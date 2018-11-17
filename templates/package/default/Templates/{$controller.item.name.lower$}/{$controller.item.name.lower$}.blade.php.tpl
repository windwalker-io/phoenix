{{-- Part of {$package.name.cap$} project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \{$package.namespace$}{$package.name.cap$}\{$package.name.cap$}Package                 Package object.
 * @var $view     \{$package.namespace$}{$package.name.cap$}\View\{$controller.item.name.cap$}\{$controller.item.name.cap$}HtmlView    View object.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $chronos  \Windwalker\Core\DateTime\DateTime          PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\MainRouter          Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 *
 * View variables
 * --------------------------------------------------------------
 * @var $item     \{$package.namespace$}{$package.name.cap$}\Record\{$controller.item.name.cap$}Record
 * @var $state    \Windwalker\Structure\Structure
 * @var $form     \Windwalker\Form\Form
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
    <form name="admin-form" id="admin-form" action="{{ $router->route('{$controller.item.name.lower$}', ['id' => $item->id]) }}" method="POST"
        enctype="multipart/form-data">

        <div class="row">
            <div class="col-md-7">
                <div id="fieldset-basic" class="fieldset-card form-horizontal card mb-4">
                    <h5 class="card-header">@lang('{$package.name.lower$}.{$controller.item.name.lower$}.edit.fieldset.basic')</h5>
                    <div class="card-body">

                        {!! $form->renderFields('basic') !!}
                    </div>
                </div>

                <div id="fieldset-text" class="fieldset-card form-horizontal card mb-4">
                    <h5 class="card-header">@lang('{$package.name.lower$}.{$controller.item.name.lower$}.edit.fieldset.text')</h5>
                    <div class="card-body">
                        {!! $form->renderFields('text') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div id="fieldset-created" class="fieldset-card form-horizontal card mb-4">
                    <h5 class="card-header">@lang('{$package.name.lower$}.{$controller.item.name.lower$}.edit.fieldset.created')</h5>
                    <div class="card-body">
                    {!! $form->renderFields('created') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden-inputs">
            @formToken
        </div>

    </form>
@stop
