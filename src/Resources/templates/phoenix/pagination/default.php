<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

use Windwalker\Core\Language\Translator;
use Windwalker\Core\Pagination\PaginationResult;

/**
 * @var PaginationResult $pagination
 * @var callable         $route
 */
?>
<ul class="pagination windwalker-pagination">
    <?php if ($pagination->getFirst()): ?>
        <li class="page-item">
            <a href="<?php echo $this->escape($route(['page' => $pagination->getFirst()])); ?>"
               class="has-tooltip page-link" title="<?php echo Translator::translate('phoenix.pagination.first'); ?>">
                <span class="fa fa-fast-backward"></span>
                <span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.first'); ?>
				</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ($pagination->getPrevious()): ?>
        <li class="page-item">
            <a href="<?php echo $this->escape($route(['page' => $pagination->getPrevious()])); ?>"
               class="has-tooltip page-link" title="<?php echo Translator::translate('phoenix.pagination.previous'); ?>">
                <span class="fa fa-backward"></span>
                <span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.previous'); ?>
				</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ($pagination->getLess()): ?>
        <li class="page-item">
            <a class="page-link" href="<?php echo $this->escape($route(['page' => $pagination->getLess()])); ?>">
                <span class="fa fa-chevron-left"></span>
                <?php echo Translator::translate('phoenix.pagination.less'); ?>
            </a>
        </li>
    <?php endif; ?>

    <?php foreach ($pagination->getPages() as $k => $page): ?>
        <?php $active = ($page == 'current') ? 'active' : ''; ?>
        <li class="page-item <?php echo $active; ?>">
            <?php if (!$active): ?>
                <a class="page-link" href="<?php echo $this->escape($route(['page' => $k])); ?>">
                    <?php echo $k; ?>
                </a>
            <?php else: ?>
                <a class="page-link" href="javascript:void(0);">
                    <?php echo $k; ?>
                </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>

    <?php if ($pagination->getMore()): ?>
        <li class="page-item">
            <a class="page-link" href="<?php echo $this->escape($route(['page' => $pagination->getMore()])); ?>">
                <?php echo Translator::translate('phoenix.pagination.more'); ?>
                <span class="fa fa-chevron-right"></span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ($pagination->getNext()): ?>
        <li class="page-item">
            <a href="<?php echo $this->escape($route(['page' => $pagination->getNext()])); ?>"
               class="has-tooltip page-link" title="<?php echo Translator::translate('phoenix.pagination.next'); ?>">
                <span class="fa fa-forward"></span>
                <span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.next'); ?>
				</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ($pagination->getLast()): ?>
        <li class="page-item">
            <a href="<?php echo $this->escape($route(['page' => $pagination->getLast()])); ?>"
               class="has-tooltip page-link" title="<?php echo Translator::translate('phoenix.pagination.last'); ?>">
                <span class="fa fa-fast-forward"></span>
                <span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.last'); ?>
				</span>
            </a>
        </li>
    <?php endif; ?>
</ul>
