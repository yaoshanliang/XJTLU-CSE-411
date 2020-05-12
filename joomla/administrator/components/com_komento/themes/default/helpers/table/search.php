<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<input type="text" class="o-form-control app-filter-bar__search-input" name="search" value="<?php echo $this->escape($value);?>" placeholder="<?php echo JText::_('COM_KOMENTO_COMMENTS_SEARCH', true );?>" data-kt-table-search-input />
<span class="app-filter-bar__search-btn-group">
    <button class="btn btn-kt-default-o app-filter-bar__search-btn" data-kt-table-search>
        <i class="fa fa-search"></i>
    </button>

    <button class="btn btn-kt-danger-o app-filter-bar__search-btn" data-kt-table-search-reset>
        <i class="fa fa-times"></i>
    </button>
</span>