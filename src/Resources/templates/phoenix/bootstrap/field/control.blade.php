{{-- Part of phoenix project. --}}
<?php
/**
 * @var $field     \Windwalker\Form\Field\AbstractField|\Windwalker\Form\Field\ListField
 * @var $attribs   array
 * @var $noLabel   bool
 * @var $hideLabel bool
 */
\Phoenix\Form\FieldHelper::handle($field, $attribs);

$defaultInputWidth = \Phoenix\Script\BootstrapScript::$currentVersion === 3 ? 'col-md-9' : 'col';
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
