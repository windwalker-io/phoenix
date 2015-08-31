{{-- Part of phoenix project. --}}

@if (!empty($options['only_icon']))
    <span class="glyphicon glyphicon-{{ isset($iconMapping[$value]) ? $iconMapping[$value] : null }}" title="{{ $options['titleMapping'][$value] }}"></span>
@else
    <button type="button" class="grid-boolean-icon data-state-{{ $value }} btn btn-default btn-xs"
        title="{{ isset($options['titleMapping'][$value]) ? $options['titleMapping'][$value] : null }}"
        onclick="Phoenix.updateRow({{ $row }}, null, {task: '{{ isset($taskMapping[$value]) ? $taskMapping[$value] : null }}'})">
        <span class="glyphicon glyphicon-{{ $iconMapping[$value] }}"></span>
    </button>
@endif
