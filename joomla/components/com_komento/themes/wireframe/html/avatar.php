<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="kt-avatar" 
	<?php if ($this->config->get('easysocial_profile_popbox') && !$user->guest) { ?>
		data-popbox="module://easysocial/profile/popbox" data-user-id="<?php echo $user->id; ?>"
	<?php } ?>
>
	<?php echo $user->getAvatarHtml($name, $email, $website); ?>

	<?php if ($this->config->get('enable_rank_bar') && !$user->guest) { ?>
	<div class="kmt-rank t-lg-mt--md">
		<div class="kmt-rank-bar">
			<div class="kmt-rank-progress" style="width: <?php echo $user->getRankProgress(); ?>%;"></div>
		</div>
	</div>
	<?php } ?>
</div>
