<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2012 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

KT::import('admin:/includes/model');

class KomentoModelUploads extends KomentoModel
{
	public function __construct()
	{
		parent::__construct('uploads');
	}

	public function getAttachments($uid)
	{
		$sql = KT::sql();

		$sql->select( '#__komento_uploads' )
			->where( 'uid', $uid )
			->order( 'created' );

		$result = $sql->loadObjectList();

		$attachments = array();

		foreach ($result as $row) {
			$table = KT::table('Uploads');
			$table->bind( $row );

			$attachments[] = $table;
		}

		return $attachments;
	}

	public function loadBatchAttachments($uids)
	{
		$db = KT::db();


		$ids = implode(',', $uids);

		$query = "select * from " . $db->nameQuote('#__komento_uploads');
		$query .= " where " . $db->nameQuote('uid') . ' IN (' . $ids . ')';

		$db->setQuery($query);
		$result = $db->loadObjectList();

		$attachments = array();

		foreach ($result as $row) {
			$table = KT::table('Uploads');
			$table->bind( $row );

			$attachments[] = $table;
		}

		return $attachments;
	}
}
