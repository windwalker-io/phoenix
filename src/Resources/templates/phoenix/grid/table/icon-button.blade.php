{{-- Part of Phoenix project. --}}

@if (!empty($options['only_icon']))
    <span class="glyphicon glyphicon-{{ $iconMapping[$value] or null }}" title="@translate(isset($options['titleMapping'][$value]) ? $options['titleMapping'][$value] : null)"></span>
@else
    <button type="button" class="grid-boolean-icon data-state-{{ $value }} btn btn-default btn-xs hasTooltip"
        title="@translate(isset($options['titleMapping'][$value]) ? $options['titleMapping'][$value] : null)"
        onclick="Phoenix.Grid.doTask('{{ $taskMapping[$value] or null }}', {{ $row }})">
        <span class="glyphicon glyphicon-{{ $iconMapping[$value] or null }}"></span>
    </button>
@endif
