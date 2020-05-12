<div id="komento-dashboard">
	<ul id="komento-dashboard-menu" class="navigation-menu">
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_komento&view=dashboard'); ?>">
				<?php echo JText::_('COM_KOMENTO_MENU_COMMENTS'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_komento&view=reports'); ?>">
				<?php echo JText::_('COM_KOMENTO_MENU_FLAGS'); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_komento&view=pending') ; ?>">
				<?php echo JText::_('COM_KOMENTO_MENU_PENDING'); ?>
			</a>
		</li>
	</ul>

	<form id="dashboard-comments-filter" action="index.php?option=com_komento&view=dashboard" method="post">
	<?php
		$status[] = JHTML::_( 'select.option', 'all', JText::_( 'COM_KOMENTO_ALL_STATUS' ) );
		$status[] = JHTML::_( 'select.option', '1', JText::_( 'COM_KOMENTO_PUBLISHED' ) );
		$status[] = JHTML::_( 'select.option', '0', JText::_( 'COM_KOMENTO_UNPUBLISHED' ) );
		$status[] = JHTML::_( 'select.option', '2', JText::_( 'COM_KOMENTO_MODERATE' ) );

		$model = KT::getModel( 'comments' );
		$allComponents = $model->getUniqueComponents();

		$component[] = JHTML::_( 'select.option', 'all', JText::_( 'COM_KOMENTO_ALL_COMPONENTS' ) );

		foreach( $allComponents as $row )
		{
			$component[] = JHTML::_( 'select.option', $row, $row );
		}

		$flag[] = JHTML::_( 'select.option', 'all', JText::_( 'COM_KOMENTO_ALL_FLAGS' ) );
		$flag[] = JHTML::_( 'select.option', '0', JText::_( 'COM_KOMENTO_NOFLAG' ) );
		$flag[] = JHTML::_( 'select.option', '1', JText::_( 'COM_KOMENTO_SPAM' ) );
		$flag[] = JHTML::_( 'select.option', '2', JText::_( 'COM_KOMENTO_OFFENSIVE' ) );
		$flag[] = JHTML::_( 'select.option', '3', JText::_( 'COM_KOMENTO_OFFTOPIC' ) );

		$view[] = JHTML::_( 'select.option', 'latest', JText::_( 'COM_KOMENTO_SORT_LATEST' ) );
		$view[] = JHTML::_( 'select.option', 'oldest', JText::_( 'COM_KOMENTO_SORT_OLDEST' ) );

		echo JHTML::_( 'select.genericlist', $component, 'filter-component', 'class="inputbox" size="1"', 'value', 'text', $filter['component'] );

		echo JHTML::_( 'select.genericlist', $flag, 'filter-flag', 'class="inputbox" size="1"', 'value', 'text', $filter['flag'] );

		echo JHTML::_( 'select.genericlist', $status, 'filter-status', 'class="inputbox" size="1"', 'value', 'text', $filter['status'] );

		echo JHTML::_( 'select.genericlist', $view, 'filter-sort', 'class="inputbox" size="1"', 'value', 'text', $filter['sort'] );
	?>
	<br />
	<label><?php echo JText::_( 'COM_KOMENTO_COMMENTS_SEARCH' ); ?> :</label>
	<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($filter['search']); ?>" class="inputbox" />
	<button onclick="submitform()">Submit</button>
	<button onclick="resetform()">Reset</button>
	</form>


	<?php if ($comments) { ?>
	<ul id="dashboard-comments" class="kmt-list">
		<?php foreach ($comments as $comment) { ?>
			<?php echo $this->output('site/dashboard/comments', array('row', $comment)); ?>
		<?php } ?>
	</ul>
	<?php } else { ?>
	<div class="is-empty">
		<div class="empty">
			<?php echo JText::_('COM_KOMENTO_COMMENTS_NO_COMMENT'); ?>
		</div>
	</div>
	<?php } ?>

	<?php if ($comments && isset($pagination)) { ?>
		<div class="pagination"><?php echo $pagination->getPagesLinks();?></div>
	<?php } ?>

</div>
