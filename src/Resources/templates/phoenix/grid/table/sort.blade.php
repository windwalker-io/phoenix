{{-- Part of phoenix project. --}}

{{-- If is DESC, we prepare ASC --}}
@if ($direction == 'DESC')
    <a href="javascript: void(0);" onclick="Phoenix.Grid.sort('{{ $field }}', 'ASC')" class="hasTooltip"
        title="@translate('phoenix.grid.sort.button')">
        @translate($label)

        @if ($field == $ordering)
            <small class="glyphicon glyphicon-triangle-bottom"></small>
        @endif
    </a>
{{-- If is ACS, we prepare DESC --}}
@else
    <a href="javascript: void(0);" onclick="Phoenix.Grid.sort('{{ $field }}', 'DESC')" class="hasTooltip"
        title="@translate('phoenix.grid.sort.button')">
        @translate($label)

        @if ($field == $ordering)
            <small class="glyphicon glyphicon-triangle-top"></small>
        @endif
    </a>
@endif

