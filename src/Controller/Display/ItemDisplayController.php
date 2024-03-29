<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Repository\ItemRepository;
use Phoenix\View\ItemView;
use Windwalker\Core\Repository\Repository;
use Windwalker\Core\View\AbstractView;

/**
 * The GetController class.
 *
 * @method  ItemRepository getModel($name = null, $source = null, $forceNew)
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
     * @param AbstractView $view       The view to render page.
     * @param Repository   $repository The default mode.
     *
     * @return  void
     * @throws \ReflectionException
     */
    protected function prepareViewRepository(AbstractView $view, Repository $repository)
    {
        parent::prepareViewRepository($view, $repository);

        $pk = $this->input->get($this->keyName);

        if ($pk) {
            $pk = [$this->keyName => $pk];
        }

        $repository['load.conditions'] = $pk;
    }

    /**
     * prepareExecute
     *
     * @param Repository $model
     *
     * @return void
     *
     * @deprecated Override prepareViewModel() instead.
     */
    protected function prepareModelState(Repository $model)
    {
    }
}
