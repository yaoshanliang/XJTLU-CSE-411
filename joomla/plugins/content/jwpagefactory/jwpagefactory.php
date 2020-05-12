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

$jwpf_helper_path = JPATH_ADMINISTRATOR . '/components/com_jwpagefactory/helpers/jwpagefactory.php';
if (!file_exists($jwpf_helper_path)) {
	return;
}
if (!class_exists('JwpagefactoryHelper')) {
	require_once $jwpf_helper_path;
}

class PlgContentJwpagefactory extends JPlugin
{
	protected $autoloadLanguage = true;
	protected $jwpagefactory_content = '';
	protected $jwpagefactory_active = 0;
	protected $isJwpagefactoryEnabled = 0;

	public function __construct(&$subject, $config)
	{
		$this->isJwpagefactoryEnabled = $this->isJwpagefactoryEnabled();

		// make sure that administrator language files are loaded
		if ($this->isJwpagefactoryEnabled) {
			$language = JFactory::getLanguage();
			$language->load('com_jwpagefactory', JPATH_ROOT . '/components/com_jwpagefactory', null, true);
			$language->load('com_jwpagefactory', JPATH_ROOT . '/administrator/components/com_jwpagefactory', null, true);
		}

		parent::__construct($subject, $config);
	}

	// Common
	public static function __context()
	{
		$context = array(
			'option' => 'com_content',
			'view' => 'article',
			'id_alias' => 'id'
		);
		return $context;
	}

	public function onContentAfterSave($context, $article, $isNew)
	{
		if (!$this->isJwpagefactoryEnabled) return;
		$input = JFactory::getApplication()->input;
		$option = $input->get('option', '', 'STRING');
		$view = 'article';
		$form = $input->post->get('jform', array(), 'ARRAY');
		$jwpagefactory_active = (isset($form['attribs']['jwpagefactory_active']) && $form['attribs']['jwpagefactory_active']) ? $form['attribs']['jwpagefactory_active'] : 0;
		$jwpagefactory_content = (isset($form['attribs']['jwpagefactory_content']) && $form['attribs']['jwpagefactory_content']) ? $form['attribs']['jwpagefactory_content'] : '[]';
		if (!$jwpagefactory_content) return;
		if ($context == 'com_content.article') {
			$article_state = $article->state;
			if (!$jwpagefactory_active) {
				$article_state = 0;
			}
			$values = array(
				'title' => $article->title,
				'text' => $jwpagefactory_content,
				'option' => $option,
				'view' => $view,
				'id' => $article->id,
				'active' => $jwpagefactory_active,
				'published' => $article_state,
				'catid' => $article->catid,
				'created_on' => $article->created,
				'created_by' => $article->created_by,
				'modified' => $article->modified,
				'modified_by' => $article->modified_by,
				'access' => $article->access,
				'language' => '*',
				'action' => 'apply'
			);
			if ($article->state == 2) {
				$values['published'] = 1;
			}
			JwpagefactoryHelper::onAfterIntegrationSave($values);
		}
	}

	public function onContentPrepare($context, $article, $params, $page)
	{
		$input = JFactory::getApplication()->input;
		$option = $input->get('option', '', 'STRING');
		$view = $input->get('view', '', 'STRING');
		$task = $input->get('task', '', 'STRING');
		if (!isset($article->id) || !(int)$article->id) {
			return true;
		}
		if ($this->isJwpagefactoryEnabled) {
			if (($option == 'com_content') && ($view == 'article')) {
				$article->text = JwpagefactoryHelper::onIntegrationPrepareContent($article->text, $option, $view, $article->id);
			}
			if (($option == 'com_j2store') && ($view == 'products') && ($task == 'view') && ($context == 'com_content.article.productlist')) {
				$article->text = JwpagefactoryHelper::onIntegrationPrepareContent($article->text, 'com_content', 'article', $article->id);
			}
		}
	}

