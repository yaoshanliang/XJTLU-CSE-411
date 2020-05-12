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

jimport('joomla.form.formfield');

class JFormFieldPagefactory extends JFormField
{
	protected $type = 'Pagefactory';

	protected function getInput()
	{
		$output = '';
		$id = (int)JFactory::getApplication()->input->get('id', 0, 'INT');
		if ($id) {
			require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/builder/classes/base.php';
			require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/builder/classes/config.php';

			$this->loadPageFactoryLanguage();

			JHtml::_('jquery.framework');
			JHtml::_('jquery.ui', array('core', 'sortable'));
			$doc = JFactory::getDocument();
			$input = JFactory::getApplication()->input;
			$params = JComponentHelper::getParams('com_jwpagefactory');
			if ($params->get('fontawesome', 1)) {
				$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
				$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
			}
			$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/pbfont.css');
			$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/react-select.css');
			$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
			$doc->addScript(JURI::root(true) . '/media/editors/tinymce/tinymce.min.js');
			$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/script.js');
			$doc->addScript(JURI::root(true) . '/modules/mod_jwpagefactory/assets/js/action.js');
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

			foreach ($addons_list as $key => &$addon) {
				$new_default_value = JwPageFactoryBase::getSettingsDefaultValue($addon['attr']);
				$addon['default'] = array_merge($new_default_value['default'], $globalDefault['default']);
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
			//Global variable for page name
			$doc->addScriptdeclaration('var pageType="module"; ');
			// Media
			$mediaParams = JComponentHelper::getParams('com_media');
			$doc->addScriptdeclaration('var jwpfMediaPath=\'/' . $mediaParams->get('file_path', 'images') . '\';');

			$initialState = '[]';

			$pageData = $this->pageData($id);

			if (isset($pageData->id) && $pageData->id) {
				$view_id = $pageData->id;
				$content = $pageData->text;
			} else {
				$data = $this->form->getData();
				$params = new Joomla\Registry\Registry($this->moduleParams($id));
				$title = $data->get('title');
				$content = $params->get('content', '[]');

				if (!$this->isJson($content)) {
					$content = '[]';
				}

				$view_id = $this->insertData($id, $title, $content);

				if (empty($content)) {
					$content = '[]';
				}
			}

			$initialState = $content;

			$doc->addScriptdeclaration('var initialState=' . $initialState . ';');
			$doc->addScriptdeclaration('var boxLayout=1;');

			$conf = JFactory::getConfig();
			$editor = $conf->get('editor');
			if ($editor == 'jce') {
				require_once(JPATH_ADMINISTRATOR . '/components/com_jce/includes/base.php');
				wfimport('admin.models.editor');
				$editor = new WFModelEditor();
				$app = JFactory::getApplication();
				$settings = $editor->getEditorSettings();
				$app->triggerEvent('onBeforeWfEditorRender', array(&$settings));
				echo $editor->render($settings);
			}

			$app = JApplication::getInstance('site');
			$router = $app->getRouter();
			$front_link = 'index.php?option=com_jwpagefactory&view=form&tmpl=component&layout=edit&extension=mod_jwpagefactory&extension_view=module&id=' . $view_id;
			$sefURI = str_replace('/administrator', '', $router->build($front_link));

			$output = '<a class="btn btn-default" style="margin-bottom: 20px;" href="' . $sefURI . '" target="_blank">Frontend Edit with JW Page Factory</a>';

			$output .= '<div class="jw-pagefactory-admin pagefactory-module"><div id="jw-pagefactory-page-tools" class="clearfix jw-pagefactory-page-tools"></div><div class="jw-pagefactory-sidebar-and-builder"><div id="jw-pagefactory-section-lib" class="clearfix jw-pagefactory-section-lib"></div><div id="container"></div></div></div>';

			$output .= '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="">';
			$output .= '<input type="hidden" id="jwpagefactory_module_id" value="' . $id . '">';
			$output .= '<script type="text/javascript" src="' . JURI::base(true) . '/components/com_jwpagefactory/assets/js/engine.js"></script>';

			return $output;
		} else {
			$output .= '<div class="alert alert-info">Please save this module to activate Page Factory</div>';
		}

		$output .= '<style>#general .control-group .control-label {display: none;} #general .control-group .controls {margin-left: 0;}</style>';

		return $output;
	}

	private function moduleParams($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('params')));
		$query->from($db->quoteName('#__modules'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}

	private function pageData($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__jwpagefactory'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote('mod_jwpagefactory'));
		$query->where($db->quoteName('extension_view') . ' = ' . $db->quote('module'));
		$query->where($db->quoteName('view_id') . ' = ' . $db->quote($id));
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}

	private function insertData($id, $title, $content)
	{
		$user = JFactory::getUser();
		$date = JFactory::getDate();
		$db = JFactory::getDbo();
		$page = new stdClass();
		$page->title = $title;
		$page->text = $content;
		$page->extension = 'mod_jwpagefactory';
		$page->extension_view = 'module';
		$page->view_id = $id;
		$page->published = 1;
		$page->created_by = (int)$user->id;
		$page->created_on = $date->toSql();
		$page->language = '*';
		$page->access = 1;
		$page->active = 1;

		$db->insertObject('#__jwpagefactory', $page);
		return $db->insertid();
	}

	function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	private function loadPageFactoryLanguage()
	{
		$lang = JFactory::getLanguage();
		$lang->load('com_jwpagefactory', JPATH_ADMINISTRATOR, $lang->getName(), true);
		$lang->load('tpl_' . $this->getTemplate(), JPATH_SITE, $lang->getName(), true);
		require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/helpers/language.php';
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
}
