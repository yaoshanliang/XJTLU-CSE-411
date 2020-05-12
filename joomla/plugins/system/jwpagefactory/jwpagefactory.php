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

require_once JPATH_ADMINISTRATOR . '/components/com_jwpagefactory/helpers/integrations.php';

class  plgSystemJwpagefactory extends JPlugin
{

	protected $autoloadLanguage = true;
	protected $pagefactory_content = '[]';
	protected $pagefactory_active = 0;

	function onBeforeRender()
	{
		$app = JFactory::getApplication();

		if ($app->isAdmin()) {
			$integrations = $this->getIntegrations();
			if (!$integrations) return;

			$input = $app->input;
			$option = $input->get('option', '', 'STRING');
			$view = $input->get('view', '', 'STRING');
			$layout = $input->get('layout', '', 'STRING');
			$context = $option . '.' . $view;

			if (!array_key_exists($context, $integrations)) return;
			$integration = $integrations[$context];

			// Get ID
			$id = $input->get($integration['id_alias'], 0, 'INT');

			require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/builder/classes/base.php';
			require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/builder/classes/config.php';

			$this->loadPageFactoryLanguage();

			JHtml::_('jquery.ui', array('core', 'sortable'));
			$doc = JFactory::getDocument();
			$params = JComponentHelper::getParams('com_jwpagefactory');
			if ($params->get('fontawesome', 1)) {
				$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
				$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
			}
			$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/pbfont.css');
			$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/react-select.css');
			$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
			$doc->addScript(JURI::root(true) . '/plugins/system/jwpagefactory/assets/js/init.js');
			if (JFactory::getConfig()->get('editor') === 'tinymce') {
				$doc->addScript(JURI::root(true) . '/media/editors/tinymce/tinymce.min.js');
			}
			$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/script.js');
			$doc->addScriptdeclaration('var pagefactory_base="' . JURI::root() . '";');


			// Addon List Initialize
			JwPageFactoryBase::loadAddons();
			$fa_icon_list = JwPageFactoryBase::getIconList(); // Icon List
			$animateNames = JwPageFactoryBase::getAnimationsList(); // Animation Names
			$accessLevels = JwPageFactoryBase::getAccessLevelList(); // Access Levels
			$article_cats = JwPageFactoryBase::getArticleCategories(); // Article Categories
			$moduleAttr = JwPageFactoryBase::getModuleAttributes(); // Module Postions and Module Lits
			$rowSettings = JwPageFactoryBase::getRowGlobalSettings(); // Row Settings Attributes
			$columnSettings = JwPageFactoryBase::getColumnGlobalSettings(); // Column Settings Attributes
			$global_attributes = JwPageFactoryBase::addonOptions();

			// Addon List
			$addons_list = JwAddonsConfig::$addons;
			$globalDefault = JwPageFactoryBase::getSettingsDefaultValue($global_attributes);

			JPluginHelper::importPlugin('system');
			$dispatcher = JEventDispatcher::getInstance();

			foreach ($addons_list as $key => &$addon) {
				$new_default_value = JwPageFactoryBase::getSettingsDefaultValue($addon['attr']);
				$addon['default'] = array_merge($new_default_value['default'], $globalDefault['default']);
				$results = $dispatcher->trigger('onBeforeAddonConfigure', array($key, &$addon));
			}

			$row_default_value = JwPageFactoryBase::getSettingsDefaultValue($rowSettings['attr']);
			$rowSettings['default'] = $row_default_value;

			$column_default_value = JwPageFactoryBase::getSettingsDefaultValue($columnSettings['attr']);
			$columnSettings['default'] = $column_default_value;

			$doc->addScriptdeclaration('var addonsJSON=' . json_encode($addons_list) . ';');

			// Addon Categories
			$addon_cats = JwPageFactoryBase::getAddonCategories($addons_list);
			$doc->addScriptdeclaration('var addonCats=' . json_encode($addon_cats) . ';');

			// Global Attributes
			$doc->addScriptdeclaration('var globalAttr=' . json_encode($global_attributes) . ';');
			$doc->addScriptdeclaration('var faIconList=' . json_encode($fa_icon_list) . ';');
			$doc->addScriptdeclaration('var animateNames=' . json_encode($animateNames) . ';');
			$doc->addScriptdeclaration('var accessLevels=' . json_encode($accessLevels) . ';');
			$doc->addScriptdeclaration('var articleCats=' . json_encode($article_cats) . ';');
			$doc->addScriptdeclaration('var moduleAttr=' . json_encode($moduleAttr) . ';');
			$doc->addScriptdeclaration('var rowSettings=' . json_encode($rowSettings) . ';');
			$doc->addScriptdeclaration('var colSettings=' . json_encode($columnSettings) . ';');
			// Media
			$mediaParams = JComponentHelper::getParams('com_media');
			$doc->addScriptdeclaration('var jwpfMediaPath=\'/' . $mediaParams->get('file_path', 'images') . '\';');

			// Retrive content
			$pagefactory_enbaled = 0;
			$initialState = '[]';

			if ($page_content = $this->getPageContent($option, $view, $id)) {
				$pagefactory_enbaled = $page_content->active;

				if (($page_content->text != '') && ($page_content->text != '[]')) {
					$initialState = $page_content->text;
					$this->pagefactory_content = $page_content->text;
				}
				$this->pagefactory_active = $pagefactory_enbaled;
			}

			$integration_element = '.adminform';

			if ($option == 'com_content') {
				$integration_element = '.adminform';
			} else if ($option == 'com_k2') {
				$integration_element = '.k2ItemFormEditor';
			}

			$doc->addScriptdeclaration('var jwIntergationElement="' . $integration_element . '";');
			$doc->addScriptdeclaration('var jwPagefactoryEnabled=' . $pagefactory_enbaled . ';');
			$doc->addScriptdeclaration('var initialState=' . $initialState . ';');
		} else {
			$input = $app->input;
			$option = $input->get('option', '', 'STRING');
			$view = $input->get('view', '', 'STRING');
			$task = $input->get('task', '', 'STRING');
			$id = $input->get('id', 0, 'INT');
			$pageName = '';

			if ($option == 'com_content' && $view == 'article') {
				$pageName = "{$view}-{$id}.css";
			} elseif ($option == 'com_j2store' && $view == 'products' && $task == 'view') {
				$pageName = "article-{$id}.css";
			} elseif ($option == 'com_k2' && $view == 'item') {
				$pageName = "item-{$id}.css";
			} elseif ($option == 'com_jwpagefactory' && $view == 'page') {
				$pageName = "{$view}-{$id}.css";
			}

			$file_path = JPATH_ROOT . '/media/jwpagefactory/css/' . $pageName;
			$file_url = JUri::base(true) . '/media/jwpagefactory/css/' . $pageName;
			if (file_exists($file_path)) {
				$doc = JFactory::getDocument();
				$doc->addStyleSheet($file_url);
			}
		}
	}


