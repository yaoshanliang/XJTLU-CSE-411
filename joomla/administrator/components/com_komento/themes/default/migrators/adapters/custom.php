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
defined('_JEXEC') or die('Restricted access');
?>
<div class="row">
	<div class="col-lg-6">
        <div class="panel">
			<div class="panel-head">
				<div class="panel-info"><?php echo JText::_('COM_KOMENTO_MIGRATORS_LAYOUT_MAIN'); ?></div>
			</div>

			<div class="panel-body" data-migrator-custom-data>
	            <div class="form-group">
	            	<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_TABLE'); ?>
					<div class="col-md-7">
						<?php  echo $this->html('grid.selectList', 'table', '', $tableSelection, '', 'data-custom-table'); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_COMPONENT'); ?>
					<div class="col-md-7">
						<?php  echo $this->html('grid.selectList', 'component', '', $componentSelection, '', 'data-custom-component-filter'); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_POST_ID'); ?>
					<div class="col-md-7">
						<select id="migrate-column-contentid" class="o-form-control" data-table-columns data-required="true"></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_COMMENT'); ?>
					<div class="col-md-7">
						<select id="migrate-column-comment" class="o-form-control" data-table-columns></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_DATE'); ?>
					<div class="col-md-7">
						<select id="migrate-column-date" class="o-form-control" data-table-columns></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_AUTHOR_ID'); ?>
					<div class="col-md-7">
						<select id="migrate-column-authorid" class="o-form-control" data-table-columns></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_NAME'); ?>
					<div class="col-md-7">
						<select id="migrate-column-name" class="o-form-control" data-table-columns></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_EMAIL'); ?>
					<div class="col-md-7">
						<select id="migrate-column-email" class="o-form-control" data-table-columns></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_WEBSITE'); ?>
					<div class="col-md-7">
						<select id="migrate-column-homepage" class="o-form-control" data-table-columns></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_PUBLISH_STATE'); ?>
					<div class="col-md-7">
						<select id="migrate-column-published" class="o-form-control" data-table-columns></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_IP'); ?>
					<div class="col-md-7">
						<select id="migrate-column-ip" class="o-form-control" data-table-columns></select>
					</div>
				</div>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_MIGRATORS_CUSTOM_MIGRATE_CYCLE'); ?>
					<div class="col-md-7">
						<input id="migrate-cycle" type="input" class="o-form-control input-mini text-center" value="10" data-migrate-cycle />
					</div>
				</div>

				<div style="padding-top:20px;">
					<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-migrate-custom><?php echo JText::_('COM_KOMENTO_MIGRATORS_RUN_NOW'); ?></a>
				</div>
			</div>
        </div>
    </div>

    <div class="col-lg-6">
        <?php echo $this->output('admin/migrators/adapters/progress'); ?>
    </div>
</div>
<input type="hidden" name="layout" value="custom" />