<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Model\ItemModel;
use Phoenix\View\ItemView;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Core\View\AbstractView;

/**
 * The GetController class.
 *
 * @method  ItemModel getModel($name = null, $source = null, $forceNew)
 * @method  ItemView  getView($name = null, $format = 'html', $engine = null, $forceNew = false)
 *
 * @since  1.0
 */
class ItemDisplayController extends DisplayController
{
    /**
     * Property inflection.
     *
     * @var  string
     */
    protected $inflection = self::SINGULAR;

    /**
     * Property keyName.
     *
     * @var  string
     */
    protected $keyName = 'id';

    /**
     * Prepare view and default model.
     *
     * You can configure default model state here, or add more sub models to view.
     * Remember to call parent to make sure default model already set in view.
     *
     * @param AbstractView    $view  The view to render page.
     * @param ModelRepository $model The default mode.
     *
     * @return  void
     */
    protected function prepareViewModel(AbstractView $view, ModelRepository $model)
    {
        parent::prepareViewModel($view, $model);

        $pk = $this->input->get($this->keyName);

        if ($pk) {
            $pk = [$this->keyName => $pk];
        }

        $model['load.conditions'] = $pk;
    }

    /**
     * prepareExecute
     *
     * @param ModelRepository $model
     *
     * @return void
     *
     * @deprecated Override prepareViewModel() instead.
     */
    protected function prepareModelState(ModelRepository $model)
    {

    }
}
