{{-- Part of Phoenix project. --}}

<?php
/**
 * @var $field \Phoenix\Field\ModalField
 */

\Phoenix\Script\BootstrapScript::modal();
$disabled = $attrs['readonly'] || $attrs['disabled'];
?>

<div id="{{ $id }}-wrap" data-modal-list @attr('data-max-items', $field->max())>
    <div class="card">
        <div class="card-body p-2 d-flex">
            @if ($field->get('list_type') === \Phoenix\Field\ModalField::TYPE_TAG)
                <div class="modal-tag-container flex-grow-1 mr-3">
                    {!! $input !!}
                </div>
            @else
                <div class="text-muted pl-2 align-self-center">
                    {!! $field->get('placeholder') !!}
                </div>
            @endif
            <div class="modal-field-toolbar ml-auto">
                <a class="btn btn-info {{ $disabled ? 'disabled' : null }}" role="button"
                    href="{{ $disabled ? 'javascript:void(0);' : $url }}"
                    onclick="$(this).closest('[data-modal-list]').data('modal-list').open(event)">
                    @lang($field->getAttribute('buttonText', 'phoenix.form.field.modal.button.text'))
                </a>
            </div>
        </div>
        @if ($field->get('list_type') === \Phoenix\Field\ModalField::TYPE_LIST)
            <div class="modal-list-container list-group list-group-flush" style="max-height: {{ $field->get('height', 300) }}px; overflow-y: auto">
                {{-- items --}}
            </div>
            @if ($field->get('required'))
                @unset($attrs['value'])
                <input type="text" name="{{ $field->getFieldName() }}-placeholder" value=""
                    data-validation-placeholder class="form-control" style="display: none;"
                    {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($attrs) !!} />
            @endif
        @endif
    </div>

    @if ($field->get('list_type') === \Phoenix\Field\ModalField::TYPE_LIST)
        <script id="{{ $id }}-item-tmpl" type="text/template">
            <div class="list-group-item item">
                <div class="d-flex">
                    @if ($field->get('sortable'))
                        <div class="drag-handle mr-2" style="cursor: move;">
                            <span class="fa fa-fw fa-ellipsis-v"></span>
                        </div>
                    @endif

                    @if ($field->get('has_image'))
                        <div class="modal-list-item-image mr-2">
                            <div style="height: 2em; width: 2em; border-radius: 2px; background: url(@{{ image }}) center center; background-size: cover">

                            </div>
                        </div>
                    @endif

                    <div class="modal-list-item-title flex-grow-1">
                        @{{ title }}
                    </div>
                    <div class="modal-list-item-delete ml-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm">
                            <span class="fa fa-trash"></span>
                        </button>

                        <input data-value-store type="hidden" name="{{ $field->getFieldName() }}[]" value="@{{ value }}" />
                    </div>
                </div>
            </div>
        </script>
    @endif

</div>
