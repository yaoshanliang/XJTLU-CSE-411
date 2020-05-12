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
<form action="index.php" method="post" name="adminForm" id="adminForm" data-kt-table>
	<div class="panel-table">
		<table class="app-table app-table-middle table table-striped">
			<thead>
				<tr>
					<td width="1%" class="center">
                    &nbsp;
               		</td>	
					<td style="text-align:left;">
						<?php echo JText::_('COM_KOMENTO_THEMES_TITLE'); ?>
					</td>
					<td width="5%" class="center">
						<?php echo JText::_('COM_KOMENTO_THEMES_DEFAULT'); ?>
					</td>
					<td width="15%" class="center">
						<?php echo JText::_('COM_KOMENTO_THEMES_AUTHOR'); ?>
					</td>
					
				</tr>
			</thead>
			<tbody>
				
				<?php $i = 0; ?>
				<?php foreach ($themes as $theme) { ?>
				<tr>
					<td class="center">
					    <input type="radio" name="cid[]" value="<?php echo $theme->element;?>" data-theme-item data-table-grid-id />
					</td>
					
					<td>
	                    <?php echo JText::_($theme->name);?>
	                </td>

					<td class="center">
						<?php echo $this->html('grid.featured', $theme, 'themes', 'default', 'setDefault'); ?>
					</td>

					<td class="center">
						<?php echo $theme->author; ?>
					</td>	
				</tr>
				<?php $i++; ?>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="task" value="" data-kt-table-task />
	<input type="hidden" name="option" value="com_komento" />
	<input type="hidden" name="controller" value="themes" />
	<?php echo JHTML::_('form.token'); ?>
	
</form>