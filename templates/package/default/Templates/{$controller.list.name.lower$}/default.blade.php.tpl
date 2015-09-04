{{-- Part of phoenix project. --}}

@extends('_global.{$package.name.lower$}.admin')

@section('toolbar')
    @include('toolbar')
@stop

@section('body')
<div id="phoenix-admin" class="{$controller.list.name.lower$}-container grid-container">
    <form name="admin-form" id="admin-form" action="{{ $router->html('{$controller.list.name.lower$}') }}" method="POST" enctype="multipart/form-data">

        <div class="filter-bar">
            {{ $filterBar->render(array('form' => $filterForm, 'show' => $showFilterBar)) }}
        </div>

        <div class="grid-table">
            <table class="table table-bordered">
                <thead>
                <tr>
                    {{-- CHECKBOX --}}
                    <th>
                        {{ $grid->checkboxesToggle(array('duration' => 150)) }}
                    </th>

                    <th>
                        {{ $grid->sortTitle('State', '{$controller.item.name.lower$}.state') }}
                    </th>

                    {{-- TITLE --}}
                    <th>
                        {{ $grid->sortTitle('Title', '{$controller.item.name.lower$}.title') }}
                    </th>

                    {{-- ORDERING --}}
                    <th width="5%" class="nowrap">
                        {{ $grid->sortTitle('Order', '{$controller.item.name.lower$}.ordering') }} {{ $grid->saveorderButton() }}
                    </th>

                    {{-- AUTHOR --}}
                    <th>
                        {{ $grid->sortTitle('Author', '{$controller.item.name.lower$}.created_by') }}
                    </th>

                    {{-- CREATED --}}
                    <th>
                        {{ $grid->sortTitle('Created', '{$controller.item.name.lower$}.created') }}
                    </th>

                    {{-- LANGUAGE --}}
                    <th>
                        {{ $grid->sortTitle('Language', '{$controller.item.name.lower$}.language') }}
                    </th>

                    {{-- ID --}}
                    <th>
                        {{ $grid->sortTitle('ID', '{$controller.item.name.lower$}.id') }}
                    </th>
                </tr>
                </thead>

                <tbody>
                @foreach ($items as $i => $item)
                    <?php
                    $grid->setItem($item, $i);
                    ?>
                    <tr>
                        <td>
                            {{ $grid->checkbox() }}
                        </td>
                        <td>
                            <span class="btn-group">
                                {{ $grid->state($item->state) }}
                                <button type="button" class="btn btn-default btn-xs hasTooltip" onclick="Phoenix.Grid.copyRow({{ $i }});"
                                    title="@translate('phoenix.toolbar.button.duplicate')">
                                    <span class="glyphicon glyphicon-duplicate text-info"></span>
                                </button>
                                <button type="button" class="btn btn-default btn-xs hasTooltip" onclick="Phoenix.Grid.deleteRow({{ $i }});"
                                    title="@translate('phoenix.toolbar.button.delete')">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </span>
                        </td>
                        <td>
                            <a href="{{{ $router->html('{$controller.item.name.lower$}', array('id' => $item->id)) }}}">
                                {{{ $item->title }}}
                            </a>
                        </td>
                        <td>
                            {{ $grid->orderButton() }}
                        </td>
                        <td>
                            {{{ $item->created_by }}}
                        </td>
                        <td>
                            {{{ $item->created }}}
                        </td>
                        <td>
                            {{{ $item->language }}}
                        </td>
                        <td>
                            {{{ $item->id }}}
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <td colspan="25">
                        {{ $pagination }}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="hidden-inputs">
            <input type="hidden" name="_method" value="PUT" />
            {{ \Windwalker\Core\Security\CsrfProtection::input() }}
        </div>

        @include('batch')
    </form>
</div>
@stop
