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

// get the background presets
$bgModel = KT::model('Backgrounds');
$presets = $bgModel->getPresets(array('published' => true));

?>
<style type="text/css">

<?php foreach ($presets as $preset) { ?>
	#kt .kt-form-bg--<?php echo $preset->id; ?> {
		color: <?php echo $preset->getParams()->get('fontcolor'); ?> !important;
		background: rgba(<?php echo $preset->getRGBColor('color1'); ?>) !important;
		background: -moz-linear-gradient(left, rgba(<?php echo $preset->getRGBColor('color1'); ?>) 0%, rgba(<?php echo $preset->getRGBColor('color2'); ?>) 100%) !important;
		background: -webkit-gradient(left top, right top, color-stop(0%, rgba(<?php echo $preset->getRGBColor('color1'); ?>)), color-stop(100%, rgba(<?php echo $preset->getRGBColor('color2'); ?>))) !important;
		background: -webkit-linear-gradient(left, rgba(<?php echo $preset->getRGBColor('color1'); ?>) 0%, rgba(<?php echo $preset->getRGBColor('color2'); ?>) 100%) !important;
		background: -o-linear-gradient(left, rgba(<?php echo $preset->getRGBColor('color1'); ?>) 0%, rgba(<?php echo $preset->getRGBColor('color2'); ?>) 100%) !important;
		background: -ms-linear-gradient(left, rgba(<?php echo $preset->getRGBColor('color1'); ?>) 0%, rgba(<?php echo $preset->getRGBColor('color2'); ?>) 100%) !important;
		background: linear-gradient(to <?php echo $preset->getGradientDirection(); ?>, rgba(<?php echo $preset->getRGBColor('color1'); ?>) 0%, rgba(<?php echo $preset->getRGBColor('color2'); ?>) 100%) !important;
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3e28b4', endColorstr='#cd66da', GradientType=1 ) !important;
	}
	#kt .kt-form-bg--<?php echo $preset->id; ?>::-webkit-input-placeholder {
		color: <?php echo $preset->getParams()->get('fontcolor'); ?> !important;
		opacity: .7 !important;
	}
	#kt .kt-form-bg--<?php echo $preset->id; ?>::-moz-placeholder {
		color: <?php echo $preset->getParams()->get('fontcolor'); ?> !important;
		opacity: .7 !important;
	}
	#kt .kt-form-bg--<?php echo $preset->id; ?>:-ms-input-placeholder {
		color: <?php echo $preset->getParams()->get('fontcolor'); ?> !important;
		opacity: .7 !important;
	}
	#kt .kt-form-bg--<?php echo $preset->id; ?>:-moz-placeholder {
		color: <?php echo $preset->getParams()->get('fontcolor'); ?> !important;
		opacity: .7 !important;
	}
	#kt .kt-form-editor.kt-form-bg--<?php echo $preset->id;?> .kt-form-editor__smiley-toggle > i {
		color: <?php echo $preset->getParams()->get('fontcolor'); ?> !important;
	}
<?php } ?>
</style>