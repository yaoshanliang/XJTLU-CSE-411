<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldJwmedia extends JFormField
{
	protected $type = 'Jwmedia';

	protected function getInput() {

		$media_format = $this->getAttribute('media_format', 'image');

		JText::script('COM_JWPAGEFACTORY_MEDIA_MANAGER_CONFIRM_DELETE');
		JText::script('COM_JWPAGEFACTORY_MEDIA_MANAGER_ENTER_DIRECTORY_NAME');

		$html = '';

		if($this->value) {
			if($media_format == 'image') {
				$html = '<img class="jw-pagefactory-media-preview" src="' . JURI::root(true) . '/' . $this->value . '" alt="" />';
			}
		} else {
			if($media_format == 'image') {
				$html  = '<img class="jw-pagefactory-media-preview jw-pagefactory-media-no-image" alt="">';
			}
		}

		if($media_format == 'image') {
			$html .= '<input class="jw-media-input" type="hidden" name="'. $this->name .'" id="'. $this->id .'" value="'. $this->value .'">';
		} else {
			$html .= '<input class="jw-media-input" type="text" name="'. $this->name .'" id="'. $this->id .'" value="'. $this->value .'">';
		}

		$html .= '<a href="#" id="media-upload-button" class="jw-pagefactory-btn jw-pagefactory-btn-primary jw-pagefactory-btn-media-manager" data-support="' . $media_format . '"><i class="fa fa-spinner fa-spin" style="margin-right: 5px; display: none;"></i>'. JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_UPLOAD_' . strtoupper($media_format)) .'</a> <a href="#" class="jw-pagefactory-btn jw-pagefactory-btn-danger jw-pagefactory-btn-clear-media"><i class="fa fa-times"></i></a>';

		return $html;
	}
}
