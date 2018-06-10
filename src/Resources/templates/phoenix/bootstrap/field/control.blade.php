{{-- Part of phoenix project. --}}
<?php
/**
 * @var $field     \Windwalker\Form\Field\AbstractField|\Windwalker\Form\Field\ListField
 * @var $attribs   array
 * @var $noLabel   bool
 * @var $hideLabel bool
 */
\Phoenix\Form\FieldHelper::handle($field, $attribs);

?>

@if (isset($attribs['transition']))
    <transition name="{{ $attribs['transition'] }}">
        @endif

        @php( isset($attribs['class']) ? $attribs['class'] : null )
        @php( $attribs['class'] .= ' form-group' )

        <div {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($attribs) !!} data-form-group>
            {!! $labelHtml !!}
            <div
                class="{{ $noLabel || $hideLabel ? 'col-md-12' : $field->get('fieldWidth', 'col-md-9') }} input-container">
                {!! $inputHtml !!}
            </div>
        </div>

        @if (isset($attribs['transition']))
    </transition>
@endif
