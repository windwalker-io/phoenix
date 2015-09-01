{{-- Part of phoenix project. --}}

@extends('_global.admin-edit')

@section('toolbar')
    @include('toolbar')
@stop

@section('body')
<form name="admin-form" id="admin-form" action="{{ $router->html('{$controller.item.name.lower$}', array($item->id)) }}" method="POST" enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-8">
            <fieldset class="form-horizontal">
                <legend>Basic</legend>

                {{ $form->renderFields('basic') }}
            </fieldset>

            <fieldset class="form-horizontal">
                <legend>Text</legend>

                {{ $form->renderFields('text') }}
            </fieldset>
        </div>
        <div class="col-md-4">
            <fieldset class="form-horizontal">
                <legend>Created</legend>

                {{ $form->renderFields('created') }}
            </fieldset>
        </div>
    </div>

    <div class="hidden-inputs">
        {{ \Windwalker\Core\Security\CsrfProtection::input() }}
    </div>

</form>
@stop
