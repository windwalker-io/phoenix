{{-- Part of phoenix project. --}}

@if (isset($attribs['transition']))
    <transition name="{{ $attribs['transition'] }}">
@endif

    @php( isset($attribs['class']) ? $attribs['class'] = '' : null )
    @php( $attribs['class'] .= ' form-group' )

    <div {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($attribs) !!}>
        {!! $labelHtml !!}
        <div class="{{ $field->get('fieldWidth', 'col-md-9') }} input-container">
            {!! $inputHtml !!}
        </div>
    </div>

@if (isset($attribs['transition']))
    </transition>
@endif
