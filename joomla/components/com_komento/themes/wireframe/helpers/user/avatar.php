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
<a href="<?php echo $this->escape($user->getProfileLink($email, $website)); ?>" class="o-avatar <?php echo $size;?>" itemprop="url">
	<img src="<?php echo $user->getAvatar($email);?>" alt="<?php echo $this->escape($name);?>" class="avatar" itemprop="image" />
</a>