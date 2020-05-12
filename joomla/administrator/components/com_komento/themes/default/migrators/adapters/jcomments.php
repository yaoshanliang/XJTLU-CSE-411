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
defined('_JEXEC') or die('Restricted access');
?>
<div class="row">
	<div class="col-lg-6">
        <div class="panel">
			<div class="panel-head">
				<div class="panel-info"><?php echo JText::_('COM_KOMENTO_MIGRATORS_LAYOUT_MAIN'); ?></div>
			</div>

			<div class="panel-body">
	            <div class="form-group">
		            <label for="page_title" class="col-md-5">
		                <?php echo JText::_('COM_KOMENTO_MIGRATORS_SELECT_COMPONENTS'); ?>

		                <i data-html="true" data-placement="top" data-title="<?php echo JText::_('COM_KOMENTO_MIGRATORS_SELECT_COMPONENTS'); ?>"
		                    data-content="<?php echo JText::_('COM_KOMENTO_MIGRATORS_SELECT_COMPONENTS_DESC');?>" data-kt-provide="popover" class="fa fa-question-circle pull-right"></i>
		            </label>

		            <div class="col-md-7">
						<?php  echo $this->html('grid.selectList', 'components', 'all', KT::migrator()->getAdapter('jcomments')->getComponentSelection(), '', 'data-jcomments-components'); ?>
		            </div>
		        </div>

		        <div class="form-group">
		            <label for="page_title" class="col-md-5">
		                <?php echo JText::_('COM_KOMENTO_MIGRATORS_SELECT_PUBLISHING_STATE'); ?>

		                <i data-html="true" data-placement="top" data-title="<?php echo JText::_('COM_KOMENTO_MIGRATORS_SELECT_PUBLISHING_STATE'); ?>"
		                    data-content="<?php echo JText::_('COM_KOMENTO_MIGRATORS_SELECT_PUBLISHING_STATE_DESC');?>" data-kt-provide="popover" class="fa fa-question-circle pull-right"></i>
		            </label>

		            <div class="col-md-7">
						<?php  echo $this->html('grid.selectList', 'publishingState', 'inherit', $publishingState, '', 'data-migrate-comment-state'); ?>
		            </div>
		        </div>

		        <div class="form-group">
		        	<label class="col-md-5 control-label">
		        		<span ><?php echo JText::_('COM_KOMENTO_MIGRATORS_MIGRATE_LIKES'); ?></span>
		        	</label>
		        	<div class="col-md-7">
		        		<input id="migrateLikes" type="checkbox" checked="checked" data-migrate-comment-likes /></td>
		        	</div>
		        </div>

				<div style="padding-top:20px;">
					<a href="javascript:void(0);" class="btn btn-primary btn-sm" data-migrate-jcomments><?php echo JText::_('COM_KOMENTO_MIGRATORS_RUN_NOW'); ?></a>
				</div>
			</div>
        </div>
    </div>

    <div class="col-lg-6">
        <?php echo $this->output('admin/migrators/adapters/progress'); ?>
    </div>
</div>
<input type="hidden" name="layout" value="jcomments" />

