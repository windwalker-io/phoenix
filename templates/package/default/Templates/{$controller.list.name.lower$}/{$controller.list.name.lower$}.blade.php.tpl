{{-- Part of {$package.name.cap$} project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app           \Windwalker\Web\Application                 Global Application
 * @var $package       \{$package.namespace$}{$package.name.cap$}\{$package.name.cap$}Package                 Package object.
 * @var $view          \{$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$}\{$controller.list.name.cap$}HtmlView  View object.
 * @var $uri           \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $chronos       \Windwalker\Core\DateTime\DateTime          PHP DateTime object of current time.
 * @var $helper        \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router        \Windwalker\Core\Router\MainRouter          Route builder object.
 * @var $asset         \Windwalker\Core\Asset\AssetManager         The Asset manager.
 *
 * View variables
 * --------------------------------------------------------------
 * @var $filterBar     \Windwalker\Core\Widget\Widget
 * @var $filterForm    \Windwalker\Form\Form
 * @var $batchForm     \Windwalker\Form\Form
 * @var $showFilterBar boolean
 * @var $grid          \Phoenix\View\Helper\GridHelper
 * @var $state         \Windwalker\Structure\Structure
 * @var $items         \Windwalker\Data\DataSet
 * @var $item          \{$package.namespace$}{$package.name.cap$}\Record\{$controller.item.name.cap$}Record
 * @var $i             integer
 * @var $pagination    \Windwalker\Core\Pagination\Pagination
 */
?>

@extends('_global.{$package.name.lower$}.admin')

@section('toolbar-buttons')
    @include('toolbar')
@stop

@push('script')
    {{-- Add Script Here --}}
@endpush

@section('admin-body')
    <div id="phoenix-admin" class="{$controller.list.name.lower$}-container grid-container">
        <form name="admin-form" id="admin-form" action="{{ $router->route('{$controller.list.name.lower$}') }}" method="POST"
            enctype="multipart/form-data">

            {{-- FILTER BAR --}}
            <div class="filter-bar">
                {!! $filterBar->render(['form' => $form, 'show' => $showFilterBar]) !!}
            </div>

            @if (count($items))
                {{-- RESPONSIVE TABLE DESC --}}
                <p class="visible-xs-block d-sm-block d-md-none">
                    @lang('phoenix.grid.responsive.table.desc')
                </p>

                <div class="grid-table">
                    <table class="table table-striped table-bordered table-responsive">
                        <thead>
                        <tr>
                            {{-- CHECKBOX --}}
                            <th width="1%" class="text-nowrap">
                                {!! $grid->checkboxesToggle(['duration' => 150]) !!}
                            </th>

                            {{-- STATE --}}
                            <th style="min-width: 70px;" width="8%" class="text-nowrap">
                                {!! $grid->sortTitle('{$package.name.lower$}.{$controller.item.name.lower$}.field.state', '{$controller.item.name.lower$}.state') !!}
                            </th>

                            {{-- TITLE --}}
                            <th class="text-nowrap" style="min-width: 300px;">
                                {!! $grid->sortTitle('{$package.name.lower$}.{$controller.item.name.lower$}.field.title', '{$controller.item.name.lower$}.title') !!}
                            </th>

                            {{-- ORDERING --}}
                            <th width="5%" class="text-nowrap">
                                {!! $grid->sortTitle('{$package.name.lower$}.{$controller.item.name.lower$}.field.ordering', '{$controller.item.name.lower$}.ordering') !!}
                                {!! $grid->saveOrderButton() !!}
                            </th>

                            {{-- AUTHOR --}}
                            <th width="10%" class="text-nowrap">
                                {!! $grid->sortTitle('{$package.name.lower$}.{$controller.item.name.lower$}.field.author', '{$controller.item.name.lower$}.created_by') !!}
                            </th>

                            {{-- CREATED --}}
                            <th width="10%" class="text-nowrap">
                                {!! $grid->sortTitle('{$package.name.lower$}.{$controller.item.name.lower$}.field.created', '{$controller.item.name.lower$}.created') !!}
                            </th>

                            {{-- LANGUAGE --}}
                            <th width="7%" class="text-nowrap">
                                {!! $grid->sortTitle('{$package.name.lower$}.{$controller.item.name.lower$}.field.language', '{$controller.item.name.lower$}.language') !!}
                            </th>

                            {{-- DELETE --}}
                            <th width="1%" class="text-nowrap">
                                @lang('{$package.name.lower$}.{$controller.item.name.lower$}.field.delete')
                            </th>

                            {{-- ID --}}
                            <th width="3%" class="text-nowrap text-right">
                                {!! $grid->sortTitle('{$package.name.lower$}.{$controller.item.name.lower$}.field.id', '{$controller.item.name.lower$}.id') !!}
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($items as $i => $item)
                            <?php
                            $grid->setItem($item, $i);
                            ?>
                            <tr data-order-group="">
                                {{-- CHECKBOX --}}
                                <td>
                                    {!! $grid->checkbox() !!}
                                </td>

                                {{-- STATE --}}
                                <td class="text-nowrap">
                                    <span class="btn-group">
                                        {!! $grid->published($item->state) !!}
                                        <button type="button" class="btn btn-default btn-light btn-sm has-tooltip"
                                            onclick="Phoenix.Grid.copyRow({{ $i }});"
                                            title="@lang('phoenix.toolbar.duplicate')">
                                            <span class="fa fa-fw fa-copy text-info"></span>
                                        </button>
                                    </span>
                                </td>

                                {{-- TITLE --}}
                                <td>
                                    <a href="{{ $router->route('{$controller.item.name.lower$}', ['id' => $item->id]) }}">
                                        {{ $item->title }}
                                    </a>
                                </td>

                                {{-- ORDERING --}}
                                <td class="text-right">
                                    {!! $grid->orderButton() !!}
                                </td>

                                {{-- AUTHOR --}}
                                <td class="text-nowrap">
                                    {{ property_exists($item, 'user_name') ? $item->user_name : $item->created_by }}
                                </td>

                                {{-- CREATED --}}
                                <td class="text-nowrap">
                                    <span class="has-tooltip"
                                        title="{{ $datetime::toLocalTime($item->created, 'Y-m-d H:i:s') }}">
                                        {{ $datetime::toLocalTime($item->created, 'Y-m-d') }}
                                    </span>
                                </td>

                                {{-- LANGUAGE --}}
                                <td class="text-nowrap">
                                    {{ $item->language }}
                                </td>

                                {{-- DELETE --}}
                                <td class="text-center">
                                    <button type="button" class="btn btn-default btn-outline-secondary btn-sm has-tooltip"
                                        onclick="Phoenix.Grid.deleteRow({{ $i }});"
                                        title="@lang('phoenix.toolbar.delete')">
                                        <span class="fa fa-fw fa-trash"></span>
                                    </button>
                                </td>

                                {{-- ID --}}
                                <td class="text-right">
                                    {{ $item->id }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr>
                            {{-- PAGINATION --}}
                            <td colspan="25">
                                {!! $pagination->route('{$controller.list.name.lower$}', [])->render() !!}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="grid-no-items card bg-light" style="padding: 125px 0;">
                    <div class="card-body text-center">
                        <h3 class="text-secondary">@lang('phoenix.grid.no.items')</h3>
                    </div>
                </div>
            @endif

            <div class="hidden-inputs">
                {{-- METHOD --}}
                <input type="hidden" name="_method" value="PUT" />

                {{-- TOKEN --}}
                @formToken
            </div>

            @include('_global.{$package.name.lower$}.widget.batch')
        </form>
    </div>
@stop
