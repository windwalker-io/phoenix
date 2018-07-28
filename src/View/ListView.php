<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Windwalker\Core\Pagination\Pagination;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;

/**
 * The ListHtmlView class.
 *
 * @since  1.0
 */
class ListView extends AbstractPhoenixHtmView
{
    /**
     * Property simplePagination.
     *
     * @var  boolean
     */
    protected $simplePagination = false;

    /**
     * Property pagination.
     *
     * @var  Pagination
     */
    protected $pagination;

    /**
     * Property fixPage.
     *
     * @var  bool
     */
    protected $fixPage = true;

    /**
     * setTitle
     *
     * @param string $title
     *
     * @return  static
     */
    public function setTitle($title = null)
    {
        $title = $title ?: __(
            'phoenix.title.list',
            __($this->langPrefix . $this->getName() . '.title')
        );

        return parent::setTitle($title);
    }

    /**
     * prepareData
     *
     * @param   Data $data
     *
     * @return  void
     */
    protected function prepareData($data)
    {
        parent::prepareData($data);

        $data->items = $data->items ?: $this->model->getItems();

        $this->preparePagination($data);

        $data->limit = $data->limit ?: $this->model->getLimit();
        $data->start = $data->start ?: $this->model->getStart();
        $data->page  = $data->page ?: $this->model->getPage();
    }

    /**
     * preparePagination
     *
     * @param DataInterface $data
     *
     * @return  void
     */
    protected function preparePagination(DataInterface $data)
    {
        if ($this->simplePagination) {
            $this->repository->getConfig()->set('list.fix_page', false);
            $data->total      = null;
            $data->pagination = $this->repository->getSimplePagination()
                ->template('phoenix.pagination.simple')
                ->setRouter($this->getRouter());
        } else {
            $this->repository->getConfig()->set('list.fix_page', $this->fixPage);
            $data->total      = $data->total ?: $this->repository->getTotal();
            $data->pagination = $this->repository->getPagination()
                ->template('phoenix.pagination.default')
                ->setRouter($this->getRouter());
        }
    }

    /**
     * getPagination
     *
     * @param int $page
     * @param int $total
     * @param int $limit
     * @param int $neighbours
     *
     * @return Pagination
     */
    public function getPagination($page = 1, $total = 0, $limit = 15, $neighbours = 4)
    {
        if ($this->pagination) {
            return $this->pagination;
        }

        return $this->pagination = new Pagination($page, $limit, $total, $neighbours);
    }
}
