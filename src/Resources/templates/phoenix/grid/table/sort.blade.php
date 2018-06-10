{{-- Part of Phoenix project. --}}

{{-- If is DESC, we prepare ASC --}}
@if ($direction == 'DESC')
    <a href="javascript: void(0);" onclick="{{ $phoenixJsObject }}.Grid.sort('{{ $field }}', 'ASC')" class="has-tooltip"
       title="@lang('phoenix.grid.sort.button')">
        @lang($label)

        @if (isset($ordering) && $field == $ordering)
            <small class="fa fa-caret-down"></small>
        @endif
    </a>
    {{-- If is ACS, we prepare DESC --}}
@else
    <a href="javascript: void(0);" onclick="{{ $phoenixJsObject }}.Grid.sort('{{ $field }}', 'DESC')" class="has-tooltip"
       title="@lang('phoenix.grid.sort.button')">
        @lang($label)

        @if (isset($ordering) && $field == $ordering)
            <small class="fa fa-caret-up"></small>
        @endif
    </a>
@endif

