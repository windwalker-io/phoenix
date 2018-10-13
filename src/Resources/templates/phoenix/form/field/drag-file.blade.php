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
{{--<div class="custom-file drag-custom-file">--}}
    {{--<input type="file" class="custom-file-input" name="item[subtrack_file]"--}}
        {{--id="input-item-subtrack_file"--}}
        {{--data-file-limit="1" data-size-limit="700" data-accepted="zip">--}}
    {{--<label class="custom-file-label px-3" for="input-item-subtrack_file">--}}
                                    {{--<span class="label-text"><span class="fa fa-upload"></span>--}}
                                        {{--上傳分軌檔案: 拖拉檔案或按此瀏覽</span>--}}
    {{--</label>--}}
{{--</div>--}}
