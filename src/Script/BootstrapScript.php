<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

/**
 * The BootstrapScript class.
 *
 * @see  \Phoenix\Script\ScriptManager
 * @see  \Phoenix\Script\Module\ModuleManager
 *
 * @since  {DEPLOY_VERSION}
 */
class BootstrapScript extends ScriptManager
{
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

			$asset->addStyle(static::phoenixName() . '/bootstrap/css/bootstrap.min.css');
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
			PhoenixScript::jquery(true);
			static::css();

			$asset = static::getAsset();

			$asset->addScript(static::phoenixName() . '/bootstrap/js/bootstrap.min.js');
		}
	}

	/**
	 * checkbox
	 *
	 * @return  void
	 */
	public static function checkbox()
	{
		if (!static::inited(__METHOD__))
		{
			static::css();

			$asset = static::getAsset();

			$asset->addStyle(static::phoenixName() . '/bootstrap/css/awesome-checkbox.min.css');
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
jQuery(document).ready(function($) {
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
jQuery(document).ready(function($) {
	$('{$selector}').click(function(event) {
		var \$link = $(this);
		var modal  = $('#phoenix-iframe-modal');
		var href   = \$link.attr('href');
		var iframe = modal.find('iframe');

		iframe.attr('src', href);
		modal.modal('show');

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
			PhoenixScript::jquery();
			static::css();

			$asset->addScript(static::phoenixName() . '/js/datetime/moment.min.js');
			$asset->addScript(static::phoenixName() . '/bootstrap/js/bootstrap-datetimepicker.min.js');
			$asset->addStyle(static::phoenixName() . '/bootstrap/css/bootstrap-datetimepicker.min.css');
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
}
