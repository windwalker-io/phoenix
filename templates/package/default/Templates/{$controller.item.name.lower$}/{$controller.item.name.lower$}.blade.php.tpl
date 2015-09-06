{{-- Part of phoenix project. --}}

@extends('_global.{$package.name.lower$}.admin-edit')

@section('toolbar')
    @include('toolbar')
@stop

@section('body')
<form name="admin-form" id="admin-form" action="{{ $router->html('{$controller.item.name.lower$}', array($item->id)) }}" method="POST" enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-7">
            <fieldset class="form-horizontal">
                <legend>@translate('{$package.name.lower$}.{$controller.item.name.lower$}.edit.fieldset.basic')</legend>

                {{ $form->renderFields('basic') }}
            </fieldset>

            <fieldset class="form-horizontal">
                <legend>@translate('{$package.name.lower$}.{$controller.item.name.lower$}.edit.fieldset.text')</legend>

                {{ $form->renderFields('text') }}
            </fieldset>
        </div>
        <div class="col-md-5">
            <fieldset class="form-horizontal">
                <legend>@translate('{$package.name.lower$}.{$controller.item.name.lower$}.edit.fieldset.created')</legend>

                {{ $form->renderFields('created') }}
            </fieldset>
        </div>
    </div>

    <div class="hidden-inputs">
        {{ \Windwalker\Core\Security\CsrfProtection::input() }}
    </div>

</form>
@stop
