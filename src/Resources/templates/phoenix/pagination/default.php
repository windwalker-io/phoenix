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
		<li>
			<a href="<?php echo $this->escape($route(['page'=> $pagination->getFirst()])); ?>"
				class="hasTooltip" title="<?php echo Translator::translate('phoenix.pagination.first'); ?>">
				<span class="glyphicon glyphicon-fast-backward fa fa-fast-backward"></span>
				<span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.first'); ?>
				</span>
			</a>
		</li>
	<?php endif; ?>

	<?php if ($pagination->getPrevious()): ?>
		<li>
			<a href="<?php echo $this->escape($route(['page'=> $pagination->getPrevious()])); ?>"
				class="hasTooltip" title="<?php echo Translator::translate('phoenix.pagination.previous'); ?>">
				<span class="glyphicon glyphicon-backward fa fa-backward"></span>
				<span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.previous'); ?>
				</span>
			</a>
		</li>
	<?php endif; ?>

	<?php if ($pagination->getLess()): ?>
		<li>
			<a href="<?php echo $this->escape($route(['page'=> $pagination->getLess()])); ?>">
				<span class="glyphicon glyphicon-menu-left fa fa-chevron-left"></span>
				<?php echo Translator::translate('phoenix.pagination.less'); ?>
			</a>
		</li>
	<?php endif; ?>

	<?php foreach ($pagination->getPages() as $k => $page): ?>
		<?php $active = ($page == 'current') ? 'active' : ''; ?>
		<li class="<?php echo $active; ?>">
			<?php if (!$active): ?>
				<a href="<?php echo $this->escape($route(['page'=> $k])); ?>">
					<?php echo $k; ?>
				</a>
			<?php else: ?>
				<a href="javascript:void(0);">
					<?php echo $k; ?>
				</a>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>

	<?php if ($pagination->getMore()): ?>
		<li>
			<a href="<?php echo $this->escape($route(['page'=> $pagination->getMore()])); ?>">
				<?php echo Translator::translate('phoenix.pagination.more'); ?>
				<span class="glyphicon glyphicon-menu-right fa fa-chevron-right"></span>
			</a>
		</li>
	<?php endif; ?>

	<?php if ($pagination->getNext()): ?>
		<li>
			<a href="<?php echo $this->escape($route(['page'=> $pagination->getNext()])); ?>"
				class="hasTooltip" title="<?php echo Translator::translate('phoenix.pagination.next'); ?>">
				<span class="glyphicon glyphicon-forward fa fa-forward"></span>
				<span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.next'); ?>
				</span>
			</a>
		</li>
	<?php endif; ?>

	<?php if ($pagination->getLast()): ?>
		<li>
			<a href="<?php echo $this->escape($route(['page'=> $pagination->getLast()])); ?>"
				class="hasTooltip" title="<?php echo Translator::translate('phoenix.pagination.last'); ?>">
				<span class="glyphicon glyphicon-fast-forward fa fa-fast-forward"></span>
				<span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.last'); ?>
				</span>
			</a>
		</li>
	<?php endif; ?>
</ul>
