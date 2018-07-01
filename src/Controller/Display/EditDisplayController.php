<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Repository\AdminRepository;
use Phoenix\Repository\CrudRepository;
use Phoenix\View\EditView;
use Windwalker\Core\Repository\Repository;
use Windwalker\Core\View\AbstractView;

/**
 * The EditGetController class.
 *
 * @method  AdminRepository|CrudRepository  getModel($name = null, $source = null, $forceNew = false)
 * @method  EditView                        getView($name = null, $format = 'html', $engine = null, $forceNew = false)
 *
 * @since  1.0.5
 */
class EditDisplayController extends ItemDisplayController
{
    /**
     * A hook before main process executing.
     *
     * @return  void
     * @throws \Exception
     */
    protected function prepareExecute()
    {
        parent::prepareExecute();
    }

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

        if ($this->input->get('new') !== null) {
            $this->removeUserState($this->getContext('edit.data'));
        }

        $repository['form.data'] = $this->getUserState($this->getContext('edit.data'));

        $this->removeUserState($this->getContext('edit.data'));
    }
}
