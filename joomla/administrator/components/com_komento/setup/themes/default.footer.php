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
<?php if ($active != 'complete') { ?>
<script type="text/javascript">
$(document).ready(function(){

	var previous = $('[data-installation-nav-prev]'),
		active = $('[data-installation-form-nav-active]'),
		nav = $('[data-installation-form-nav]'),
		retry = $('[data-installation-retry]'),
		cancel = $('[data-installation-nav-cancel]'),
		loading = $('[data-installation-loading]');

	previous.on('click', function() {
		active.val(<?php echo $active;?> - 2);

		nav.submit();
	});

	cancel.on('click', function() {
		window.location = '<?php echo JURI::base();?>/index.php?option=com_komento&cancelSetup=1';
	});

	retry.on('click', function() {
		var step = $(this).data('retry-step');

		$(this).addClass('hide');

		loading.removeClass('hide');

		window['kt']['installation'][step]();
	});
});
</script>

<form action="index.php?option=com_komento" method="post" data-installation-form-nav class="hidden">
	<input type="hidden" name="active" value="" data-installation-form-nav-active />
	<input type="hidden" name="option" value="com_komento" />
	<?php if($reinstall){ ?>
	<input type="hidden" name="reinstall" value="1" />
	<?php } ?>

	<?php if($update){ ?>
	<input type="hidden" name="update" value="1" />
	<?php } ?>
</form>


<div class="container">
	<div class="navi row-table">
		<a href="javascript:void(0);" class="col-cell" <?php echo $active > 1 ? ' data-installation-nav-prev' : ' data-installation-nav-cancel';?>>
			<b>
				<span>
					<?php if ($active > 1) { ?>
						<?php echo JText::_('COM_KOMENTO_INSTALLATION_PREVIOUS'); ?>
					<?php } else { ?>
						<?php echo JText::_('COM_KOMENTO_INSTALLATION_EXIT'); ?>
					<?php } ?>
				</span>
			</b>
		</a>

		<a href="javascript:void(0);" class="col-cell primary" data-installation-submit>
			<b>
				<span><?php echo JText::_('COM_KOMENTO_INSTALLATION_NEXT_STEP'); ?></span>
			</b>
		</a>

		<a href="javascript:void(0);" class="col-cell loading hide disabled" data-installation-loading>
			<b>
				<span><?php echo JText::_('COM_KOMENTO_INSTALLATION_LOADING'); ?></span>
				<span>
					<b class="ui loader"></b>
				</span>
			</b>
		</a>

		<a href="javascript:void(0);" class="col-cell primary hide" data-installation-install-addons>
			<b>
				<span><?php echo JText::_('COM_KOMENTO_INSTALLATION_INSTALL_ADDONS'); ?></span>
				<span>
					<i class="ion-android-arrow-forward text-left"></i>
				</span>
			</b>
		</a>

		<a href="javascript:void(0);" class="col-cell primary hide" data-installation-retry>
			<b>
				<span><?php echo JText::_('COM_KOMENTO_INSTALLATION_RETRY'); ?></span>
			</b>
		</a>
	</div>
</div>
<?php } ?>

<?php if ($active == 'complete') { ?>
<div class="container">
	<div class="navi row-table">
		<a class="col-cell primary" href="<?php echo JURI::root();?>administrator/index.php?option=com_komento">
			<b><span><?php echo JText::_('COM_KOMENTO_CONTINUE_TO_BACKEND');?></span></b>
		</a>
	</div>
</div>
<?php } ?>