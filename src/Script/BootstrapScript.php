<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

/**
 * The BootstrapScript class.
 *
 * @see  \Phoenix\Script\ScriptManager
 * @see  \Phoenix\Script\Module\ModuleManager
 *
 * @since  1.0
 */
abstract class BootstrapScript extends AbstractScriptManager
{
	const GLYPHICONS = 'glyphicons';
	const FONTAWESOME = 'fontawesome';

	/**
	 * css
	 *
	 * @return  void
	 */
	public static function css()
	{
		if (!static::inited(__METHOD__))
		{
			$asset = static::getAsset();

			$asset->addStyle(static::phoenixName() . '/css/bootstrap/bootstrap.min.css');
		}
	}

	/**
	 * script
	 *
	 * @return  void
	 */
	public static function script()
	{
		if (!static::inited(__METHOD__))
		{
			JQueryScript::core();

			$asset = static::getAsset();

			$asset->addScript(static::phoenixName() . '/js/bootstrap/bootstrap.min.js');
		}
	}

	/**
	 * tooltip
	 *
	 * @param string $selector
	 *
	 * @return  void
	 */
	public static function tooltip($selector = '.hasTooltip')
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			static::script();
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$js = <<<JS
// Modal task
jQuery(document).ready(function($)
{
	$('{$selector}').tooltip();
});
JS;

			$asset->internalScript($js);
		}
	}

	/**
	 * checkbox
	 *
	 * @param string $iconSet
	 */
	public static function checkbox($iconSet = self::GLYPHICONS)
	{
		if (!static::inited(__METHOD__))
		{
			$asset = static::getAsset();

			$asset->addStyle(static::phoenixName() . '/css/bootstrap/awesome-checkbox.min.css');

			$font = $iconSet == static::GLYPHICONS ? 'Glyphicons Halflings' : 'FontAwesome';
			$content = $iconSet == static::GLYPHICONS ? '\\e013' : '\\f00c';

			$css = <<<CSS
/* Bootstrap Awesome Checkbox */
.checkbox input[type=checkbox]:checked + label:after {
  font-family: '$font';
  content: "$content";
}
.checkbox label:after {
  padding-left: 4px;
  padding-top: 2px;
  font-size: 9px;
}
.checkbox input {
  cursor: pointer;
}
.checkbox.single-checkbox {
  margin: 0;
  padding: 0;
  width: 21px;
}
.checkbox.single-checkbox label {
  padding: 0;
}
.checkbox.single-checkbox label::before,
.checkbox.single-checkbox label::after,
.checkbox.single-checkbox input[type=checkbox] {
  margin: 0;
}
.checkbox.single-checkbox label::after {
  padding-left: 2px;
  padding-top: 0;
}
.checkbox.single-checkbox input[type=checkbox] {
  width: 17px;
  height: 17px;
}
CSS;

			$asset->internalStyle($css);
		}
	}

	/**
	 * modal
	 *
	 * @param string $selector
	 *
	 * @return  void
	 */
	public static function modal($selector = '.hasModal')
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			static::script();

			$js = <<<JS
// Init modal
jQuery(document).ready(function($)
{
	var modalBox = $('<div class="modal fade" id="phoenix-iframe-modal"> \
    <div class="modal-dialog"> \
        <div class="modal-content"> \
            <div class="modal-body"> \
                <iframe width="100%" src="" frameborder="0"></iframe> \
            </div> \
        </div> \
    </div> \
</div>');

	$('body').append(modalBox);
});
JS;

			$asset->internalScript($js);
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$js = <<<JS
// Modal task
jQuery(document).ready(function($)
{
	$('{$selector}').click(function(event)
	{
		var link   = $(this);
		var modal  = $('#phoenix-iframe-modal');
		var href   = link.attr('href');
		var iframe = modal.find('iframe');

		iframe.attr('src', href);
		modal.modal('show');
		modal.on('hide.bs.modal', function() {
		    iframe.attr('src', '');
		})

		event.stopPropagation();
		event.preventDefault();
	});
});
JS;

			$asset->internalScript($js);
		}
	}

	/**
	 * calendar
	 *
	 * @param string $selector
	 * @param string $format
	 * @param array  $options
	 *
	 * @return  void
	 */
	public static function calendar($selector = '.hasCalendar', $format = 'YYYY-MM-DD HH:mm:ss', $options = array())
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			JQueryScript::core();

			$asset->addScript(static::phoenixName() . '/js/datetime/moment.min.js');
			$asset->addScript(static::phoenixName() . '/js/bootstrap/bootstrap-datetimepicker.min.js');
			$asset->addStyle(static::phoenixName() . '/css/bootstrap/bootstrap-datetimepicker.min.css');
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$defaultOptions = array(
				'format' => $format,
				'sideBySide' => true,
				'calendarWeeks' => true
			);

			$options = json_encode((object) array_merge($defaultOptions, $options));

			$js = <<<JS
jQuery(document).ready(function($)
{
	$('$selector').datetimepicker($options);
});
JS;

			$asset->internalScript($js);
		}
	}

	/**
	 * tabState
	 *
	 * @return  void
	 */
	public static function tabState()
	{
		if (!static::inited(__METHOD__))
		{
			JQueryScript::core();

			$asset = static::getAsset();

			$asset->addScript(static::phoenixName() . '/js/bootstrap/tab-state.min.js');
		}
	}

	public static function buttonRadio()
	{
		if (!static::inited(__METHOD__))
		{
			JQueryScript::core();

			$asset = static::getAsset();

			$asset->addScript(static::phoenixName() . '/js/bootstrap/button-radio.min.js');
		}
	}
}
