{{-- Part of phoenix project. --}}

@extends('_global.admin')

@section('script')
    <script src="{{ $uri['media.path'] }}phoenix/js/phoenix.js"></script>
    <script src="{{ $uri['media.path'] }}phoenix/js/filterbar.js"></script>
    <script>
        jQuery(document).ready(function($)
        {
            var form = $('#admin-form');

            window.Phoenix = new PhoenixCore(form, {});

            form.filterbar();
        });
    </script>
@stop

@section('toolbar')
    @include('toolbar')
@stop

@section('body')

<form name="admin-form" id="admin-form" action="{{ $router->html('{$controller.list.name.lower$}') }}" method="POST" enctype="multipart/form-data">

    <div class="{$controller.item.name.lower$}-items">

        <div class="filter-bar">
            {{ $filterBar->render(array('form' => $filterForm)) }}
        </div>

        <table class="table table-bordered">
            <thead>
            <tr>
                {{-- SORT --}}
                <th>
                    #
                </th>

                {{-- CHECKBOX --}}
                <th>
                    {{ $grid->checkboxesToggle() }}
                </th>

                <th>
                    {{ $grid->sortTitle('State', '{$controller.item.name.lower$}.state') }}
                </th>

                {{-- TITLE --}}
                <th>
                    {{ $grid->sortTitle('Title', '{$controller.item.name.lower$}.title') }}
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
                /**  @var \Phoenix\View\Helper\GridHelper $grid */
                $grid->setItem($item, $i);
                ?>
                <tr>
                    <td>

                    </td>
                    <td>
                        {{ $grid->checkbox() }}
                    </td>
                    <td>
                        {{ $grid->state($item->state) }}
                    </td>
                    <td>
                        <a href="{{{ $router->html('{$controller.item.name.lower$}', array('id' => $item->id)) }}}">
                            {{{ $item->title }}}
                        </a>
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
@stop
