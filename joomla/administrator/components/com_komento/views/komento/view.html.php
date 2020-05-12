<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

KT::import('admin:/views/views');

class KomentoViewKomento extends KomentoAdminView
{
	public function display($tpl = null)
	{
		$model = KT::model('Comments');
		
		if ($this->my->authorise('core.admin', 'com_komento')) {
			JToolBarHelper::preferences('com_komento');
		}

		$comments = '';
		$options = array(
						'threaded' => 0,
						'sort' => 'latest',
						'limit' => 10
					);

		$comments = $model->getComments('all', 'all', $options);

		if ($comments) {
			$comments = KT::formatter('comment', $comments);
		}

		// Set Options
		$optionsPending['published'] = 2;
		$optionsPending['no_tree'] = 1;
		$optionsPending['no_child'] = 1;

		$pendings = $model->getItems($optionsPending);

		if ($pendings) {
			$pendings = KT::formatter('comment', $pendings);
		}

		$model = KT::model('Comments');
		$totalComments = $model->getTotalComment();
		$totalPending = $model->getCount('all', 'all', array('published' => 2));

		$reportsModel = KT::model('Reports');
		$totalReports = $reportsModel->getTotal();

		$subscriptionModel = KT::model('Subscription');
		$totalSubscribers = $subscriptionModel->getTotalSubscribers();

		$currentVersion = KT::komentoVersion();

		$this->set('currentVersion', $currentVersion);
		$this->set('totalPending', $totalPending);
		$this->set('totalReports', $totalReports);
		$this->set('totalSubscribers', $totalSubscribers);
		$this->set('comments', $comments);
		$this->set('pendings', $pendings);
		$this->set('totalComments', $totalComments);

		parent::display('komento/default');
	}
}
