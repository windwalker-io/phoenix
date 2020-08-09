{{-- Part of phoenix project. --}}
<?php
/**
 * @var $form \Windwalker\Form\Form
 * @var $field \Phoenix\Field\RepeatableField
 * @var $attrs array
 */

// Set renderer
$form->setRenderer($field->getForm()->getRenderer());

$singleArray = $field->singleArray();

$hasKey = (bool) $form->getField('key');
?>
<div id="{{ $field->getId() }}-wrap" class="phoenix-repeatable"
    data-has-key="{{ (int) $hasKey }}" data-single-array="{{ (int) $singleArray }}">
    <table class="table">
        <thead>
        <tr>
            @if ($field->sortable())
                <th>#</th>
            @endif
            @foreach ($form->getFields() as $subField)
                <th class="{{ $subField->getType() === 'hidden' ? 'sr-only' : '' }}">
                    {!! $subField->getLabel() !!}
                </th>
            @endforeach
            <th class="text-right">
                <button type="button" class="btn btn-sm btn-success btn-primary"
                    @click="addItem(-1)">
                    <span class="fa fa-plus"></span>
                </button>
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
                    <td class="{{ $subField->getType() === 'hidden' ? 'sr-only' : '' }}">
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
        <?php unset($attrs['value']) ?>
        {!! new \Windwalker\Dom\HtmlElement('div', null, $attrs) !!}
    </div>
</div>
