<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Phoenix\Asset\AssetManager;
use Phoenix\Script\Module\Module;
use Phoenix\Script\Module\ModuleManager;

/**
 * The BootstrapScript class.
 *
 * @see  \Phoenix\Script\ScriptManager
 * @see  \Phoenix\Script\Module\ModuleManager
 *
 * @method  static  boolean  css()
 * @method  static  boolean  script()
 * @method  static  boolean  modal($selector = '.hasModal')
 * @method  static  boolean  calendar($selector = '.hasCalendar', $format = 'YYYY-MM-DD HH:mm:ss')
 *
 * @since  {DEPLOY_VERSION}
 */
class BootstrapScript extends ScriptManager
{
	/**
	 * registerModules
	 *
	 * @param ModuleManager $moduleManager
	 *
	 * @return  void
	 */
	protected function registerModules(ModuleManager $moduleManager)
	{
		// css()
		// -------------------------------------------------------
		$moduleManager->addModule('css', function(Module $module, AssetManager $asset)
		{
			if (!$module->inited())
			{
				$asset->addStyle('phoenix/bootstrap/css/bootstrap.min.css');
			}
		});

		// script()
		// -------------------------------------------------------
		$moduleManager->addModule('script', function(Module $module, AssetManager $asset)
		{
			PhoenixScript::jquery(true);
			static::css();

			if (!$module->inited())
			{
				$asset->addScript('phoenix/bootstrap/js/bootstrap.min.js');
			}
		});

		// modal()
		// -------------------------------------------------------
		$moduleManager->addModule('modal', function(Module $module, AssetManager $asset, $selector = '.hasModal')
		{
			static::script();

			if (!$module->inited())
			{
				$tmpl = <<<MODAL
<div class="modal fade" id="phoenix-iframe-modal"> \
    <div class="modal-dialog"> \
        <div class="modal-content"> \
            <div class="modal-body"> \
                <iframe width="100%" src="" frameborder="0"></iframe> \
            </div> \
        </div> \
    </div> \
</div>
MODAL;

				$js = <<<JS

// Init modal
jQuery(document).ready(function($) {
	var modalBox = $('{$tmpl}');

	$('body').append(modalBox);
});
JS;

				$asset->internalScript($js);
			}

			if (!$module->stateInited())
			{
				$js = <<<JS

// Modal task
jQuery(document).ready(function($) {
	\$('{$selector}').click(function(event) {
		var \$link = \$(this);
		var modal  = \$('#phoenix-iframe-modal');
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
		});

		// calendar()
		// -------------------------------------------------------
		$moduleManager->addModule('calendar', function(Module $module, AssetManager $asset,
			$selector = '.hasCalendar', $format = 'YYYY-MM-DD HH:mm:ss', $options = array())
		{
			PhoenixScript::jquery();
			static::css();

			if (!$module->inited())
			{
				$asset->addScript('phoenix/js/datetime/moment.min.js');
				$asset->addScript('phoenix/bootstrap/js/bootstrap-datetimepicker.min.js');
				$asset->addStyle('phoenix/bootstrap/css/bootstrap-datetimepicker.min.css');
			}

			if (!$module->stateInited())
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
	\$('$selector').datetimepicker($options);
});
JS;

				$asset->internalScript($js);
			}
		});
	}
}
