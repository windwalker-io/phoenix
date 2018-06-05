<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Repository\AdminRepository;
use Phoenix\Repository\AdminRepositoryInterface;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Security\Exception\UnauthorizedException;
use Windwalker\Data\Data;

/**
 * The ReorderController class.
 *
 * @since  1.0.5
 */
abstract class AbstractReorderController extends AbstractBatchController
{
    /**
     * Property action.
     *
     * @var  string
     */
    protected $action = 'reorder';

    /**
     * Property model.
     *
     * @var  AdminRepository
     */
    protected $repository;

    /**
     * Property orderField.
     *
     * @var  string
     */
    protected $orderField = 'ordering';

    /**
     * Property delta.
     *
     * @var int
     */
    protected $delta;

    /**
     * Property origin.
     *
     * @var  array
     */
    protected $origin = [];

    /**
     * prepareExecute
     *
     * @return  void
     */
    protected function prepareExecute()
    {
        parent::prepareExecute();

        $this->data   = $this->input->getArray('ordering', []);
        $this->origin = explode(',', $this->input->post->getString('origin_ordering'));
        $this->delta  = $this->input->post->get('delta');

        // Determine model
        if (!$this->repository instanceof AdminRepositoryInterface) {
            throw new \UnexpectedValueException(sprintf('%s model need extend to AdminModel', $this->getName()));
        }
    }

    /**
     * doExecute
     *
     * @return mixed
     * @throws \Exception
     */
    protected function doExecute()
    {
        if (!$this->checkAccess($this->data)) {
            throw new UnauthorizedException('You have no access to modify these items.');
        }

        $this->repository['order.column'] = $this->orderField;

        // Move item
        if ($this->delta) {
            $this->repository->move($this->pks, $this->delta);
        } // Save order list
        else {
            // Do not order if no change.
            if (array_values($this->data) === array_values($this->origin)) {
                return true;
            }

            // Order subset list.
            if ($this->pks) {
                $pks = array_flip($this->pks);

                $order = array_intersect_key($this->data, $pks);
            } // Order whole page
            else {
                $order = $this->data;
            }

            $this->repository->reorder((array) $order);
        }

        return true;
    }

    /**
     * getSuccessMessage
     *
     * @param Data $data
     *
     * @return  string
     */
    public function getSuccessMessage($data = null)
    {
        return Translator::translate($this->langPrefix . 'message.batch.reorder.success');
    }
}
