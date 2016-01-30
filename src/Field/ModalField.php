<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Phoenix\Asset\Asset;
use Phoenix\Script\JQueryScript;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Data;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Form\Field\TextField;
use Windwalker\Ioc;

/**
 * The ModalField class.
 *
 * @since  1.0
 */
class ModalField extends TextField
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected $table;

	/**
	 * Property titleField.
	 *
	 * @var  string
	 */
	protected $titleField = 'title';

	/**
	 * Property pkName.
	 *
	 * @var  string
	 */
	protected $keyField = 'id';

	/**
	 * Property package.
	 *
	 * @var  string
	 */
	protected $package;

	/**
	 * Property view.
	 *
	 * @var  string
	 */
	protected $view;

	/**
	 * Property route.
	 *
	 * @var  string
	 */
	protected $route;

	/**
	 * Property query.
	 *
	 * @var  array
	 */
	protected $query = array();

	/**
	 * buildInput
	 *
	 * @param array $attrs
	 *
	 * @return  string
	 */
	public function buildInput($attrs)
	{
		$this->prepareScript();

		$this->package = $this->package ? : $this->get('package');
		$this->view = $this->view ? : $this->get('view');

		$attribs = $attrs;

		unset($attribs['name']);

		$attribs['value'] = $this->getTitle();
		$attribs['disabled'] = true;

		$input = parent::buildInput($attribs);

		$url = $this->get('url') ? : $this->getUrl();
		$id  = $this->getId();

		return WidgetHelper::render('phoenix.form.field.modal', array(
			'id'    => $id,
			'input' => $input,
			'url'   => $url,
			'attrs' => $attrs,
			'field' => $this
		), WidgetHelper::ENGINE_BLADE);
	}

	/**
	 * getTitle
	 *
	 * @return  Data
	 */
	protected function getTitle()
	{
		$table = $this->table ? : $this->get('table', $this->view);
		$value = $this->getValue();
		$keyField   = $this->get('key_field', $this->keyField);
		$titleField = $this->get('title_field', $this->titleField);

		$dataMapper = new DataMapper($table);

		$data = $dataMapper->findOne(array($keyField => $value));

		return $data->$titleField;
	}

	/**
	 * getUrl
	 *
	 * @return  string
	 */
	protected function getUrl()
	{
		$package = PackageHelper::getPackage($this->package);

		$package = $package ? : Ioc::get('current.package');

		$route = $this->get('route', $this->route) ? : $this->view;
		$query = $this->get('query', $this->query);

		return $package->router->html($route, array_merge(array(
			'layout'   => 'modal',
			'selector' => '#' . $this->getId() . '-wrap',
			'function' => 'Phoenix.Field.Modal.select'
		), $query));
	}

	/**
	 * prepareScript
	 *
	 * @return  void
	 */
	protected function prepareScript()
	{
		static $inited = false;

		if ($inited)
		{
			return;
		}

		JQueryScript::ui(array('effect'));

		$js = <<<JS
// Phoenix.Field.Modal
var Phoenix;
(function(Phoenix, $)
{
    (function()
    {
        Phoenix.Field.Modal = {
            select: function(selector, id, title)
            {
                var ele = $(selector);

                ele.find('.input-group input').attr('value', title).delay(250).effect('highlight');
                ele.find('input[data-value-store]').attr('value', id);

                $('#phoenix-iframe-modal').modal('hide');
            }
        };
    })(Phoenix.Field || (Phoenix.Field = {}));
})(Phoenix || (Phoenix = {}), jQuery);
JS;

		Asset::internalScript($js);

		$inited = true;
	}
}
