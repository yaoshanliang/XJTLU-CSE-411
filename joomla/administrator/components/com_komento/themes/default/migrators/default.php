<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form action="<?php echo JRoute::_('index.php');?>" method="post" name="adminForm" id="adminForm" data-migrate-article-form>

<?php if ($htmlContent) {?>
	<?php echo $htmlContent; ?>
<?php } else { ?>
<div class="row">
	<div class="col-lg-6">
		<?php foreach ($componentMigrators as $migrator) { ?>
			<?php if ($componentLib->isInstalled('com_' . $migrator)) { ?>
				<div class="panel">
					<div class="panel-head">
						<a href="index.php?option=com_komento&view=migrators&layout=<?php echo $migrator; ?>"><?php echo JText::_('COM_KOMENTO_MIGRATORS_' . strtoupper($migrator));?></a>
					</div>

					<div class="panel-body">
						<p><?php echo JText::_('COM_KOMENTO_MIGRATORS_' . strtoupper($migrator) . '_DESC');?></p>

						<br />
						<a href="index.php?option=com_komento&view=migrators&layout=<?php echo $migrator; ?>" class="btn btn-primary"><?php echo JText::_('COM_KOMENTO_VIEW_MIGRATOR');?></a>
					</div>
				</div>
			<?php } ?>
		<?php } ?> 

		<div class="panel">
			<div class="panel-head">
				<a href="index.php?option=com_komento&view=migrators&layout=custom"><?php echo JText::_('COM_KOMENTO_MIGRATORS_CUSTOM');?></a>
			</div>

			<div class="panel-body">
				<p><?php echo JText::_('COM_KOMENTO_MIGRATORS_CUSTOM_DESC');?></p>

				<br />
				<a href="index.php?option=com_komento&view=migrators&layout=custom" class="btn btn-primary"><?php echo JText::_('COM_KOMENTO_VIEW_MIGRATOR');?></a>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
<input type="hidden" name="option" value="com_komento" />
</form>
