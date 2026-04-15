<?php

namespace App\Models;

trait HasManualImportOverrides
{
    public function getManualOverrideFields(): array
    {
        return collect($this->manual_override_fields ?? [])
            ->filter(fn($field) => is_string($field) && $field !== '')
            ->values()
            ->all();
    }

    public function getPendingManualOverrideFields(): array
    {
        return collect($this->pending_manual_override_fields ?? [])
            ->filter(fn($field) => is_string($field) && $field !== '')
            ->values()
            ->all();
    }

    public function mergeManualOverrideFields(array $fields): void
    {
        $fields = $this->normalizeManualOverrideFields($fields);

        if (empty($fields)) {
            return;
        }

        $this->manual_override_fields = collect($fields)
            ->merge($this->getManualOverrideFields())
            ->unique()
            ->values()
            ->all();
    }

    public function mergePendingManualOverrideFields(array $fields): void
    {
        $fields = $this->normalizeManualOverrideFields($fields);

        if (empty($fields)) {
            return;
        }

        $this->pending_manual_override_fields = collect($fields)
            ->merge($this->getPendingManualOverrideFields())
            ->unique()
            ->values()
            ->all();
    }

    public function releaseManualOverrideFields(array $fields): void
    {
        $fields = $this->normalizeManualOverrideFields($fields);

        if (empty($fields)) {
            return;
        }

        $this->manual_override_fields = collect($this->getManualOverrideFields())
            ->reject(fn($field) => in_array($field, $fields, true))
            ->values()
            ->all();

        $this->pending_manual_override_fields = collect($this->getPendingManualOverrideFields())
            ->reject(fn($field) => in_array($field, $fields, true))
            ->values()
            ->all();
    }

    public function markManualOverridesAsReported(array $fields): void
    {
        $fields = $this->normalizeManualOverrideFields($fields);

        if (empty($fields)) {
            return;
        }

        $this->pending_manual_override_fields = collect($this->getPendingManualOverrideFields())
            ->reject(fn($field) => in_array($field, $fields, true))
            ->values()
            ->all();
    }

    public function protectManualOverridesDuringImport(array $payload): array
    {
        $releasedFields = [];
        $protectedFields = [];
        $reportedFields = [];
        $pendingFields = $this->getPendingManualOverrideFields();

        foreach ($this->getManualOverrideFields() as $field) {
            if (!array_key_exists($field, $payload)) {
                continue;
            }

            if ($this->manualOverrideMatchesIncomingValue($field, $payload[$field])) {
                $releasedFields[] = $field;
                continue;
            }

            unset($payload[$field]);
            $protectedFields[] = $field;

            if (in_array($field, $pendingFields, true)) {
                $reportedFields[] = $field;
            }
        }

        return [
            'payload' => $payload,
            'released_fields' => $releasedFields,
            'protected_fields' => $protectedFields,
            'reported_fields' => $reportedFields,
        ];
    }

    private function normalizeManualOverrideFields(array $fields): array
    {
        $ignoredFields = [
            'created_at',
            'updated_at',
            'deleted_at',
            'manual_override_fields',
            'pending_manual_override_fields',
        ];

        $fields = collect($fields)
            ->map(fn($field) => (string) $field)
            ->filter(fn($field) => $field !== '' && !in_array($field, $ignoredFields, true))
            ->unique()
            ->values()
            ->all();

        return $fields;
    }

    private function manualOverrideMatchesIncomingValue(string $field, mixed $incomingValue): bool
    {
        return $this->normalizeComparableImportValue($this->getAttribute($field))
            === $this->normalizeComparableImportValue($incomingValue);
    }

    private function normalizeComparableImportValue(mixed $value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if ($value === null) {
            return '__null__';
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return trim((string) $value);
    }
}
