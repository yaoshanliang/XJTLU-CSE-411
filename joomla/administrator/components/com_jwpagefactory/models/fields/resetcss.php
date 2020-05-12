<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
defined('JPATH_PLATFORM') or die;

jimport('joomla.form.formfield');

class JFormFieldResetcss extends JFormField
{

	protected $type = 'Resetcss';

	protected function getInput()
	{

		Jhtml::_('jquery.framework');
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration('jQuery(function($) {
			$("#btn-reset-css").on("click", function(event) {
				event.preventDefault();
				var $this = $(this);
				$this.text($this.data("loading"));
				var request = {
					"option" : "com_jwpagefactory",
					"task" : "resetcss"
				};
				$.ajax({
					type   : "POST",
					data   : request,
					success: function (data) {
						$this.text($this.data("text"));
					}
				});
				
			});
		});');

		return '<a id="btn-reset-css" class="btn btn-default" data-text="' . JText::_('COM_JWPAGEFACTORY_RESET_CSS_TEXT') . '" data-loading="' . JText::_('COM_JWPAGEFACTORY_RESET_CSS_TEXT_LOADING') . '" href="#">' . JText::_('COM_JWPAGEFACTORY_RESET_CSS_TEXT') . '</a>';
	}
}
