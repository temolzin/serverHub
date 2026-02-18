@if ($instances->hasPages())
  <nav>
    <ul class="pagination justify-content-end">
      {!! $instances->links() !!}
    </ul>
  </nav>
@endif
