<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Notifications\ResetPassword;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->name} {$this->last_name}");
    }

    public function sendPasswordResetNotification($token)
    {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ], false));

        $this->notify(new class($url) extends ResetPassword {
            protected $url;

            public function __construct($url)
            {
                $this->url = $url;
            }

            public function toMail($notifiable)
            {
                return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->subject('Restablecer contraseña - ServerHub')
                    ->greeting('¡Hola!')
                    ->line('Recibimos una solicitud para restablecer tu contraseña.')
                    ->action('Restablecer contraseña', $this->url)
                    ->line('Este enlace expirará en 60 minutos.')
                    ->line('Si no solicitaste esto, puedes ignorar este mensaje.');
            }
        });
    }
}
