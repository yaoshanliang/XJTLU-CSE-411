<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

KT::import('admin:/views/views');

class KomentoViewComments extends KomentoAdminView
{
	public function display($tpl = null)
	{
		$layout = $this->getLayout() ? $this->getLayout() : 'default';

		$access = $layout;

		if ($layout == 'default') {
			$access = 'comments';
		}

		if ($layout == 'pending') {
			$access = 'pendings';
		}

		// Check for access
		$this->checkAccess('komento.manage.' . $access);

		$this->heading('COM_KOMENTO_HEADING_COMMENTS_' . strtoupper($layout));

		$this->registerToolbar($layout);

		$filter_publish = $this->app->getUserStateFromRequest('com_komento.comments.filter_publish', 'filter_publish', '*', 'string');
		$filter_component = $this->app->getUserStateFromRequest('com_komento.comments.filter_component', 'filter_component', '*', 'string');
		$search = $this->app->getUserStateFromRequest('com_komento.comments.search', 'search', '', 'string');
		$search = trim(JString::strtolower($search));
		$order = $this->app->getUserStateFromRequest('com_komento.comments.filter_order', 'filter_order', 'created', 'string');
		$orderDirection = $this->app->getUserStateFromRequest('com_komento.comments.filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');

		$state = $this->getPublishState($filter_publish);
		$parentId = $this->input->get('parentid', 0);

		// Construct options
		$options['no_search'] = $this->input->getInt('nosearch', 0);
		$options['parent_id'] = $parentId;
		$options['published'] = $filter_publish;

		$return = base64_encode('index.php?option=com_komento&view=comments');

		if ($layout == 'pending') {
			$options['published'] = KOMENTO_COMMENT_MODERATE;
			$options['no_tree'] = 1;
			$state = false;
			$return = base64_encode('index.php?option=com_komento&view=comments&layout=pending');
		}

		if ($layout == 'reports') {
			$model = KT::model('reports');
			$comments = $model->getItems();
			$return = base64_encode('index.php?option=com_komento&view=comments&layout=reports');
		} else {
			// Get data from the model
			$model = KT::model('comments');
			$comments = $model->getItems($options);
		}

		$limit = $model->getState('limit');
		$pagination = $model->getPagination();

		$comments = KT::formatter('comment', $comments, false);

		if ($search) {
			$parentId = 0;
		}

		$parent	= '';

		if ($parentId) {
			$parent = KT::comment($parentId);
			$parent = KT::formatter('comment', $parent, false);
		}

		$this->set('return', $return);
		$this->set('limit', $limit);
		$this->set('comments', $comments);
		$this->set('pagination', $pagination);
		$this->set('parent', $parent);
		$this->set('parentid', $parentId);
		$this->set('state', $state);
		$this->set('search', $search);
		$this->set('order', $order);
		$this->set('layout', $this->getLayout());
		$this->set('orderDirection', $orderDirection);
		$this->set('component', $this->getComponentState($filter_component));

		parent::display('comments/default');
	}

	public function form()
	{
		// Check for access
		$this->checkAccess('komento.manage.comments');

		$layout = $this->getLayout() ? $this->getLayout() : 'default';

		$id = $this->input->get('id', '');
		$comment = KT::comment($id);

		JToolBarHelper::back();
		JToolBarHelper::divider();
		JToolBarHelper::title(JText::_('COM_KOMENTO_EDITING_COMMENT'), 'comments');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();

		if ($comment->published != KOMENTO_COMMENT_SPAM) {
			JToolBarHelper::custom('markCommentSpam', 'unpublish', '', JText::_('COM_KOMENTO_MARK_SPAM'), false);
		}
		
		JToolBarHelper::divider();
		JToolBarHelper::trash('deleteCommentSpam', JText::_('COM_KOMENTO_DELETE'), false);
		
		//Load pane behavior
		jimport('joomla.html.pane');

		$components	= array();
		$availableComponents = KT::components()->getAvailableComponents();

		// @task: Translate each component with human readable name.
		foreach ($availableComponents as $item) {
			$components[] = JHTML::_('select.option', $item, KT::loadApplication($item)->getComponentName());
		}

		$return = base64_encode('index.php?option=com_komento&view=comments');

		if ($comment->isSpam()) {
			$return = base64_encode('index.php?option=com_komento&view=comments&layout=spamlist');
		}

		if ($comment->isPending()) {
			$return = base64_encode('index.php?option=com_komento&view=comments&layout=pending');
		}

		if ($comment->isReport()) {
			$return = base64_encode('index.php?option=com_komento&view=comments&layout=reports');
		}

		$this->set('comment', $comment);
		$this->set('components', $components);
		$this->set('return', $return);

		parent::display('comments/form');
	}

