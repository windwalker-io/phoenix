<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Repository\ListRepository;
use Phoenix\Repository\ListRepositoryInterface;
use Phoenix\View\GridView;
use Phoenix\View\ListView;
use Windwalker\Core\Repository\Repository;
use Windwalker\Filter\InputFilter;
use Windwalker\Form\Field\ListField;

/**
 * The ListGetController class.
 *
 * @method  ListRepository     getRepository($name = null, $source = null, $forceNew = false)
 * @method  ListView|GridView  getView($name = null, $format = 'html', $engine = null, $forceNew = false)
 *
 * @since  1.0
 */
class ListDisplayController extends DisplayController
{
    /**
     * Property inflection.
     *
     * @var  string
     */
    protected $inflection = self::PLURAL;

    /**
     * Property model.
     *
     * @var  ListRepository
     */
    protected $repository;

    /**
     * The list limit per page..
     *
     * Use 0 to set unlimited.
     *
     * @var integer
     */
    protected $limit;

    /**
     * Property ordering.
     *
     * @var  string
     */
    protected $defaultOrdering = null;

    /**
     * Property direction.
     *
     * @var  string
     */
    protected $defaultDirection = null;

    /**
     * Property fuzzingSearching.
     *
     * @var  bool
     */
    protected $fuzzingSearching = true;

    /**
     * prepareUserState
     *
     * @param   Repository|ListRepositoryInterface $model
     *
     * @return  void
     *
     * @deprecated Override prepareViewModel() instead.
     */
    protected function prepareModelState(Repository $model)
    {
        // Filter & Search
        $model['input.search'] = $this->getUserStateFromInput($this->getContext('list.search'), 'search', [],
            InputFilter::ARRAY_TYPE);
        $model['input.filter'] = $this->getUserStateFromInput($this->getContext('list.filter'), 'filter', [],
            InputFilter::ARRAY_TYPE);

        foreach ((array) $this->handleSearches($model['input.search']) as $key => $value) {
            $model->addSearch($key, $value);
        }

        foreach ((array) $model['input.filter'] as $key => $value) {
            $model->addFilter($key, $value);
        }

        // Fuzzing searching
        $model->fuzzySearching($this->fuzzingSearching);

        // Ordering
        $model->ordering(
            $this->getUserStateFromInput($this->getContext('list.ordering'), 'list_ordering', $this->defaultOrdering),
            $this->getUserStateFromInput($this->getContext('list.direction'), 'list_direction', $this->defaultDirection)
        );

        // Pagination
        $model->limit($this->limit !== null ? $this->limit : $this->app->get('list.limit', 15));

        $model->page($this->getUserStateFromInput($this->getContext('list.page'), 'page', 1, InputFilter::INTEGER));
    }

    /**
     * handleSearches
     *
     * @param   array $search
     *
     * @return  array
     */
    protected function handleSearches($search)
    {
        if (!isset($search['field']) || !isset($search['content'])) {
            return [];
        }

        if ($search['field'] === '*' && isset($search['content'])) {
            // Get search fields
            $form = $this->repository->getForm('grid');

            $searchField = $form->getField('field', 'search');

            if (!$searchField) {
                return [];
            }

            /** @var ListField $searchField */
            $options = $searchField->getOptions();

            $fields = [];

            foreach ($options as $option) {
                $fields[$option->getValue()] = $search['content'];
            }

            return $fields;
        }

        return [$search['field'] => $search['content']];
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
        return parent::getContext($task) . '.' . $this->layout;
    }
}