	function onAfterRender()
	{
		$app = JFactory::getApplication();

		if ($app->isAdmin()) {
			$integrations = $this->getIntegrations();
			if (!$integrations) return;

			$input = $app->input;
			$option = $input->get('option', '', 'STRING');
			$view = $input->get('view', '', 'STRING');
			$layout = $input->get('layout', '', 'STRING');
			$context = $option . '.' . $view;

			if (!array_key_exists($context, $integrations)) return;

			// Add script
			$body = JResponse::getBody();
			if ($option == 'com_k2') {
				$body = str_replace('<div class="k2ItemFormEditor">', '<div class="jw-pagefactory-btn-group jw-pagefactory-btns-alt"><a href="#" class="jw-pagefactory-btn jw-pagefactory-btn-default jw-pagefactory-btn-switcher btn-action-editor" data-action="editor">' . JText::_('PLG_SYSTEM_JWPAGEFACTORY_JOOMLA_EDITOR') . '</a><a data-action="jwpagefactory" href="#" class="jw-pagefactory-btn jw-pagefactory-btn-default jw-pagefactory-btn-switcher btn-action-jwpagefactory">' . JText::_('PLG_SYSTEM_JWPAGEFACTORY_PAGE_FACTORY') . '</a></div><div class="jw-pagefactory-admin pagefactory-' . str_replace('_', '-', $option) . '" style="display: none;"><div id="jw-pagefactory-page-tools" class="clearfix jw-pagefactory-page-tools"></div><div class="jw-pagefactory-sidebar-and-builder"><div id="jw-pagefactory-section-lib" class="clearfix jw-pagefactory-section-lib"></div><div id="container"></div></div></div><div class="k2ItemFormEditor">', $body);
			} else {
				$body = str_replace('<fieldset class="adminform">', '<div class="jw-pagefactory-btn-group jw-pagefactory-btns-alt"><a href="#" class="jw-pagefactory-btn jw-pagefactory-btn-default jw-pagefactory-btn-switcher btn-action-editor" data-action="editor">' . JText::_('PLG_SYSTEM_JWPAGEFACTORY_JOOMLA_EDITOR') . '</a><a data-action="jwpagefactory" href="#" class="jw-pagefactory-btn jw-pagefactory-btn-default jw-pagefactory-btn-switcher btn-action-jwpagefactory">' . JText::_('PLG_SYSTEM_JWPAGEFACTORY_PAGE_FACTORY') . '</a></div><div class="jw-pagefactory-admin pagefactory-' . str_replace('_', '-', $option) . '" style="display: none;"><div id="jw-pagefactory-page-tools" class="clearfix jw-pagefactory-page-tools"></div><div class="jw-pagefactory-sidebar-and-builder"><div id="jw-pagefactory-section-lib" class="clearfix jw-pagefactory-section-lib"></div><div id="container"></div></div></div><fieldset class="adminform">', $body);
			}

			// Page Factory fields
			$body = str_replace('</form>', '<input type="hidden" id="jform_attribs_jwpagefactory_content" name="jform[attribs][jwpagefactory_content]"></form>' . "\n", $body);
			$body = str_replace('</form>', '<input type="hidden" id="jform_attribs_jwpagefactory_active" name="jform[attribs][jwpagefactory_active]" value="' . $this->pagefactory_active . '"></form>' . "\n", $body);

			//Add script
			$body = str_replace('</body>', '<script type="text/javascript" src="' . JURI::base(true) . '/components/com_jwpagefactory/assets/js/engine.js"></script>' . "\n</body>", $body);
			JResponse::setBody($body);

		}
	}

