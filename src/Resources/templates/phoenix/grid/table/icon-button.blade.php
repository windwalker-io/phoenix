{{-- Part of phoenix project. --}}

@if (!empty($options['only_icon']))
    <span class="glyphicon glyphicon-{{ isset($iconMapping[$value]) ? $iconMapping[$value] : null }}" title="@translate(isset($options['titleMapping'][$value]) ? $options['titleMapping'][$value] : null)"></span>
@else
    <button type="button" class="grid-boolean-icon data-state-{{ $value }} btn btn-default btn-xs hasTooltip"
        title="@translate(isset($options['titleMapping'][$value]) ? $options['titleMapping'][$value] : null)"
        onclick="Phoenix.Grid.updateRow({{ $row }}, null, {task: '{{ isset($taskMapping[$value]) ? $taskMapping[$value] : null }}'})">
        <span class="glyphicon glyphicon-{{ isset($iconMapping[$value]) ? $iconMapping[$value] : null }}"></span>
    </button>
@endif
