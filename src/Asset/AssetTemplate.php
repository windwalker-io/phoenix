<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Asset;

use Windwalker\String\StringHelper;

/**
 * The AssetTemplate class.
 *
 * @since  {DEPLOY_VERSION}
 */
class AssetTemplate
{
	/**
	 * Property templates.
	 *
	 * @var  array
	 */
	protected $templates = array();

	/**
	 * Property currentName.
	 *
	 * @var  string
	 */
	protected $currentName;

	/**
	 * addTemplate
	 *
	 * @param string $name
	 * @param string $string
	 * @param array  $data
	 *
	 * @return  static
	 */
	public function addTemplate($name, $string, $data = array())
	{
		$this->templates[$name] = StringHelper::parseVariable($string, $data);

		return $this;
	}

	/**
	 * removeTemplate
	 *
	 * @param   string  $name
	 *
	 * @return  static
	 */
	public function removeTemplate($name)
	{
		if (isset($this->templates[$name]))
		{
			unset($this->templates[$name]);
		}

		return $this;
	}

	/**
	 * resetTemplates
	 *
	 * @return  static
	 */
	public function resetTemplates()
	{
		$this->templates = array();

		return $this;
	}

	/**
	 * renderTemplate
	 *
	 * @return  string
	 */
	public function renderTemplates()
	{
		$html = '';

		if (WINDWALKER_DEBUG)
		{
			$html .= "\n\n<!-- Start Asset Template -->\n\n";
		}

		foreach ($this->templates as $name => $template)
		{
			if (WINDWALKER_DEBUG)
			{
				$html .= sprintf("\n<!-- $name -->\n");
			}

			$html .= $template;
		}

		return $html;
	}

	/**
	 * startTemplate
	 *
	 * @param string $__assetTemplateName
	 * @param array  $__assetTemplateData
	 *
	 * @return  $this
	 */
	public function startTemplate($__assetTemplateName, $__assetTemplateData = array())
	{
		if ($this->currentName)
		{
			throw new \LogicException('Do not support nested template for: ' . $__assetTemplateName . '. current template is: ' . $this->currentName);
		}

		$this->currentName = $__assetTemplateName;

		extract((array) $__assetTemplateData);

		ob_start();

		return $this;
	}

	/**
	 * endTemplate
	 *
	 * @return  static
	 */
	public function endTemplate()
	{
		$content = ob_get_contents();

		ob_end_clean();

		$this->addTemplate($this->currentName, $content);

		$this->currentName = null;

		return $this;
	}
}
