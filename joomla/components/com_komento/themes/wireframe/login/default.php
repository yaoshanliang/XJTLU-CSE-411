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
<div class="kt-login">
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login');?>" method="post" data-kt-login-form>
		<div class="o-grid o-grid--gutters ">
			<div class="o-grid__cell">
				<?php echo $this->html('form.floatinglabel', $usernameField, 'username'); ?>
			</div>

			<div class="o-grid__cell kt-login__cell-pass">
				<?php echo $this->html('form.floatinglabel', 'COM_KOMENTO_LOGIN_PASSWORD', 'password', 'password'); ?>
			</div>
		</div>

		<div class="kt-login-actions t-text--right">
			<button type="submit" class="btn btn-kt-default btn-kt-sm" data-kt-login-submit><?php echo JText::_('COM_KT_LOGIN_TO_MY_ACCOUNT');?> &rarr;</button>
		</div>

		<input type="hidden" value="com_users"  name="option">
		<input type="hidden" value="user.login" name="task">
		<input type="hidden" name="return" value="<?php echo base64_encode(JURI::getInstance()->toString() . '#commentform'); ?>" />
		<?php echo $this->html('form.token'); ?>
	</form>
</div>
