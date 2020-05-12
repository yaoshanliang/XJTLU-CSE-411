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
defined('_JEXEC') or die();

class lang_jwpagefactory_chineseInstallerScript
{

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	public function preflight($action, $parent)
	{
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	public function postflight($action, JInstallerAdapterFile $parent)
	{
		if ($action == 'uninstall') {
			return true;
		}

		//JInstaller $grandpa
		$grandpa = $parent->getParent();
		$manifest = $grandpa->manifest;

		//install or update component languages
		if (isset($manifest->_config)) {
			//_config data
			$_title = (string)$manifest->_config->_title;
			$_description = (string)$manifest->_config->_description;
			$_lang_tag = (string)$manifest->_config->_lang_tag;
			$_lang_key = (string)$manifest->_config->_lang_key;
			$_version = (string)$manifest->version;

			try {
				// Install or update database
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__jwpagefactory_languages'))
					->where(array(
						$db->qn('lang_tag') . '=' . $db->q($_lang_tag),
						$db->qn('lang_key') . '=' . $db->q($_lang_key)
					));
				$db->setQuery((string)$query);
				$oldObject = $db->loadObject();
				if ($oldObject) {
					// update
					$oldObject->title = $_title;
					$oldObject->description = $_description;
					$oldObject->version = $_version;
					$oldObject->state = 1;
					$db->updateObject('#__jwpagefactory_languages', $oldObject, 'id');
				} else {
					// insert
					$newObject = new stdClass();
					$newObject->title = $_title;
					$newObject->lang_tag = $_lang_tag;
					$newObject->lang_key = $_lang_key;
					$newObject->description = $_description;
					$newObject->version = $_version;
					$newObject->state = 1;
					$db->insertObject('#__jwpagefactory_languages', $newObject);
				}
			} catch (Exception $e) {

			}
		}

	}

	/**
	 * method to uninstall the manifests
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
		//JInstaller $grandpa
		$grandpa = $parent->getParent();
		$manifest = $grandpa->manifest ? $grandpa->manifest : $parent->manifest;

		//delete component languages
		if (isset($manifest->_config)) {
			//_config data
			$_lang_tag = (string)$manifest->_config->_lang_tag;
			$_lang_key = (string)$manifest->_config->_lang_key;

			try {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__jwpagefactory_languages'))
					->where(array(
						$db->qn('lang_tag') . '=' . $db->q($_lang_tag),
						$db->qn('lang_key') . '=' . $db->q($_lang_key)
					));
				$db->setQuery((string)$query);
				$oldObject = $db->loadObject();
				if ($oldObject) {
					// update
					$oldObject->state = 2;
					$db->updateObject('#__jwpagefactory_languages', $oldObject, 'id');
				}
			} catch (Exception $e) {

			}
		}
	}

}
