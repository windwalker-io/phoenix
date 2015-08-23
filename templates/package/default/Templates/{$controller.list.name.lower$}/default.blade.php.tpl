{{-- Part of phoenix project. --}}

@extends('_global.admin')

@section('body')

<div class="{$controller.item.name.lower$}-items">
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
    </table>
</div>

@stop
