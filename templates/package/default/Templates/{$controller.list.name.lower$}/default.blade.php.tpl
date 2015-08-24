{{-- Part of phoenix project. --}}

@extends('_global.admin')

@section('body')

<form name="admin-form" id="admin-form" action="{{ $router->html('{$controller.list.name.lower$}', array('_method' => 'PUT')) }}" method="POST" enctype="multipart/form-data">

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
                    #
                </th>

                {{-- TITLE --}}
                <th>
                    Title
                </th>

                {{-- AUTHOR --}}
                <th>
                    Author
                </th>

                {{-- CREATED --}}
                <th>
                    Created
                </th>

                {{-- LANGUAGE --}}
                <th>
                    Language
                </th>

                {{-- ID --}}
                <th>
                    ID
                </th>
            </tr>
            </thead>

            <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>
                        {{{ $item->title }}}
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

</form>
@stop
