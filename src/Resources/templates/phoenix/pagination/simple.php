<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

use Windwalker\Core\Pagination\PaginationResult;

/**
 * @var PaginationResult $pagination
 * @var callable         $route
 */
?>
<div class="windwalker-pagination">
    <div class="btn-group pull-left">
        <?php if ($pagination->getFirst()): ?>
            <a href="<?php echo $this->escape($route(['page' => $pagination->getFirst()])); ?>"
                class="has-tooltip btn btn-default"
                title="<?php echo __('phoenix.pagination.first'); ?>">
                <span class="fa fa-fast-backward"></span>
                <span class="sr-only">
                    <?php echo __('phoenix.pagination.first'); ?>
                </span>
            </a>
        <?php endif; ?>

        <?php if ($pagination->getPrevious()): ?>
            <a href="<?php echo $this->escape($route(['page' => $pagination->getPrevious()])); ?>"
                class="has-tooltip btn btn-default"
                title="<?php echo __('phoenix.pagination.previous'); ?>">
                <span class="fa fa-backward"></span>
                <span class="sr-only">
                    <?php echo __('phoenix.pagination.previous'); ?>
                </span>
            </a>
        <?php endif; ?>
    </div>

    <div class="pull-right">
        <?php if ($pagination->getNext()): ?>
            <a href="<?php echo $this->escape($route(['page' => $pagination->getNext()])); ?>"
                class="has-tooltip btn btn-default"
                title="<?php echo __('phoenix.pagination.next'); ?>">
                <span class="fa fa-forward"></span>
                <span class="sr-only">
                    <?php echo __('phoenix.pagination.next'); ?>
                </span>
            </a>
        <?php endif; ?>
    </div>
</div>
