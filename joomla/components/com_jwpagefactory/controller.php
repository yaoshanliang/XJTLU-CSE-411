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

use Joomla\CMS\Component\ComponentHelper;

//import Joomla controller library
jimport('joomla.application.component.controller');

class JwpagefactoryController extends JControllerLegacy
{

	public function __construct(array $config)
	{
		parent::__construct($config);

		// make sure that administrator language files are loaded
		$language = JFactory::getLanguage();
		$language->load('com_jwpagefactory', JPATH_ROOT . '/administrator/components/com_jwpagefactory', null, true);
	}

	function display($cachable = false, $urlparams = false)
	{
		$apps = JFactory::getApplication();
		$viewStatus = false;

		$id = $this->input->getInt('id');
		$vName = $this->input->getCmd('view');

		if ($vName == 'page') {
			$viewStatus = true;
		} else if ($vName == 'form') {
			$viewStatus = true;
		} else if ($vName == 'ajax') {
			$viewStatus = true;
		} else if ($vName == 'media') {
			$viewStatus = true;
		} else if ($vName == 'pages') {
			$viewStatus = true;
		} else if ($vName == 'menus') {
			$viewStatus = true;
		}

		if (!$viewStatus) {
			return JError::raiseError(404, 'Page not found');
		}

		$this->input->set('view', $vName);

		if ($vName == 'page') {
			$cachable = true;
		}

		$safeurlparams = array(
			'catid' => 'INT',
			'id' => 'INT',
			'cid' => 'ARRAY',
			'return' => 'BASE64',
			'print' => 'BOOLEAN',
			'lang' => 'CMD',
			'Itemid' => 'INT'
		);

		$user = JFactory::getUser();
		$isIgnoreView = ($this->input->getMethod() === 'POST' && (($vName === 'form' && ($this->input->get('layout') !== 'edit') || $this->input->get('layout') !== 'edit-iframe')));
		if ($user->get('id') || $isIgnoreView) {
			$cachable = false;
		}

		if ($vName === 'page') {
			$model = $this->getModel($vName);
			$model->hit();
		}

		parent::display($cachable, $safeurlparams);
	}

	public function export()
	{
		// check have access
		$user = JFactory::getUser();
		$authorised = $user->authorise('core.edit', 'com_jwpagefactory');
		if (!$authorised) {
			die('Restricted Access');
		}

		$input = JFactory::getApplication()->input;
		$template = $input->get('template', '[]', 'RAW');
		$filename = 'template' . rand(10000, 99999);

		if ($template !== '[]') {
			$template = json_decode($template);
			foreach ($template as &$row) {
				foreach ($row->columns as &$column) {
					foreach ($column->addons as &$addon) {
						if (isset($addon->type) && $addon->type == 'jw_row') {
							foreach ($addon->columns as &$column) {
								foreach ($column->addons as &$addon) {
									if (isset($addon->htmlContent)) {
										unset($addon->htmlContent);
									}
									if (isset($addon->assets)) {
										unset($addon->assets);
									}
								}
							}
						} else {
							if (isset($addon->htmlContent)) {
								unset($addon->htmlContent);
							}
							if (isset($addon->assets)) {
								unset($addon->assets);
							}
						}
					}
				}
			}
			$template = json_encode($template);
		}

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=$filename.json");
		header("Content-Type: application/json");
		header("Content-Transfer-Encoding: binary ");

		echo $template;
		die();
	}

	//Ajax
	public function ajax()
	{

		$app = JFactory::getApplication();
		$input = $app->input;
		$format = strtolower($input->getWord('format'));
		$results = null;
		$addon = $input->get('addon', '', 'STRING');

		if ($addon) {

			$function = 'jw_' . $addon . '_get_ajax';
			$addon_class = 'JwpagefactoryAddon' . ucfirst($addon);
			$method = $input->get('method', 'get', 'STRING');

			require_once JPATH_BASE . '/components/com_jwpagefactory/parser/addon-parser.php';

			$core_path = JPATH_BASE . '/components/com_jwpagefactory/addons/' . $input->get('addon') . '/site.php';
			$template_path = JPATH_BASE . '/templates/' . $this->getTemplateName() . '/jwpagefactory/addons/' . $input->get('addon') . '/site.php';

			if (file_exists($template_path)) {
				require_once $template_path;
			} else {
				require_once $core_path;
			}

			if (class_exists($addon_class)) {

				if (method_exists($addon_class, $method . 'Ajax')) {
					try {
						$results = call_user_func($addon_class . '::' . $method . 'Ajax');
					} catch (Exception $e) {
						$results = $e;
					}
				} else {
					$results = new LogicException(JText::sprintf('COM_AJAX_METHOD_NOT_EXISTS', $method . 'Ajax'), 404);
				}

			} else {
				if (function_exists($function)) {
					try {
						$results = call_user_func($function);
					} catch (Exception $e) {
						$results = $e;
					}
				} else {
					$results = new LogicException(JText::sprintf('Function %s does not exist', $function), 404);
				}
			}
		}

		echo new JResponseJson($results, null, false, $input->get('ignoreMessages', true, 'bool'));
		die;
	}

	private function getTemplateName()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('template')));
		$query->from($db->quoteName('#__template_styles'));
		$query->where($db->quoteName('client_id') . ' = 0');
		$query->where($db->quoteName('home') . ' = 1');
		$db->setQuery($query);

		return $db->loadObject()->template;
	}
}