	private function loadPageFactoryLanguage()
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_jwpagefactory', JPATH_ADMINISTRATOR, $lang->getTag(), true);
		$lang->load('tpl_' . $this->getTemplate(), JPATH_SITE, $lang->getTag(), true);
		require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/helpers/language.php';
	}

	private function getPageContent($extension = 'com_content', $extension_view = 'article', $view_id = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('text', 'active')));
		$query->from($db->quoteName('#__jwpagefactory'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote($extension));
		$query->where($db->quoteName('extension_view') . ' = ' . $db->quote($extension_view));
		$query->where($db->quoteName('view_id') . ' = ' . $view_id);
		$db->setQuery($query);
		$result = $db->loadObject();

		if ($result) {
			return $result;
		}

		return false;
	}

	private function getIntegrations()
	{
		$app = JFactory::getApplication();
		$option = $app->input->get('option', '', "STRING");
		$integrations_list = JwpagefactoryHelperIntegrations::integrations();

		if (!in_array($option, $integrations_list)) {
			return false;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$query->select('a.id, a.component, a.plugin, a.state');
		$query->from('#__jwpagefactory_integrations as a');
		$query->where($db->quoteName('state') . ' = 1');
		$db->setQuery($query);
		$results = $db->loadObjectList();

		$contexts = array();

		foreach ($results as $key => $result) {
			$plugin = json_decode($result->plugin);
			$path = JPATH_PLUGINS . '/' . $plugin->group . '/' . $plugin->name . '/' . $plugin->name . '.php';

			if (file_exists($path)) {
				if (JPluginHelper::isEnabled($plugin->group, $plugin->name)) {
					require_once($path);
					$className = 'Plg' . ucfirst($plugin->group) . ucfirst($plugin->name);
					if (method_exists($className, '__context')) {
						$context = $className::__context();
						$contexts[$context['option'] . '.' . $context['view']] = $className::__context();
					}
				}
			}
		}

		if (count($contexts)) return $contexts;

		return false;
	}

	private function getTemplate()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('template')));
		$query->from($db->quoteName('#__template_styles'));
		$query->where($db->quoteName('client_id') . ' = ' . $db->quote(0));
		$query->where($db->quoteName('home') . ' = ' . $db->quote(1));
		$db->setQuery($query);
		return $db->loadResult();
	}

	public function onExtensionAfterSave($option, $data)
	{
		if (($option == 'com_config.component') && ($data->element == 'com_jwpagefactory')) {
			jimport('joomla.filesystem.folder');
			$admin_cache = JPATH_ROOT . '/administrator/cache/jwpagefactory';
			if (JFolder::exists($admin_cache)) {
				JFolder::delete($admin_cache);
			}

			$site_cache = JPATH_ROOT . '/cache/jwpagefactory';
			if (JFolder::exists($site_cache)) {
				JFolder::delete($site_cache);
			}
		}
	}
}