	/**
	 * Renders a list of comments that are flagged as spam
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function spamlist()
	{
		// Check for access
		$this->checkAccess('komento.manage.spamlist');

		$this->heading('COM_KOMENTO_HEADING_COMMENTS_SPAMLIST');

		JToolBarHelper::custom('notspam', 'publish', '', JText::_('COM_KOMENTO_NOT_SPAM'));
		
		JToolBarHelper::divider();
		JToolBarHelper::trash('delete', JText::_('COM_KOMENTO_DELETE'));

		$filter_component = $this->app->getUserStateFromRequest('com_komento.spamlist.filter_component', 'filter_component', '*', 'string');
		$search = $this->app->getUserStateFromRequest('com_komento.spamlist.search', 'search', '', 'string');
		$search = trim(JString::strtolower($search));
		$order = $this->app->getUserStateFromRequest('com_komento.spamlist.filter_order', 'filter_order', 'created', 'string');
		$orderDirection = $this->app->getUserStateFromRequest('com_komento.spamlist.filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');

		$options['published'] = KOMENTO_COMMENT_SPAM;

		// Get data from the model
		$model = KT::model('comments');
		$comments = $model->getItems($options);
		$pagination = $model->getPagination();
		$comments = KT::formatter('comment', $comments, false);
		$limit = $model->getState('limit');
		$return = base64_encode('index.php?option=com_komento&view=comments&layout=spamlist');

		$this->set('layout', $this->getLayout());
		$this->set('limit', $limit);
		$this->set('comments', $comments);
		$this->set('pagination', $pagination);
		$this->set('search', $search);
		$this->set('order', $order);
		$this->set('return', $return);
		$this->set('orderDirection', $orderDirection);
		$this->set('component', $this->getComponentState($filter_component));

		parent::display('comments/spamlist');
	}

	public function registerToolbar($layout = 'default')
	{
		$parentId = $this->input->get('parentid', 0);

		if ($parentId) {
			$parent = KT::table('comments');
			$parent->load($parentId);
			JToolBarHelper::title(JText::_('COM_KOMENTO_COMMENTS_TITLE_CHILD_OF') . $parentId, 'comments');
			JToolBarHelper::back(JText::_('COM_KOMENTO_BACK'), 'index.php?option=com_komento&view=comments&parentid=' . $parent->parent_id);
		} else {
			JToolBarHelper::title(JText::_('COM_KOMENTO_COMMENTS_TITLE'), 'comments');
		}

		if ($layout == 'default') {
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::divider();
			JToolBarHelper::custom('feature', 'featured', '', JText::_('COM_KOMENTO_FEATURE'));
			JToolBarHelper::custom('unfeature', 'star-empty', '', JText::_('COM_KOMENTO_UNFEATURE'));
			JToolBarHelper::divider();
		}

		if ($layout == 'reports') {
			JToolBarHelper::title(JText::_('COM_KOMENTO_REPORTS'), 'reports');
			JToolBarHelper::divider();
			JToolBarHelper::custom('clearReports', 'wand', '', JText::_('COM_KOMENTO_CLEAR_REPORTS'));
			JToolBarHelper::divider();
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
		}

		if ($layout == 'pending') {
			JToolBarHelper::title(JText::_('COM_KOMENTO_PENDING'), 'pending');
			JToolBarHelper::custom('publish', 'publish', '', JText::_('COM_KOMENTO_APPROVE'));
			JToolBarHelper::custom('unpublish', 'unpublish', '', JText::_('COM_KOMENTO_REJECT'));
		}

		JToolBarHelper::custom('markSpam', 'unpublish', '', JText::_('COM_KOMENTO_MARK_SPAM'));
		JToolBarHelper::divider();
		JToolBarHelper::trash('delete', JText::_('COM_KOMENTO_DELETE'));
	}

	public function getPublishState($filter_publish = '*')
	{
		$publish[] = JHTML::_('select.option', '*', JText::_('COM_KOMENTO_ALL_STATUS'));
		$publish[] = JHTML::_('select.option', '1', JText::_('COM_KOMENTO_PUBLISHED'));
		$publish[] = JHTML::_('select.option', '0', JText::_('COM_KOMENTO_UNPUBLISHED'));

		return JHTML::_('select.genericlist', $publish, 'filter_publish', 'class="o-form-control" onchange="submitform();" data-table-grid-filter', 'value', 'text', $filter_publish);
	}

	public function getComponentState($filter_component = '*')
	{
		$availableComponents = KT::components()->getAvailableComponents();

		$component_state[] = JHTML::_('select.option', '*', JText::_('COM_KOMENTO_ALL_COMPONENTS'));

		foreach ($availableComponents as $component) {
			$component_state[] = JHTML::_('select.option', $component, KT::loadApplication($component)->getComponentName());
		}

		return JHTML::_('select.genericlist', $component_state, 'filter_component', 'class="o-form-control" onchange="submitform();" data-table-grid-filter', 'value', 'text', $filter_component);
	}
}
