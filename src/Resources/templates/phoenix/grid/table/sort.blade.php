{{-- Part of phoenix project. --}}

{{-- If is DESC, we prepare ASC --}}
@if ($direction == 'DESC')
    <a href="javascript: void(0);" data-sort-button data-sort-field="{{{ $field }}}" data-sort-direction="ASC">
        {{{ $label }}}

        @if ($field == $ordering)
            <small class="glyphicon glyphicon-triangle-bottom"></small>
        @endif
    </a>
{{-- If is ACS, we prepare DESC --}}
@else
    <a href="javascript: void(0);" data-sort-button data-sort-field="{{{ $field }}}" data-sort-direction="DESC">
        {{{ $label }}}

        @if ($field == $ordering)
            <small class="glyphicon glyphicon-triangle-top"></small>
        @endif
    </a>
@endif

