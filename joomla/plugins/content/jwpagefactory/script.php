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

class PlgContentJwpagefactoryInstallerScript
{

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	public function preflight($type, $parent)
	{
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	public function postflight($action, $parent)
	{
		if ($action == 'uninstall') {
			return true;
		}

		//JInstaller $grandpa
		$grandpa = $parent->getParent();
		$manifest = $grandpa->manifest;

		//get group & name of plugin
		list($group, $name) = $this->_getPlgGroupNameByManifest($manifest);
		if ($group && $name) {

			//enable extension
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$fields = array(
				$db->qn('enabled') . ' = 1'
			);
			$conditions = array(
				$db->qn('type') . ' = ' . $db->q('plugin'),
				$db->qn('element') . ' = ' . $db->q($name),
				$db->qn('folder') . ' = ' . $db->q($group)
			);
			$query->update($db->qn('#__extensions'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$db->execute();

			//install or update component integrations
			if (isset($manifest->_config)) {
				//_config data
				$_title = (string)$manifest->_config->_title;
				$_description = (string)$manifest->_config->_description;
				$_component = (string)$manifest->_config->_component;
				$_plugin = json_encode(array('group' => $group, 'name' => $name));
				$_version = (string)$manifest->version;

				try {
					$db = JFactory::getDbo();
					$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__jwpagefactory_integrations'))
						->where($db->qn('component') . '=' . $db->q($_component));
					$db->setQuery($query);
					$oldObject = $db->loadObject();
					if ($oldObject) {
						$oldObject->title = $_title;
						$oldObject->description = $_description;
						$oldObject->plugin = $_plugin;
						$oldObject->version = $_version;
						$oldObject->state = 1;
						$db->updateObject('#__jwpagefactory_integrations', $oldObject, 'id');
					} else {
						$newObject = new stdClass();
						$newObject->title = $_title;
						$newObject->description = $_description;
						$newObject->component = $_component;
						$newObject->plugin = $_plugin;
						$newObject->version = $_version;
						$newObject->state = 1;
						$db->insertObject('#__jwpagefactory_integrations', $newObject);
					}
				} catch (Exception $e) {

				}
			}
		}

	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	public function uninstall($parent)
	{
		//JInstaller $grandpa
		$grandpa = $parent->getParent();
		$manifest = $grandpa->manifest;

		//get group & name of plugin
		list($group, $name) = $this->_getPlgGroupNameByManifest($manifest);
		if ($group && $name) {

			//delete component integrations
			if (isset($manifest->_config)) {
				//_config data
				$_component = (string)$manifest->_config->_component;

				try {
					$db = JFactory::getDbo();
					$query = $db->getQuery(true)
						->select('*')
						->from($db->qn('#__jwpagefactory_integrations'))
						->where($db->qn('component') . '=' . $db->q($_component));
					$db->setQuery($query);
					$oldObject = $db->loadObject();
					if ($oldObject) {
						$oldObject->state = 2;
						$db->updateObject('#__jwpagefactory_integrations', $oldObject, 'id');
					}
				} catch (Exception $e) {

				}
			}
		}
	}

	// get plugin group name by manifest
	private function _getPlgGroupNameByManifest($manifest)
	{
		$element = $manifest->files;
		if ($element) {
			$group = strtolower((string)$manifest->attributes()->group);
			$name = '';
			if (count($element->children())) {
				foreach ($element->children() as $file) {
					if ((string)$file->attributes()->plugin) {
						$name = strtolower((string)$file->attributes()->plugin);
						break;
					}
				}
			}
			if ($group && $name) {
				return array($group, $name);
			}
		}
		return false;
	}
}