	public function onContentAfterDelete($context, $data)
	{
		if ($this->isJwpagefactoryEnabled) {
			$input = JFactory::getApplication()->input;
			$option = $input->get('option', '', 'STRING');
			$task = $input->get('task', '', 'STRING');
			if ($option == 'com_content' && $context == 'com_content.article') {
				$values = array(
					'option' => $option,
					'view' => 'article',
					'id' => $data->id,
					'action' => 'delete'
				);
				JwpagefactoryHelper::onAfterIntegrationSave($values);
			}
		}
	}

	public function onContentAfterTitle($context, $article, $params, $limitstart)
	{

		$input = JFactory::getApplication()->input;
		$option = $input->get('option', '', 'STRING');
		$view = $input->get('view', '', 'STRING');
		$task = $input->get('task', '', 'STRING');
		if (!isset($article->id) || !(int)$article->id) {
			return true;
		}
		if ($this->isJwpagefactoryEnabled) {
			if ($option == 'com_content' && $view == 'article' && $params->get('access-edit')) {
				$jwpfEditLink = $this->displayJWPFEditLink($article, $params);
				if ($jwpfEditLink) {
					return $jwpfEditLink;
				}
			}
		}

		return;
	}

	public function onContentChangeState($context, $pks, $value)
	{
		if ($this->isJwpagefactoryEnabled) {
			$input = JFactory::getApplication()->input;
			$option = $input->get('option', '', 'STRING');
			$view = $input->get('view', '', 'STRING');
			$task = $input->get('task', '', 'STRING');
			if ($option == 'com_content' && $context == 'com_content.article') {
				$actions = array(0, 1, -2);
				if (!in_array($value, $actions)) return;
				foreach ($pks as $id) {
					$values = array(
						'option' => $option,
						'view' => 'article',
						'id' => $id,
						'published' => $value,
						'action' => 'stateChange'
					);
					JwpagefactoryHelper::onAfterIntegrationSave($values);
				}
			}
		}
	}

	private function isJwpagefactoryEnabled()
	{
		$db = JFactory::getDbo();
		$db->setQuery("SELECT enabled FROM #__extensions WHERE element = 'com_jwpagefactory' AND type = 'component'");
		return $is_enabled = $db->loadResult();
	}

	private function displayJWPFEditLink($article, $params)
	{

		$user = JFactory::getUser();

		// Ignore if in a popup window.
		if ($params && $params->get('popup')) return;

		// Ignore if the state is negative (trashed).
		if ($article->state < 0) return;

		$item = JwpagefactoryHelper::getPageContent('com_content', 'article', $article->id);

		if (!$item || !$item->id) return;

		if (property_exists($article, 'checked_out')
			&& property_exists($article, 'checked_out_time')
			&& $article->checked_out > 0
			&& $article->checked_out != $user->get('id')) {

			return '<a href="#"><span class="fa fa-lock"></span> Checked out</a>';
		}

		$app = JApplication::getInstance('site');
		$router = $app->getRouter();

		// Get item language code
		$lang_code = (isset($item->language) && $item->language && explode('-', $item->language)[0]) ? explode('-', $item->language)[0] : '';
		// check language filter plugin is enable or not
		$enable_lang_filter = JPluginHelper::getPlugin('system', 'languagefilter');
		// get joomla config
		$conf = JFactory::getConfig();

		$front_link = 'index.php?option=com_jwpagefactory&view=form&tmpl=component&layout=edit&id=' . $item->id;
		$sefURI = str_replace('/administrator', '', $router->build($front_link));
		if ($lang_code && $lang_code !== '*' && $enable_lang_filter && $conf->get('sef')) {
			$sefURI = str_replace('/index.php/', '/index.php/' . $lang_code . '/', $sefURI);
		} elseif ($lang_code && $lang_code !== '*') {
			$sefURI = $sefURI . '&lang=' . $lang_code;
		}

		return '<a target="_blank" href="' . $sefURI . '"><span class="fa fa-pencil-square-o"></span> ' . JText::_('PLG_CONTENT_JWPAGEFACTORY_EDIT_WITH_PAGEFACTORY') . '</a>';
	}

}