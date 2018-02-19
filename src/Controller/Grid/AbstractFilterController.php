<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Grid;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Filter\InputFilter;
use Windwalker\Uri\Uri;

/**
 * The AbstractFilterController class.
 *
 * @since  1.0.5
 */
abstract class AbstractFilterController extends AbstractPhoenixController
{
    /**
     * Property inflection.
     *
     * @var  string
     */
    protected $inflection = self::PLURAL;

    /**
     * doExecute
     *
     * @return  mixed
     */
    protected function doExecute()
    {
        $model['list.search']    = $this->getUserStateFromInput($this->getContext('list.search'), 'search', [],
            InputFilter::ARRAY_TYPE);
        $model['list.filter']    = $this->getUserStateFromInput($this->getContext('list.filter'), 'filter', [],
            InputFilter::ARRAY_TYPE);
        $model['list.ordering']  = $this->getUserStateFromInput($this->getContext('list.ordering'), 'list_ordering');
        $model['list.direction'] = $this->getUserStateFromInput($this->getContext('list.direction'), 'list_direction');

        $uri = new Uri($this->app->uri->full);
        $uri->delVar('filter');

        $this->setRedirect($uri->toString());

        return true;
    }

    /**
     * Gets the value from session and input and sets it back to session
     *
     * @param string $name
     * @param string $inputName
     * @param mixed  $default
     * @param string $filter
     * @param string $namespace
     *
     * @return  mixed
     */
    public function getUserStateFromInput(
        $name,
        $inputName,
        $default = null,
        $filter = InputFilter::STRING,
        $namespace = 'default'
    ) {
        $oldState = $this->getUserState($name, $default, $namespace);
        $newState = $this->input->get($inputName, null, $filter);

        // If state different, reset page to 1.
        if ($oldState != $newState) {
            $this->setUserState($this->getContext('list.page'), 1);
        }

        return parent::getUserStateFromInput($name, $inputName, $default, $filter, $namespace);
    }

    /**
     * getContext
     *
     * @param   string $task
     *
     * @return  string
     */
    public function getContext($task = null)
    {
        $layout = $this->input->get('layout') ?: $this->app->get('route.extra.layout', $this->name);

        return parent::getContext($task) . '.' . $layout;
    }
}
