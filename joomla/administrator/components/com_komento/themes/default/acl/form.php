<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" autocomplete="off">

	<div class="wrapper accordion">
		<div class="tab-box tab-box-alt">
			<div class="tabbable">
				<ul class="nav nav-tabs nav-tabs-icons">
					<?php foreach ($tabs as $tab) { ?>
					<li class="<?php echo $current == $tab->id ? 'active' : '';?>">
						<a href="#tab-<?php echo $tab->id;?>" data-bs-toggle="tab" data-kt-tab="<?php echo $tab->id;?>"><?php echo $tab->title;?></a>
					</li>
					<?php } ?>
				</ul>

				<div class="tab-content">
					<?php $i = 0; ?>
					<?php foreach ($tabs as $tab) { ?>
					<div class="tab-pane <?php echo $current == $tab->id ? 'active' : '';?>" id="tab-<?php echo $tab->id;?>">
						<div class="row">
							<div class="col-lg-12">
								<div class="panel">
									<?php echo $this->html('panel.heading', 'COM_KOMENTO_ACL_SECTION_' . strtoupper($tab->id)); ?>

									<div class="panel-body">
										<?php foreach ($rulesets->{$tab->id} as $rule => $value) { ?>
										<div class="form-group" data-acl-wrap>
											<?php echo $this->html('panel.label', 'COM_KOMENTO_ACL_RULE_' . strtoupper($rule), false, '', 3); ?>

											<div class="col-md-1 t-text--center">
												<?php echo $this->html('form.toggler', $rule, $value, '', 'data-acl-toggle'); ?>
											</div>

											<div class="col-md-8">
												<span class="t-fs--sm t-text--success <?php echo $value ? '' : 't-hidden';?>" data-info data-on><?php echo JText::_('COM_KOMENTO_ACL_RULE_' . strtoupper($rule) . '_ON');?></span>
												<span class="t-fs--sm t-text--danger <?php echo !$value ? '' : 't-hidden';?>" data-info data-off><?php echo JText::_('COM_KOMENTO_ACL_RULE_' . strtoupper($rule) . '_OFF');?></span>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php $i++;?>
					<?php } ?>					
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="target_id" value="<?php echo $this->escape($id); ?>" />
	<input type="hidden" name="target_type" value="<?php echo $this->escape($type); ?>" />
	<input type="hidden" name="id" value="<?php echo $this->escape($id); ?>" />
	<input type="hidden" name="option" value="com_komento">
	<input type="hidden" name="controller" value="acl">
	<input type="hidden" name="task" value="" data-table-grid-task>
	<input type="hidden" name="current" value="<?php echo $this->escape($current);?>" data-current />
	<?php echo JHTML::_('form.token'); ?>
</form>