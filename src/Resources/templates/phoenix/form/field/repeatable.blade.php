{{-- Part of phoenix project. --}}
<?php
/**
 * @var $form \Windwalker\Form\Form
 * @var $field \Phoenix\Field\RepeatableField
 * @var $attrs array
 */

// Set renderer
$form->setRenderer($field->getForm()->getRenderer());
?>
<div id="{{ $field->getId() }}-wrap" class="phoenix-repeatable">
    <table class="table">
        <thead>
        <tr>
            @if ($field->sortable())
                <th>#</th>
            @endif
            @foreach ($form->getFields() as $subField)
                <th>
                    {!! $subField->getLabel() !!}
                </th>
            @endforeach
            <th class="text-right">
                <span class="fa fa-cog"></span>
            </th>
        </tr>
        </thead>
        <tbody v-model="items" is="{{ $field->sortable() ? 'draggable' : 'tbody' }}" element="tbody"
            :options="{ handle: '.drag-handle', animation: 300 }">
            <tr v-for="(item, i) of items" :key="item.__key" :ref="`repeat-item-${i}`">
                @if ($field->sortable())
                    <td>
                        <div class="drag-handle" style="cursor: move;">
                            <span class="fa fa-ellipsis-v" ></span>
                        </div>
                    </td>
                @endif
                @foreach ($form->getFields() as $subField)
                    <td>
                        {!! $subField->renderInput() !!}
                    </td>
                @endforeach
                <td class="text-nowrap text-right" width="1%">
                    <button type="button" class="btn btn-sm btn-success btn-primary"
                        @click="addItem(i)">
                        <span class="fa fa-plus"></span>
                    </button>
                    <button type="button" class="btn btn-sm btn-success btn-danger"
                        @click="delItem(i)">
                        <span class="fa fa-trash"></span>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="d-none">
        {!! new \Windwalker\Dom\HtmlElement('div', null, $attrs) !!}
    </div>
</div>
