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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class com_jwpagefactoryInstallerScript
{

	// min version of PHP
	protected $minimumPHPVersion = '5.6.0';

	/**
	 * This runs before Joomla! installs or updates the component.
	 * This is our last chance to tell Joomla! if it should abort the installation
	 * Verifications and pre-requisites should run in this function.
	 *
	 * @param   string $type Installation type (install, update, discover_install)
	 * @param   JInstaller $parent Parent object
	 *
	 * @return  boolean  True to let the installation proceed, false to halt the installation
	 */
	public function preflight($type, $parent)
	{

		if ($type == 'install' || $type == 'update') {

			// Check the minimum PHP version
			if (!empty($this->minimumPHPVersion)) {
				if (defined('PHP_VERSION')) {
					$version = PHP_VERSION;
				} elseif (function_exists('phpversion')) {
					$version = phpversion();
				} else {
					$version = '0.0.0'; // all bets are off!
				}
				if (!version_compare($version, $this->minimumPHPVersion, 'ge')) {
					\JFactory::getApplication()->enqueueMessage('Your PHP version is too old for this component.', 'error');

					// exit install or update
					return false;
				}
			}

			//Give a warning if cURL or allow_url_fopen is not enabled on system;
			if (!ini_get('allow_url_fopen') && !extension_loaded('curl')) {
				\JFactory::getApplication()->enqueueMessage('PHP环境不支持allow_url_fopen或cUrl扩展，获取远程资源功能不能使用', 'warning');
			}

			//Give a warning if JCE is not compatibility;
			$editor = \JFactory::getConfig()->get('editor');
			if ($editor == 'jce') {
				require_once(JPATH_ADMINISTRATOR . '/components/com_jce/includes/base.php');
				wfimport('admin.models.editor');
				$editor = new WFModelEditor();

				if (!method_exists($editor, 'render')) {
					\JFactory::getApplication()->enqueueMessage('JCE编辑器组件WFModelEditor->render方法不存在，请及时升级JCE编辑器组件或更换编辑器', 'warning');
				}
			}
		}
	}

	/**
	 * Runs after install, update or discover_update. In other words, it executes after Joomla! has finished installing
	 * or updating your component. This is the last chance you've got to perform any additional installations, clean-up,
	 * database updates and similar housekeeping functions.
	 *
	 * @param   string $type install, update or discover_update
	 * @param   \stdClass $parent - Parent object calling object.
	 * @return void
	 */
	public function postflight($type, $parent)
	{
		if ($type == 'uninstall') {
			return true;
		}

		$grandpa = $parent->getParent();
		$src = $grandpa->getPath('source');
		$manifest = $grandpa->manifest;

		// Install Plugins
		$plugins = $manifest->xpath('plugins/plugin');
		foreach ($plugins as $plugin) {
			$name = (string)$plugin->attributes()->name;
			$group = (string)$plugin->attributes()->group;
			$path = $src . '/plugins/' . $group . '/' . $name;

			$installer = new JInstaller;
			$result = $installer->install($path);

			if ($result && $group != 'finder') {
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
			}
		}

		// Install Modules
		$modules = $manifest->xpath('modules/module');
		foreach ($modules as $module) {
			$name = (string)$module->attributes()->module;
			$client = (string)$module->attributes()->client;
			$path = $src . '/modules/' . $client . '/' . $name;
			$position = (isset($module->attributes()->position) && $module->attributes()->position) ? (string)$module->attributes()->position : '';
			$ordering = (isset($module->attributes()->ordering) && $module->attributes()->ordering) ? (string)$module->attributes()->ordering : 0;

			$installer = new JInstaller;
			$result = $installer->install($path);

			if ($client == 'administrator') {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);

				$fields = array(
					$db->qn('published') . ' = 1'
				);
				if ($position) {
					$fields[] = $db->qn('position') . ' = ' . $db->q($position);
				}
				if ($ordering) {
					$fields[] = $db->qn('ordering') . ' = ' . $db->q($ordering);
				}
				$conditions = array(
					$db->qn('module') . ' = ' . $db->q($name)
				);
				$query->update($db->qn('#__modules'))->set($fields)->where($conditions);
				$db->setQuery($query);
				$db->execute();

				// Retrive ID
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select($db->qn('id'))
					->from($db->qn('#__modules'))
					->where($db->qn('module') . ' = ' . $db->q($name));
				$db->setQuery($query);
				$id = (int)$db->loadResult();
				if ($id) {
					$db = JFactory::getDbo();
					$db->setQuery("INSERT IGNORE INTO #__modules_menu (`moduleid`,`menuid`) VALUES (" . $id . ", 0)");
					$db->query();
				}
			}
		}

		// Fix zh-CN Language Package Installation
		$langPkg_element = 'lang_jwpagefactory_chinese';
		$xml_file = $src . '/chinese/' . $langPkg_element . '.xml';
		$script_file = $src . '/chinese/script.php';
		if (file_exists($xml_file)) {
			$xml_details = JInstaller::parseXMLInstallFile($xml_file);

			$db = JFactory::getDBO();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__extensions'))
				->where(array(
					$db->qn('type') . ' = ' . $db->q('file'),
					$db->qn('element') . ' = ' . $db->q($langPkg_element)
				));
			$db->setQuery($query);
			$oldObject = $db->loadObject();
			if ($oldObject) {
				$oldObject->name = $xml_details['name'];
				$oldObject->manifest_cache = json_encode($xml_details);
				$oldObject->enabled = 1;
				$db->updateObject('#__extensions', $oldObject, 'extension_id');
			} else {
				$newObject = new stdClass();
				$newObject->name = $xml_details['name'];
				$newObject->type = $xml_details['type'];
				$newObject->element = $langPkg_element;
				$newObject->manifest_cache = json_encode($xml_details);
				$newObject->enabled = 1;
				$db->insertObject('#__extensions', $newObject);
			}

			// Insure cache folder exists
			$files_src = JPATH_ADMINISTRATOR . '/manifests/files';
			$files_elesrc = $files_src . '/' . $langPkg_element;
			if (!file_exists($files_elesrc)) {
				JFolder::create($files_elesrc, 0755);
			}
			JFile::copy($xml_file, $files_src . '/' . $langPkg_element . '.xml');
			JFile::copy($script_file, $files_elesrc . '/script.php');
		}

		// show success result
		echo '<link href="' . JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css" rel="stylesheet">';
		echo '<link href="' . JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css" rel="stylesheet">';
		echo '<link href="' . JURI::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css" rel="stylesheet">';
		echo '<style>.jw-pagefactory-pages.jw-pagefactory-about-view{padding: 0;margin: 0 0 20px 0;}</style>';
		include_once JPATH_ADMINISTRATOR . '/components/com_jwpagefactory/views/about/tmpl/default_about.php';
	}

	/**
	 * Runs on uninstallation
	 *
	 * @param   JInstallerAdapterFile $parent The parent object
	 *
	 * @throws  RuntimeException  If the uninstallation is not allowed
	 */
	public function uninstall($parent)
	{
		$grandpa = $parent->getParent();
		$manifest = $grandpa->manifest;

		// Uninstall Plugins
		$plugins = $manifest->xpath('plugins/plugin');
		foreach ($plugins as $plugin) {
			$name = (string)$plugin->attributes()->name;
			$group = (string)$plugin->attributes()->group;

			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn('extension_id'))
				->from($db->qn('#__extensions'))
				->where(array(
					$db->qn('type') . ' = ' . $db->q('plugin'),
					$db->qn('element') . ' = ' . $db->q($name),
					$db->qn('folder') . ' = ' . $db->q($group)
				));
			$db->setQuery($query);
			$extensions = $db->loadColumn();
			if (count((array)$extensions)) {
				foreach ($extensions as $id) {
					$installer = new JInstaller;
					$installer->uninstall('plugin', $id);
				}
			}
		}

		// Uninstall Modules
		$modules = $manifest->xpath('modules/module');
		foreach ($modules as $module) {
			$name = (string)$module->attributes()->module;
			$client = (string)$module->attributes()->client;

			$db = JFactory::getDBO();
			$query = $db->getQuery(true)
				->select($db->qn('extension_id'))
				->from($db->qn('#__extensions'))
				->where(array(
					$db->qn('type') . ' = ' . $db->q('module'),
					$db->qn('element') . ' = ' . $db->q($name)
				));
			$db->setQuery($query);
			$extensions = $db->loadColumn();
			if (count((array)$extensions)) {
				foreach ($extensions as $id) {
					$installer = new JInstaller;
					$installer->uninstall('module', $id);
				}
			}
		}

		// Uninstall zh-CN Language Packages
		$db = JFactory::getDBO();
		$query = $db->getQuery(true)
			->select($db->qn('extension_id'))
			->from($db->qn('#__extensions'))
			->where(array(
				$db->qn('type') . ' = ' . $db->q('file'),
				$db->qn('element') . ' = ' . $db->q('lang_jwpagefactory_chinese')
			));
		$db->setQuery($query);
		$id = (int)$db->loadResult();
		if ($id) {
			$installer = new JInstaller;
			$installer->uninstall('file', $id);
		}
	}

}
