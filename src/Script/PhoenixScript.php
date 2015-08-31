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
 * The PhoenixScript class.
 *
 * @see  \Phoenix\Script\ScriptManager
 * @see  \Phoenix\Script\Module\ModuleManager
 *
 * @method  static  boolean  jquery($noConflict = false)
 * @method  static  boolean  core($formSelector = '#admin-form', $options = array())
 * @method  static  boolean  filterbar($formSelector = '#admin-form', $options = array())
 *
 * @since  {DEPLOY_VERSION}
 */
class PhoenixScript extends ScriptManager
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
		// jquery()
		// -------------------------------------------------------
		$moduleManager->addModule('jquery', function(Module $module, AssetManager $asset,
			$noConflict = false)
		{
			if (!$module->inited())
			{
				$asset->addScript('phoenix/js/jquery/jquery.js');
			}

			if (!$module->stateInited() && $noConflict)
			{
				$asset->internalScript('jQuery.noConflict();');
			}
		});

		// core()
		// -------------------------------------------------------
		$moduleManager->addModule('core', function(Module $module, AssetManager $asset,
			$formSelector = '#admin-form', $options = array())
		{
			static::jquery();

			if (!$module->inited())
			{
				$asset->addScript('phoenix/js/phoenix.js');
			}

			if (!$module->stateInited())
			{
				$options = json_encode((object) $options);

				$js = <<<JS
// Phoenix Core
jQuery(document).ready(function($)
{
	var form = $('$formSelector');

	window.Phoenix = new PhoenixCore(form, $options);
});
JS;

				$asset->internalScript($js);
			}
		});

		// filterbar()
		// -------------------------------------------------------
		$moduleManager->addModule('filterbar', function(Module $module, AssetManager $asset,
			$formSelector = '#admin-form', $options = array())
		{
			static::core();

			if (!$module->inited())
			{
				$asset->addScript('phoenix/js/filterbar.js');
			}

			if (!$module->stateInited())
			{
				$options = json_encode((object) $options);

				$js = <<<JS
// Filter bar
jQuery(document).ready(function($)
{
	var form = $('$formSelector');

	form.filterbar($options);
});
JS;

				$asset->internalScript($js);
			}
		});

		// chosen()
		// -------------------------------------------------------
		$moduleManager->addModule('chosen', function(Module $module, AssetManager $asset,
			$selector = 'select', $options = array())
		{
			static::jquery();

			if (!$module->inited())
			{
				$asset->addScript('phoenix/js/chosen/chosen.min.js');
				$asset->addStyle('phoenix/css/chosen/bootstrap-chosen.css');
			}

			if (!$module->stateInited())
			{
				$options = json_encode((object) $options);

				$js = <<<JS
// Chosen select
jQuery(document).ready(function($)
{
	$('{$selector}').chosen($options);
});
JS;

				$asset->internalScript($js);
			}
		});
	}
}
