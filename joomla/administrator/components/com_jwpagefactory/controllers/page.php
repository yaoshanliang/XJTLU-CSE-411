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

jimport('joomla.filesystem.file');

use Joomla\Utilities\ArrayHelper;

class JwpagefactoryControllerPage extends JControllerForm
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	protected function allowAdd($data = array())
	{
		$categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow = null;
		if ($categoryId) {
			// If the category has been passed in the data or URL check it.
			$allow = JFactory::getUser()->authorise('core.create', 'com_jwpagefactory.category.' . $categoryId);
		}
		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		return $allow;
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int)isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();

		// Zero record (id:0), return component edit permission by calling parent controller method
		if (!$recordId) {
			return parent::allowEdit($data, $key);
		}

		// Check edit on the record asset (explicit or inherited)
		if ($user->authorise('core.edit', 'com_jwpagefactory.page.' . $recordId)) {
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_jwpagefactory.page.' . $recordId)) {
			// Existing record already has an owner, get it
			$record = $this->getModel()->getItem($recordId);
			if (empty($record)) {
				return false;
			}

			// Grant if current user is owner of the record
			return $user->id == $record->created_by;
		}

		return false;
	}

	public function save($key = null, $urlVar = null)
	{

		$output = array();
		// Check for request forgeries.
		$output['status'] = false;
		$output['message'] = JText::_('JINVALID_TOKEN');
		JSession::checkToken() or die(json_encode($output));

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$data = $this->input->post->get('jform', array(), 'array');
		$task = $this->getTask();
		$context = 'com_jwpagefactory.edit.page';
		$recordId = $data['id'];

		//Authorized
		if (empty($recordId)) {
			$authorised = $user->authorise('core.create', 'com_jwpagefactory') || (count((array)$user->getAuthorisedCategories('com_jwpagefactory', 'core.create')));
		} else {
			$authorised = $user->authorise('core.edit', 'com_jwpagefactory') || $user->authorise('core.edit', 'com_jwpagefactory.page.' . $recordId) || $user->authorise('core.edit', 'com_jwpagefactory.page.' . $recordId) || ($user->authorise('core.edit.own', 'com_jwpagefactory.page.' . $recordId) && $data['created_by'] == $user->id);
		}

		if ($authorised !== true) {
			$output['status'] = false;
			$output['message'] = JText::_('JERROR_ALERTNOAUTHOR');
			echo json_encode($output);
			die();
		}

		$output['status'] = true;

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy') {
			// Check-in the original row.
			if ($model->checkin($data['id']) === false) {
				// Check-in failed, go back to the item and display a notice.
				$output['status'] = false;
				$output['message'] = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
				echo json_encode($output);
				die();
			}

			// Reset the ID and then treat the request as for Apply.
			$output['title'] = $model->pageGenerateNewTitle($data['title']);
			$data['id'] = 0;
			$task = 'apply';
		}


		// Validate the posted data.
		// This post is made up of two forms, one for the item and one for params.
		$form = $model->getForm($data);

		if (!$form) {
			$output['status'] = false;
			$output['message'] = $model->getError();
			$output['redirect'] = false;
			echo json_encode($output);
			die();
		}

		$data = $model->validate($form, $data);

		// Check for validation errors.
		if ($data === false) {
			// Get the validation messages.
			$errors = $model->getErrors();

			$output['status'] = false;
			$output['message'] = '';

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count((array)$errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$output['message'] .= $errors[$i]->getMessage();
				} else {
					$output['message'] .= $errors[$i];
				}
			}

			// Save the data in the session.
			$app->setUserState('com_jwpagefactory.edit.page.data', $data);

			// Redirect back to the edit screen.
			$output['redirect'] = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId);
			echo json_encode($output);
			die();
		}

		// Attempt to save the data.
		if (!$model->save($data)) {

			// Save the data in the session.
			$app->setUserState('com_jwpagefactory.edit.page.data', $data);

			// Redirect back to the edit screen.
			$output['status'] = false;
			$output['message'] = JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError());
			$output['redirect'] = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId);
			echo json_encode($output);
			die();
		}

		// Save succeeded, check-in the row.
		if ($model->checkin($data['id']) === false) {

			// Check-in failed, go back to the row and display a notice.
			$output['status'] = false;
			$output['message'] = JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
			$output['redirect'] = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId);
			echo json_encode($output);
			die();
		}

		$output['status'] = true;
		$output['message'] = JText::_('COM_JWPAGEFACTORY_PAGE_SAVE_SUCCESS');

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task) {
			case 'apply':
				// Set the row data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState('com_jwpagefactory.edit.page.data', null);

				// Delete generated CSS file
				$css_folder_path = JPATH_ROOT . '/media/com_jwpagefactory/css';
				$css_file_path = $css_folder_path . '/page-' . $recordId . '.css';
				if (file_exists($css_file_path)) {
					JFile::delete($css_file_path);
				}

				// Redirect back to the edit screen.
				$output['redirect'] = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId);
				$siteApp = JApplication::getInstance('site');
				$siteRouter = $siteApp->getRouter();
				$Itemid = JwpagefactoryHelper::getMenuId($recordId);
				// Get item language code
				$lang_code = (isset($data['language']) && $data['language'] && explode('-', $data['language'])[0]) ? explode('-', $data['language'])[0] : '';

				// Preview URL
				$preview_link = 'index.php?option=com_jwpagefactory&view=page&id=' . $recordId . $Itemid;
				$front_link = 'index.php?option=com_jwpagefactory&view=form&tmpl=component&layout=edit&id=' . $recordId . $Itemid;
				if ($lang_code && $lang_code !== '*') {
					$preview_link = $preview_link . '&lang=' . $lang_code;
					$front_link = $front_link . '&lang=' . $lang_code;
				}

				$output['preview_url'] = JwpagefactoryHelper::generateSefUrl($siteRouter, $preview_link);
				$output['frontend_editor_url'] = JwpagefactoryHelper::generateSefUrl($siteRouter, $front_link);

				$output['id'] = $recordId;
				break;

			default:
				// Clear the row id and data in the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState('com_jwpagefactory.edit.page.data', null);

				// Redirect to the list screen.
				$output['redirect'] = JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend(), false);
				break;
		}

		if (isset($output['id']) && $output['id']) {
			$css_file_path = JPATH_ROOT . "/media/jwpagefactory/css/page-{$output['id']}.css";
			if (file_exists($css_file_path)) {
				unlink($css_file_path);
			}
		}

		echo json_encode($output);
		die();
	}

	public function getMySections()
	{
		$model = $this->getModel();
		die($model->getMySections());
	}

	public function deleteSection()
	{
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$input = $app->input;

		$id = $input->get('id', '', 'INT');

		die($model->deleteSection($id));
	}

	public function saveSection()
	{
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$input = $app->input;

		$title = htmlspecialchars($input->get('title', '', 'STRING'));
		$section = $input->get('section', '', 'RAW');

		if ($title && $section) {
			$section_id = $model->saveSection($title, $section);
			echo $section_id;
			die();
		} else {
			die('Failed');
		}
	}

	public function getMyAddons()
	{
		$model = $this->getModel();
		die($model->getMyAddons());
	}

	public function saveAddon()
	{
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$input = $app->input;

		$title = htmlspecialchars($input->get('title', '', 'STRING'));
		$addon = $input->get('addon', '', 'RAW');

		if ($title && $addon) {
			$addon_id = $model->saveAddon($title, $addon);
			echo $addon_id;
			die();
		} else {
			die('Failed');
		}
	}

	public function deleteAddon()
	{
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$input = $app->input;

		$id = $input->get('id', '', 'INT');

		die($model->deleteAddon($id));
	}

	public function createNew()
	{
		$pageId = 0;
		$model = $this->getModel('Page');
		$output = array();
		$output['status'] = false;
		$app = JFactory::getApplication();
		$input = $app->input;

		$user = JFactory::getUser();
		$authorised = $user->authorise('core.create', 'com_jwpagefactory');

		if (!$authorised) {
			$output['message'] = JText::_('JERROR_ALERTNOAUTHOR');
			die(json_encode($output));
		}

		$title = trim(htmlspecialchars($input->post->get('title', '', 'STRING')));
		$extension = htmlspecialchars($input->post->get('extension', '', 'STRING'));
		$extension_view = htmlspecialchars($input->post->get('extension_view', '', 'STRING'));
		$view_id = $input->post->get('view_id', 0, 'INT');

		if ($view_id && $title) {
			$id = $model->createBrandNewPage($title, $extension, $extension_view, $view_id);
			$pageId = $id;
			$appSite = JApplication::getInstance('site');
			$router = $appSite->getRouter();
			$front_link = 'index.php?option=com_jwpagefactory&view=form&tmpl=component&layout=edit&extension=' . $extension . '&extension_view=' . $extension_view . '&id=' . $pageId;
			$sefURI = str_replace('/administrator', '', $router->build($front_link));
			$output['status'] = true;
			$output['url'] = $sefURI;
			die(json_encode($output));
		}

		die(json_encode($output));
	}

	public function module_save()
	{
		$pageId = 0;
		$model = $this->getModel('Page');
		$output = array();
		$output['status'] = false;
		$app = JFactory::getApplication();
		$input = $app->input;

		$user = JFactory::getUser();
		$authorised = $user->authorise('core.create', 'com_jwpagefactory');

		if (!$authorised) {
			$output['message'] = JText::_('JERROR_ALERTNOAUTHOR');
			die(json_encode($output));
		}

		$id = (int)$input->post->get('id', '', 'INT');
		$title = trim(htmlspecialchars($input->post->get('title', '', 'STRING')));
		$content = $input->post->get('content', '[]', 'RAW');

		if ($id && $title) {
			if ($view_id = $model->get_module_page_data($id)) {
				$model->update_module_data($view_id, $id, $title, $content);
			} else {
				$model->save_module_data($id, $title, $content);
			}
			$output['status'] = true;
			die(json_encode($output));
		}

		$output['message'] = 'Error';
		die(json_encode($output));
	}
}
