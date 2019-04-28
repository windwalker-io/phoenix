<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($paths as $i => $path)
            <li class="breadcrumb-item {{ $path->active ? 'active' : '' }}"
                aria-current="page"><a @attr('href', $path->link ?: null)
                >{{ $path->title }}</a></li>
        @endforeach
    </ol>
</nav>
