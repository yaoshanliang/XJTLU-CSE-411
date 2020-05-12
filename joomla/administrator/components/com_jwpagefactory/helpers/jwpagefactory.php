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
defined('_JEXEC') or die ('Restricted access');

// if sh404sef component is installed
if (JComponentHelper::isEnabled('com_sh404sef', false) || JPluginHelper::getPlugin('system', 'sh404sef')) {
	$sh404sefGeneralHelper = JPATH_ROOT . '/administrator/components/com_sh404sef/helpers/general.php';
	$sh404sefLanguageHelper = JPATH_ROOT . '/administrator/components/com_sh404sef/helpers/language.php';
	if (!file_exists($sh404sefGeneralHelper) || !file_exists($sh404sefLanguageHelper)) {
		echo JFactory::getApplication()->enqueueMessage(JText::_('COM_JWPAGEFACTORY_SH404SEF_HELPER_FILES_NOT_EXIST'), 'error');
	} else {
		require_once $sh404sefGeneralHelper;
		require_once $sh404sefLanguageHelper;
	}
}

abstract class JwpagefactoryHelper
{

	public static $extension = 'com_jwpagefactory';

	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			'<i class="fa fa-list-ul"></i> ' . JText::_('COM_JWPAGEFACTORY_PAGES'),
			'index.php?option=com_jwpagefactory&view=pages',
			$vName == 'pages'
		);

		JHtmlSidebar::addEntry(
			'<i class="fa fa-folder-o"></i> ' . JText::_('COM_JWPAGEFACTORY_CATEGORIES'),
			'index.php?option=com_categories&extension=com_jwpagefactory',
			$vName == 'categories');

		JHtmlSidebar::addEntry(
			'<i class="fa fa-plug"></i> ' . JText::_('COM_JWPAGEFACTORY_INTEGRATIONS'),
			'index.php?option=com_jwpagefactory&view=integrations',
			$vName == 'integrations'
		);

		JHtmlSidebar::addEntry(
			'<i class="fa fa-globe"></i> ' . JText::_('COM_JWPAGEFACTORY_LANGUAGES'),
			'index.php?option=com_jwpagefactory&view=languages',
			$vName == 'languages'
		);

		JHtmlSidebar::addEntry(
			'<i class="fa fa-info-circle"></i> ' . JText::_('COM_JWPAGEFACTORY_ABOUT_JWPF'),
			'index.php?option=com_jwpagefactory&view=about',
			$vName == 'about'
		);

		JHtmlSidebar::addEntry(
			'<i class="fa fa-picture-o"></i> ' . JText::_('COM_JWPAGEFACTORY_MEDIA') . ($vName == 'media' ? '<span><i class="fa fa-chevron-down pull-right"></i></span>' : ''),
			'index.php?option=com_jwpagefactory&view=media',
			$vName == 'media'
		);

	}

	public static function getVersion()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('e.manifest_cache')
			->select($db->quoteName('e.manifest_cache'))
			->from($db->quoteName('#__extensions', 'e'))
			->where($db->quoteName('e.element') . ' = ' . $db->quote('com_jwpagefactory'));

		$db->setQuery($query);
		$manifest_cache = json_decode($db->loadResult());

		if (isset($manifest_cache->version) && $manifest_cache->version) {
			return $manifest_cache->version;
		}

		return '1.0';
	}

	// 3rd party
	public static function onAfterIntegrationSave($attribs)
	{

		if (!self::getIntegration($attribs['option'])) return;

		$db = JFactory::getDbo();

		if (self::checkPage($attribs['option'], $attribs['view'], $attribs['id'])) {

			if ($attribs['action'] == 'stateChange') {
				$fields = array(
					$db->quoteName('published') . ' = ' . $db->quote($attribs['published'])
				);
				self::updatePage($attribs['option'], $attribs['view'], $attribs['id'], $fields);
			} elseif ($attribs['action'] == 'delete') {
				self::deleteArticlePage($attribs);
			} else {
				$fields = array(
					$db->quoteName('title') . ' = ' . $db->quote($attribs['title']),
					$db->quoteName('text') . ' = ' . $db->quote($attribs['text']),
					$db->quoteName('published') . ' = ' . $db->quote($attribs['published']),
					$db->quoteName('catid') . ' = ' . $db->quote($attribs['catid']),
					$db->quoteName('access') . ' = ' . $db->quote($attribs['access']),
					$db->quoteName('modified') . ' = ' . $db->quote($attribs['modified']),
					$db->quoteName('modified_by') . ' = ' . $db->quote($attribs['modified_by']),
					$db->quoteName('active') . ' = ' . $db->quote($attribs['active'])
				);
				self::updatePage($attribs['id'], $fields, $attribs['view']);
			}

		} else {
			$values = array(
				$db->quote($attribs['title']),
				$db->quote($attribs['text']),
				$db->quote($attribs['option']),
				$db->quote($attribs['view']),
				$db->quote($attribs['id']),
				$db->quote($attribs['active']),
				$db->quote($attribs['published']),
				$db->quote($attribs['catid']),
				$db->quote($attribs['access']),
				$db->quote($attribs['created_on']),
				$db->quote($attribs['created_by']),
				$db->quote($attribs['modified']),
				$db->quote($attribs['modified_by']),
				$db->quote($attribs['language'])
			);

			self::insertPage($values);
		}

		return true;
	}

	public static function onIntegrationPrepareContent($text, $option, $view, $id = 0)
	{

		if (!self::getIntegration($option)) return $text;

		$pageName = $view . '-' . $id;

		$page_content = self::getPageContent($option, $view, $id);
		if ($page_content) {
			jimport('joomla.application.component.helper');
			require_once JPATH_ROOT . '/components/com_jwpagefactory/parser/addon-parser.php';
			JHtml::_('jquery.framework');
			$doc = JFactory::getDocument();
			$params = JComponentHelper::getParams('com_jwpagefactory');
			if ($params->get('fontawesome', 1)) {
				$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
				$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
			}
			if (!$params->get('disableanimatecss', 0)) {
				$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/animate.min.css');
			}
			if (!$params->get('disablecss', 0)) {
				$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
			}
			$doc->addScript(JUri::base(true) . '/components/com_jwpagefactory/assets/js/jquery.parallax.js');
			$doc->addScript(JUri::base(true) . '/components/com_jwpagefactory/assets/js/jwpagefactory.js');

			return '<div id="jw-page-factory" class="jw-page-factory jwpf-' . $view . '-page-wrapper"><div class="page-content">' . JwpfAddonParser::viewAddons(json_decode($page_content->text), 0, $pageName) . '</div></div>';
		}

		return $text;
	}

	public static function getPageContent($extension, $extension_view, $view_id = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__jwpagefactory'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote($extension));
		$query->where($db->quoteName('extension_view') . ' = ' . $db->quote($extension_view));
		$query->where($db->quoteName('view_id') . ' = ' . $db->quote($view_id));
		$query->where($db->quoteName('active') . ' = 1');
		$db->setQuery($query);
		$result = $db->loadObject();

		if (count((array)$result)) {
			return $result;
		}

		return false;
	}

	private static function checkPage($extension, $extension_view, $view_id = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__jwpagefactory'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote($extension));
		$query->where($db->quoteName('extension_view') . ' = ' . $db->quote($extension_view));
		$query->where($db->quoteName('view_id') . ' = ' . $db->quote($view_id));
		$db->setQuery($query);

		return $db->loadResult();
	}

	private static function insertPage($content = array())
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$columns = array(
			'title',
			'text',
			'extension',
			'extension_view',
			'view_id',
			'active',
			'published',
			'catid',
			'access',
			'created_on',
			'created_by',
			'modified',
			'modified_by',
			'language'
		);
		$query->insert($db->quoteName('#__jwpagefactory'))
			->columns($db->quoteName($columns))
			->values(implode(',', $content));

		$db->setQuery($query);
		$db->execute();
	}

	private static function updatePage($view_id, $content, $extension_view = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$condition = array($db->quoteName('view_id') . ' = ' . $db->quote($view_id));

		if ($extension_view != '') {
			array_push($condition, $db->quoteName('extension_view') . ' = ' . $db->quote($extension_view));
		}

		$query->update($db->quoteName('#__jwpagefactory'))->set($content)->where($condition);

		$db->setQuery($query);
		$db->execute();
	}

	private static function getIntegration($option)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$query->select('a.id');
		$query->from('#__jwpagefactory_integrations as a');
		$query->where($db->quoteName('component') . ' = ' . $db->quote($option));
		$query->where($db->quoteName('state') . ' = 1');
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}

	public static function getMenuId($pageId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__menu'));
		$query->where($db->quoteName('link') . ' LIKE ' . $db->quote('%option=com_jwpagefactory&view=page&id=' . $pageId . '%'));
		$query->where($db->quoteName('published') . ' = ' . $db->quote('1'));
		$db->setQuery($query);
		$result = $db->loadResult();

		if ($result) {
			return '&Itemid=' . $result;
		}

		return '';
	}

	private static function deleteArticlePage($params)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('extension') . ' = ' . $db->quote($params['option']),
			$db->quoteName('extension_view') . ' = ' . $db->quote($params['view']),
			$db->quoteName('view_id') . ' = ' . $db->quote($params['id']),
		);

		$query->delete($db->quoteName('#__jwpagefactory'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Get preview and frontend edit SEF url
	 * Depedency check for SH404SEF or others component
	 *
	 * @param    $router        Joomla app router
	 * @param    $page_url    valid URL
	 */
	public static function generateSefUrl($router, $page_url)
	{
		// if SH404SEF component is enabled and plugin installed
		if (JComponentHelper::isEnabled('com_sh404sef', false) && JPluginHelper::getPlugin('system', 'sh404sef')) {
			$jconf = JFactory::getConfig();
			$sh404sef_params = JComponentHelper::getParams('com_sh404sef');
			$attr_search = array('/administrator', '//');
			$attr_replace = array('', '/');

			// if SH404SEF and sef is enabled from global configuration
			if ($sh404sef_params->get('Enabled') == 1 && $jconf->get('sef') == 1) {
				return str_replace($attr_search, $attr_replace, Sh404sefHelperGeneral::getSefFromNonSef($page_url, $fullyQualified = false, $xhtml = false, $ssl = null));
			} else {
				return str_replace($attr_search, $attr_replace, $router->build($page_url));
			}
		} else {
			return str_replace('/administrator', '', $router->build($page_url));
		}
	}

	/**
	 * Get preview and frontend edit SEF url
	 * Depedency check for SH404SEF or others component
	 *
	 * @param    $router        Joomla app router
	 * @param    $page_url    valid URL
	 */
	public static function generateEditUrl($item, $menuid)
	{

		// generate language
		$lang = '';
		if (isset($item->language) && $item->language != '*') {
			$lang_array = explode('-', $item->language);
			$lang = '&lang=' . $lang_array[0];
		}

		// if sh404sef is enabled
		if (JComponentHelper::isEnabled('com_sh404sef', false) && JPluginHelper::getPlugin('system', 'sh404sef')) {
			$jconf = JFactory::getConfig();
			$sh404sef_params = JComponentHelper::getParams('com_sh404sef');
			if ($sh404sef_params->get('Enabled') == 1 && $jconf->get('sef') == 1) {
				$iframe_url = 'index.php?option=com_jwpagefactory&amp;view=form&amp;id=' . $item->id . '&amp;layout=edit-iframe&amp;Itemid=' . $lang . $menuid;
				$iframe_sef_url = self::generateSefUrl(JFactory::getApplication()->getRouter(), $iframe_url);
			} else {
				$iframe_url = JURI::root(true) . '/index.php?option=com_jwpagefactory&amp;view=form&amp;id=' . $item->id . '&amp;layout=edit-iframe&amp;Itemid=' . $menuid . $lang;
				$iframe_sef_url = JRoute::_($iframe_url);
			}
		} else {
			$iframe_url = JURI::root(true) . '/index.php?option=com_jwpagefactory&amp;view=form&amp;id=' . $item->id . '&amp;layout=edit-iframe&amp;Itemid=' . $menuid . $lang;
			$iframe_sef_url = JRoute::_($iframe_url);
		}

		return $iframe_sef_url;
	}
}
