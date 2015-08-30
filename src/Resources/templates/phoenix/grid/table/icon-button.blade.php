{{-- Part of phoenix project. --}}

@if ($options['only_icon'])
    <span class="glyphicon glyphicon-{{ $iconMapping[$value] }}" title="{{ $options['titleMapping'][$value] }}"></span>
@else
    <button type="button" class="grid-boolean-icon data-state-{{ $value }} btn btn-default btn-xs"
        title="{{ $options['titleMapping'][$value] }}"
        onclick="Phoenix.updateRow({{ $row }}, null, {task: '{{ $taskMapping[$value] }}'})">
        <span class="glyphicon glyphicon-{{ $iconMapping[$value] }}"></span>
    </button>
@endif
