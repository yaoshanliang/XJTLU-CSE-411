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
defined('_JEXEC') or die ('restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.event.plugin');
jimport('joomla.registry.registry');

class  plgSystemJwpagefactoryproupdater extends JPlugin
{

	protected $autoloadLanguage = true;

	public function onExtensionAfterSave($option, $data)
	{

		if (($option == 'com_config.component') && ($data->element == 'com_jwpagefactory')) {

			$params = new JRegistry;
			$params->loadString($data->params);

			$email = $params->get('joomworker_email');
			$license_key = $params->get('joomworker_license_key');

			if (!empty($email) and !empty($license_key)) {

				$extra_query = 'joomworker_email=' . urlencode($email);
				$extra_query .= '&amp;joomworker_license_key=' . urlencode($license_key);

				$db = JFactory::getDbo();

				$fields = array(
					$db->quoteName('extra_query') . '=' . $db->quote($extra_query),
					$db->quoteName('last_check_timestamp') . '=0'
				);

				// Update site
				$query = $db->getQuery(true)
					->update($db->quoteName('#__update_sites'))
					->set($fields)
					->where($db->quoteName('name') . '=' . $db->quote('JW Page Factory'));
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

}