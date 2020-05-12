<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-md-6">
			<div class="panel">
				<?php echo $this->html('panel.heading', 'COM_KOMENTO_SUBSCRIBER_DETAILS'); ?>

				<div class="panel-body">
					<div class="form-group">
						<?php echo $this->html('panel.label', 'COM_KOMENTO_SUBSCRIBER_EXTENSION'); ?>

						<div class="col-md-7">
							<?php echo $this->html('form.extensions', 'component', $subscriber->component, 'data-kt-extension'); ?>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->html('panel.label', 'COM_KOMENTO_SUBSCRIBER_EXTENSION_ITEM_ID'); ?>

						<div class="col-md-7">
							<?php echo $this->html('grid.inputbox', 'cid', $subscriber->cid, 'cid', array('class' => 'input-mini text-center')); ?>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->html('panel.label', 'COM_KOMENTO_SUBSCRIBER_NAME'); ?>

						<div class="col-md-7">
							<?php echo $this->html('grid.inputbox', 'fullname', $subscriber->fullname); ?>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->html('panel.label', 'COM_KOMENTO_SUBSCRIBER_EMAIL'); ?>

						<div class="col-md-7">
							<?php echo $this->html('grid.inputbox', 'email', $subscriber->email); ?>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->html('panel.label', 'COM_KOMENTO_PUBLISHED'); ?>

						<div class="col-md-7">
							<?php echo $this->html('form.toggler', 'published', $subscriber->published); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="id" value="<?php echo $subscriber->id;?>" />
	<?php echo $this->html('form.action', 'subscribers.save'); ?>
</form>