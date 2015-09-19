<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

use Windwalker\Core\Language\Translator;
use Windwalker\Core\Pagination\PaginationResult;
use Windwalker\Data\Data;

/**
 * @var Data             $data
 * @var PaginationResult $pagination
 * @var string           $route
 */
$pagination = $data->pagination;
$route = $data->route;
?>
<ul class="pagination windwalker-pagination">
	<?php if ($pagination->getFirst()): ?>
		<li>
			<a href="<?php echo $route(array('page' => $pagination->getFirst())); ?>"
				class="hasTooltip" title="<?php echo Translator::translate('phoenix.pagination.first'); ?>">
				<span class="glyphicon glyphicon-fast-backward"></span>
				<span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.first'); ?>
				</span>
			</a>
		</li>
	<?php endif; ?>

	<?php if ($pagination->getPrevious()): ?>
		<li>
			<a href="<?php echo $route(array('page' => $pagination->getPrevious())); ?>"
				class="hasTooltip" title="<?php echo Translator::translate('phoenix.pagination.previous'); ?>">
				<span class="glyphicon glyphicon-backward"></span>
				<span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.previous'); ?>
				</span>
			</a>
		</li>
	<?php endif; ?>

	<?php if ($pagination->getLess()): ?>
		<li>
			<a href="<?php echo $route(array('page' => $pagination->getLess())); ?>">
				<span class="glyphicon glyphicon-menu-left"></span>
				<?php echo Translator::translate('phoenix.pagination.less'); ?>
			</a>
		</li>
	<?php endif; ?>

	<?php foreach ($pagination->getPages() as $k => $page): ?>
		<?php $active = ($page == 'current') ? 'active' : ''; ?>
		<li class="<?php echo $active; ?>">
			<?php if (!$active): ?>
				<a href="<?php echo $route(array('page' => $k)); ?>">
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
			<a href="<?php echo $route(array('page' => $pagination->getMore())); ?>">
				<?php echo Translator::translate('phoenix.pagination.more'); ?>
				<span class="glyphicon glyphicon-menu-right"></span>
			</a>
		</li>
	<?php endif; ?>

	<?php if ($pagination->getNext()): ?>
		<li>
			<a href="<?php echo $route(array('page' => $pagination->getNext())); ?>"
				class="hasTooltip" title="<?php echo Translator::translate('phoenix.pagination.next'); ?>">
				<span class="glyphicon glyphicon-forward"></span>
				<span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.next'); ?>
				</span>
			</a>
		</li>
	<?php endif; ?>

	<?php if ($pagination->getLast()): ?>
		<li>
			<a href="<?php echo $route(array('page' => $pagination->getLast())); ?>"
				class="hasTooltip" title="<?php echo Translator::translate('phoenix.pagination.last'); ?>">
				<span class="glyphicon glyphicon-fast-forward"></span>
				<span class="sr-only">
					<?php echo Translator::translate('phoenix.pagination.last'); ?>
				</span>
			</a>
		</li>
	<?php endif; ?>
</ul>
