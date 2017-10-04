{{-- Part of Phoenix project. --}}

@if (!empty($only_icon))
    <span class="{{ $icon or '' }} hasTooltip" title="@translate($title)"></span>
@else
    <button type="button" class="grid-boolean-icon data-state-{{ $value or '' }} btn btn-light btn-sm hasTooltip"
        title="@translate($title)"
        {{ !empty($disabled) ? 'disabled' : null }}

        @if (!empty($task))
        onclick="{{ $phoenix_js_object }}.Grid.doTask('{{ $task or '' }}', {{ $row or '' }})"
        @endif
    >
        <span class="{{ $icon or null }}"></span>
    </button>
@endif
