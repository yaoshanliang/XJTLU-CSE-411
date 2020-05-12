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
defined ('_JEXEC') or die ('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

if(!class_exists('JwpagefactoryHelperSite')) {
	require_once JPATH_ROOT . '/components/com_jwpagefactory/helpers/helper.php';
}

class JwpagefactoryViewPage extends JViewLegacy {

	protected $item;

	function display( $tpl = null ) {

		$this->item = $this->get('Item');

		if (count($errors = (array) $this->get('Errors'))) {
			JLog::add(implode('<br />',$errors),JLog::WARNING,'jerror');
			return false;
		}

		if ($this->item->access_view == false) {
			JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return;
		}

		$this->_prepareDocument($this->item->title);
		parent::display($tpl);
	}

	protected function _prepareDocument($title = '') {
		$config = JFactory::getConfig();
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$menus = $app->getMenu();
		$menu = $menus->getActive();
		$config_params = JComponentHelper::getParams('com_jwpagefactory');
		$disable_og = $config_params->get('disable_og',0);
		$disable_tc = $config_params->get('disable_tc',0);

		//Title
		if (isset($meta['title']) && $meta['title']) {
			$title = $meta['title'];
		} else {
			if ($menu) {
				if($menu->params->get('page_title', '')) {
					$title = $menu->params->get('page_title');
				} else {
					$title = $menu->title;
				}
			}
		}

		//Include Site title
		$sitetitle = $title;
		if($config->get('sitename_pagetitles')==2) {
			$sitetitle = JText::sprintf('JPAGETITLE', $sitetitle, $app->get('sitename'));
		} elseif ($config->get('sitename_pagetitles')==1) {
			$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), $sitetitle);
		}
		$doc->setTitle($sitetitle);

		$og_title = $this->item->og_title;
		
		if(!$disable_og) {
			if ( $og_title) {
				$this->document->addCustomTag('<meta property="og:title" content="'.$og_title.'" />');
			} else {
				$doc->addCustomTag('<meta property="og:title" content="' . $title . '" />');
			}

			$this->document->addCustomTag('<meta property="og:type" content="website" />');
			$this->document->addCustomTag('<meta property="og:url" content="'.JURI::current().'" />');

			if( $fb_app_id = $config_params->get('fb_app_id', '') ) {
				$this->document->addCustomTag('<meta property="fb:app_id" content="' . $fb_app_id .'" />');
			}
			
			if ($config->get('sitename', '')) {
				$this->document->addCustomTag('<meta property="og:site_name" content="'. htmlspecialchars($config->get('sitename', '')) .'" />');
			}

		}

		$og_image = $this->item->og_image;
		if (!$disable_og && $og_image) {
			$this->document->addCustomTag('<meta property="og:image" content="'.JURI::root().$og_image.'" />');
			$this->document->addCustomTag('<meta property="og:image:width" content="1200" />');
			$this->document->addCustomTag('<meta property="og:image:height" content="630" />');
		}

		$og_description = $this->item->og_description;
		if (!$disable_og && $og_description) {
			$this->document->addCustomTag('<meta property="og:description" content="'.$og_description.'" />');
		}

		if (!$disable_tc) {
			// Twitter
			$this->document->addCustomTag('<meta name="twitter:card" content="summary" />');
			if ($config->get('sitename', '')) {
				$this->document->addCustomTag('<meta name="twitter:site" content="'. htmlspecialchars($config->get('sitename', '')) .'" />');
			}
			if ($og_description) {
				$this->document->addCustomTag('<meta name="twitter:description" content="'. $og_description .'" />');
			}
			if ($og_image) {
				$this->document->addCustomTag('<meta name="twitter:image:src" content="'. JURI::root() . $og_image .'" />');
			}
		}

		// Page Meta
		if(isset($this->item->attribs)){
			$attribs = json_decode($this->item->attribs);
		} else {
			$attribs = new stdClass;
		}

		$meta_description = (isset($attribs->meta_description) && $attribs->meta_description) ? $attribs->meta_description : '';
		$meta_keywords = (isset($attribs->meta_keywords) && $attribs->meta_keywords) ? $attribs->meta_keywords : '';
		
		if ($menu) {
			if($menu->params->get('menu-meta_description')) {
				$meta_description = $menu->params->get('menu-meta_description');
			}
			if($menu->params->get('menu-meta_keywords')) {
				$meta_keywords = $menu->params->get('menu-meta_keywords');
			}
		}

		if (!empty($meta_description)) {
			$this->document->setDescription($meta_description);
		}

		if (!empty($meta_keywords)) {
			$this->document->setMetadata('keywords', $meta_keywords);
		}

		if ($menu) {
			if ($menu->params->get('robots'))
			{
				$this->document->setMetadata('robots', $menu->params->get('robots'));
			}
		}
	}
}
