{{-- Part of Phoenix project. --}}

{{-- If is DESC, we prepare ASC --}}
@if ($direction == 'DESC')
    <a href="javascript: void(0);" onclick="{{ $phoenixJsObject }}.Grid.sort('{{ $field }}', 'ASC')" class="hasTooltip"
       title="@translate('phoenix.grid.sort.button')">
        @translate($label)

        @if (isset($ordering) && $field == $ordering)
            <small class="fa fa-caret-down"></small>
        @endif
    </a>
    {{-- If is ACS, we prepare DESC --}}
@else
    <a href="javascript: void(0);" onclick="{{ $phoenixJsObject }}.Grid.sort('{{ $field }}', 'DESC')" class="hasTooltip"
       title="@translate('phoenix.grid.sort.button')">
        @translate($label)

        @if (isset($ordering) && $field == $ordering)
            <small class="fa fa-caret-up"></small>
        @endif
    </a>
@endif

