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
<div class="kt-captcha">
	<div class="kt-captcha__title">
		<?php echo JText::_('COM_KOMENTO_COMMENT_CAPTCHA_DESC'); ?>	
	</div>
	<div class="o-grid">
		<div class="o-grid__cell-auto-size">
			<div class="kt-captcha__form">
				<div class="kt-captcha__img">
					<img src="<?php echo $url;?>" data-kt-captcha-image />
				</div>
				
				<div class="kt-captcha__reload">
					<a href="javascript:void(0);" class="btn btn-kt-default-o" data-kt-captcha-reload>
						<i class="fa fa-refresh"></i>
						<div class="o-loader o-loader--sm"></div>
					</a>
				</div>
			</div>
		</div>

		<div class="o-grid__cell">
			<div class="o-form-inline">
				<input type="text" name="captchaResponse" id="captcha-response" class="o-form-control" maxlength="5" data-kt-captcha-response />
			</div>
		</div>
	</div>	
	<input type="hidden" name="captchaId" id="captcha-id" value="<?php echo $this->escape($id);?>" data-kt-captcha-id />
</div>

