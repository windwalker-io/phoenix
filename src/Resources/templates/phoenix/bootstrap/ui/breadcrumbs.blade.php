<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($paths as $i => $path)
            <li class="breadcrumb-item {{ $path->active ? 'active' : '' }}" aria-current="page">
                @if ($path->link)
                    <a href="{{ $path->link }}">
                        {{ $path->title }}
                    </a>
                @else
                    {{ $path->title }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>
