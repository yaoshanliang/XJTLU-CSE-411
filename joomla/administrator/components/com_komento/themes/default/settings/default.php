<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<div class="wrapper accordion">
		<div class="tab-box tab-box-alt">
			<div class="tabbable">
				<ul class="nav nav-tabs nav-tabs-icons">
					<?php foreach ($tabs as $tab) { ?>
					<li class="<?php echo $tab->active ? 'active' : '';?>">
						<a href="#tab-<?php echo $tab->id;?>" data-bs-toggle="tab"><?php echo $tab->title;?></a>
					</li>
					<?php } ?>
				</ul>

				<div class="tab-content">
					<?php foreach ($tabs as $tab) { ?>
					<div class="tab-pane <?php echo $tab->active ? 'active' : '';?>" id="tab-<?php echo $tab->id;?>">
						<?php echo $this->output($tab->namespace, array('tab' => $tab)); ?>
					</div>
					<?php } ?>
				</div>

			</div>
		</div>
	</div>

	<input type="hidden" name="current" value="<?php echo $layout;?>" />
	<input type="hidden" name="active" id="active" value="<?php echo $active; ?>" data-active-layout />
	<input type="hidden" name="component" value="com_komento" />
	<?php echo $this->html('form.action', 'settings.save'); ?>
</form>