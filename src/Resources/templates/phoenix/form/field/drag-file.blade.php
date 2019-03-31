{{-- Part of phoenix project. --}}
<?php
/**
 * @var  \Windwalker\Form\Field\AbstractField $field
 * @var  \Windwalker\Dom\HtmlElement $input
 * @var  array                       $attrs
 */
?>
<div class="custom-file drag-file-input">
    {!! $input !!}
    <label class="custom-file-label px-3" for="{{ $id }}">
        <span class="label-text">
            <span class="fa fa-upload"></span>
        </span>
    </label>
</div>
