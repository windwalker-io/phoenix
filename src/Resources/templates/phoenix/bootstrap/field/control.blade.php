{{-- Part of phoenix project. --}}
<?php
/**
 * @var $field     \Windwalker\Form\Field\AbstractField|\Windwalker\Form\Field\ListField
 * @var $attribs   array
 * @var $noLabel   bool
 * @var $hideLabel bool
 * @var $options   array
 */
\Phoenix\Form\FieldHelper::handle($field, $attribs);

if (\Phoenix\Script\BootstrapScript::$currentVersion === 3) {
    $defaultInputWidth = 'col-md-9';
} elseif (!empty($options['vertical'])) {
    $defaultInputWidth = 'col-md-12 col-12';
} else {
    $defaultInputWidth = 'col';
}
?>

@if (isset($attribs['transition']))
    <transition name="{{ $attribs['transition'] }}">
        @endif

        @php( isset($attribs['class']) ? $attribs['class'] : null )
        @php( $attribs['class'] .= ' form-group' )

        <div {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($attribs) !!} data-form-group>
            @if (!$noLabel)
                {!! $labelHtml !!}
            @endif
            <div
                class="{{ $noLabel || $hideLabel ? 'col-md-12' : $field->get('fieldWidth', $defaultInputWidth) }} input-container">
                {!! $inputHtml !!}
            </div>
        </div>

        @if (isset($attribs['transition']))
    </transition>
@endif
