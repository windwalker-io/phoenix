{{-- Part of Phoenix project. --}}

@if (!empty($only_icon))
    <span class="glyphicon glyphicon-{{ $icon or null }} hasTooltip" title="@translate($title)"></span>
@else
    <button type="button" class="grid-boolean-icon data-state-{{ $value or null }} btn btn-default btn-xs hasTooltip"
        title="@translate($title)"
        {{ !empty($disabled) ? 'disabled' : null }}

        @if (!empty($task))
        onclick="Phoenix.Grid.doTask('{{ $task or null }}', {{ $row or null }})"
        @endif
    >
        <span class="glyphicon glyphicon-{{ $icon or null }}"></span>
    </button>
@endif
