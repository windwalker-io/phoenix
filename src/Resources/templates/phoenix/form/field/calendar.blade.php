{{-- Part of phoenix project. --}}

<?php
\Phoenix\Script\BootstrapScript::calendar('#' . $id . '-wrapper', $format);
?>

<div id="{{ $id }}-wrapper" class="input-group date">
    {!! $input !!}
<span class="input-group-addon">
	<span class="glyphicon glyphicon-calendar fa fa-calendar"></span>
</span>
</div>
