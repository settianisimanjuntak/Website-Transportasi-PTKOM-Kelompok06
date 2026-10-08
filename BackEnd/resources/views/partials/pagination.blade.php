@if ($paginator->hasPages())
  <style>
    .pg-nav{display:flex;gap:6px;align-items:center;flex-wrap:wrap;}
    .pg-nav a,.pg-nav span{
      min-width:28px;height:28px;padding:0 8px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;
      border:1px solid var(--line);background:#fff;color:var(--ink-soft);font-size:12.5px;font-weight:700;text-decoration:none;
    }
    .pg-nav a:hover{border-color:var(--green-600);color:var(--green-700);}
    .pg-nav span.active{background:var(--green-600);color:#fff;border-color:var(--green-600);}
    .pg-nav span.disabled{opacity:.5;}
  </style>
  <nav class="pg-nav" role="navigation" aria-label="Navigasi halaman">
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="disabled">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="active">{{ $page }}</span>
          @else
            <a href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach
  </nav>
@endif
