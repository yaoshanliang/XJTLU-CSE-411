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

jimport('joomla.application.component.helper');
jimport('joomla.filesystem.folder');

$app = JFactory::getApplication();
$input = $app->input;

$cParams = JComponentHelper::getParams('com_jwpagefactory');
$version = JwpagefactoryHelper::getVersion();
$lang = JFactory::getLanguage()->getTag();
$domain = isset($_SERVER['HTTP_HOST']) ? urlencode($_SERVER['HTTP_HOST']) : '';
$veriargs = '&email=' . $cParams->get('joomworker_email') . '&api_key=' . $cParams->get('joomworker_license_key') . '&api_host=' . $domain . '&version=' . $version . '&language=' . $lang;

// Load Notice Of Pages Page
if ($action === 'page-notice') {
	// output
	$output = array('status' => false, 'data' => 'No notice.');

	// service api
	$noticeApi = API_SITE . '/index.php?option=com_pagefactoryservice&task=api.notice' . $veriargs;

	$noticeData = '';

	// curl
	if (ini_get('allow_url_fopen')) {
		$opts = array(
			'http' => array(
				'method' => 'GET',
				'header' => "Content-Type: text/html",
				'timeout' => 60
			)
		);
		$context = stream_context_create($opts);
		$noticeData = file_get_contents($noticeApi, false, $context);
	} else if (extension_loaded('curl')) {
		$headers = array();
		$headers[] = "Content-Type: text/html";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $noticeApi);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$noticeData = curl_exec($ch);
		curl_close($ch);
	} else {
		$output = array('status' => false, 'data' => 'Please enable cURL or url_fopen in PHP.');
	}

	if (!empty($noticeData)) {
		$noticeData = json_decode($noticeData);

		if (isset($noticeData->status) && $noticeData->status) {
			$output['status'] = true;
			$output['data'] = $noticeData->data;
			echo json_encode($output);
			die();
		}
	}
	echo json_encode($output);
	die();
}

// Load Page Template List
if ($action === 'pre-page-list') {
	// output
	$output = array('status' => false, 'data' => 'Template not found.');

	$templateData = '';
	$cache_path = JPATH_CACHE . '/jwpagefactory';
	$cache_file = $cache_path . '/templates.json';
	if ($cParams->get('open_pages_cache') && file_exists($cache_file) && (filemtime($cache_file) > (time() - (24 * 60 * 60)))) {
		$templateData = file_get_contents($cache_file);
	} else {
		// service api
		$templateApi = API_SITE . '/index.php?option=com_pagefactoryservice&task=api.templates' . $veriargs;

		if (ini_get('allow_url_fopen')) {
			$opts = array(
				'http' => array(
					'method' => 'GET',
					'header' => "Content-Type: text/html",
					'timeout' => 60
				)
			);
			$context = stream_context_create($opts);
			$templateData = file_get_contents($templateApi, false, $context);
		} else if (extension_loaded('curl')) {
			$headers = array();
			$headers[] = "Content-Type: text/html";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_URL, $templateApi);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$templateData = curl_exec($ch);
			curl_close($ch);
		} else {
			$output = array('status' => false, 'data' => 'Please enable \'cURL\' or url_fopen in PHP or contact with your Server or Hosting administrator.');
		}

		if (!empty($templateData)) {
			if (!file_exists($cache_path)) {
				JFolder::create($cache_path, 0755);
			}
			file_put_contents($cache_file, $templateData, LOCK_EX);
		}
	}

	if (!empty($templateData)) {
		$templates = json_decode($templateData);
		if (count((array)$templates)) {
			$output['status'] = true;
			$output['data'] = $templates;
			echo json_encode($output);
			die();
		}
	}
	echo json_encode($output);
	die();
}

// Load Page Template List
if ($action === 'get-pre-page-data') {
	// output
	$output = array('status' => false, 'data' => 'Page not found.');

	$layout_id = $input->post->get('layout_id', '', 'NUMBER');

	// service api
	$pageApi = API_SITE . '/index.php?option=com_pagefactoryservice&task=api.layout&id=' . $layout_id . $veriargs;

	$pageData = '';

	if (ini_get('allow_url_fopen')) {
		$opts = array(
			'http' => array(
				'method' => 'GET',
				'header' => "Content-Type: text/html",
				'timeout' => 60
			)
		);
		$context = stream_context_create($opts);
		$pageData = file_get_contents($pageApi, false, $context);
	} else if (extension_loaded('curl')) {
		$headers = array();
		$headers[] = "Content-Type: text/html";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $pageApi);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$pageData = curl_exec($ch);
		curl_close($ch);
	} else {
		$output = array('status' => false, 'data' => 'Please enable cURL or url_fopen in PHP.');
	}

	if (!empty($pageData)) {
		$page = json_decode($pageData);
		if (isset($page->status) && $page->status) {
			$output['status'] = true;
			$output['data'] = $page->content;
			echo json_encode($output);
			die();
		} elseif (isset($page->authorised)) {
			$output['status'] = false;
			$output['data'] = $page->authorised;
			echo json_encode($output);
			die();
		}

	}
	echo json_encode($output);
	die();
}

if ($action === 'pre-section-list') {
	// output
	$output = array('status' => false, 'data' => 'Sections not found.');

	$sectionsData = '';
	$cache_path = JPATH_CACHE . '/jwpagefactory';
	$cache_file = $cache_path . '/sections.json';
	if ($cParams->get('open_sections_cache') && file_exists($cache_file) && (filemtime($cache_file) > (time() - (24 * 60 * 60)))) {
		$sectionsData = file_get_contents($cache_file);
	} else {
		// service api
		$sectionApi = API_SITE . '/index.php?option=com_pagefactoryservice&task=api.blocks' . $veriargs;

		if (ini_get('allow_url_fopen')) {
			$opts = array(
				'http' => array(
					'method' => 'GET',
					'header' => "Content-Type: text/html",
					'timeout' => 60
				)
			);
			$context = stream_context_create($opts);
			$sectionsData = file_get_contents($sectionApi, false, $context);
		} else if (extension_loaded('curl')) {
			$headers = array();
			$headers[] = "Content-Type: text/html";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_URL, $sectionApi);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$sectionsData = curl_exec($ch);
			curl_close($ch);
		} else {
			$output = array('status' => false, 'data' => 'Please enable cURL or url_fopen in PHP.');
		}

		if (!empty($sectionsData)) {
			if (!file_exists($cache_path)) {
				JFolder::create($cache_path, 0755);
			}
			file_put_contents($cache_file, $sectionsData, LOCK_EX);
		}
	}

	if (!empty($sectionsData)) {
		$sections = json_decode($sectionsData);
		if ((is_array($sections) && count($sections)) || is_object($sections)) {
			$output['status'] = true;
			$output['data'] = $sections;
			echo json_encode($output);
			die();
		}
	}

	echo json_encode($output);
	die();
}

// Load page from uploaded page
if ($action === 'upload-page') {
	if (isset($_FILES['page']) && $_FILES['page']['error'] === 0) {

		$file_name = $_FILES['page']['name'];
		$file_extension = substr($file_name, -5);
		$file_extension_lower = strtolower($file_extension);

		if ($file_extension_lower === '.json') {
			$content = file_get_contents($_FILES['page']['tmp_name']);
			if ($content) {

				// add by adan 20190129
				// compatibility
				try {
					$regexData = '';
					$cache_path = JPATH_CACHE . '/jwpagefactory';
					$cache_file = $cache_path . '/regexfilter.json';
					if ($cParams->get('open_pages_cache') && file_exists($cache_file) && (filemtime($cache_file) > (time() - (24 * 60 * 60)))) {
						$regexData = file_get_contents($cache_file);
					} else {
						// service api
						$regexApi = API_SITE . '/index.php?option=com_pagefactoryservice&task=api.upload' . $veriargs;

						if (ini_get('allow_url_fopen')) {
							$opts = array(
								'http' => array(
									'method' => 'GET',
									'header' => "Content-Type: text/html",
									'timeout' => 60
								)
							);
							$context = stream_context_create($opts);
							$regexData = file_get_contents($regexApi, false, $context);
						} else if (extension_loaded('curl')) {
							$headers = array();
							$headers[] = "Content-Type: text/html";

							$ch = curl_init();
							curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
							curl_setopt($ch, CURLOPT_URL, $regexApi);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$regexData = curl_exec($ch);
							curl_close($ch);
						}

						if (!empty($regexData)) {
							if (!file_exists($cache_path)) {
								JFolder::create($cache_path, 0755);
							}
							file_put_contents($cache_file, $regexData, LOCK_EX);
						}
					}

					if (!empty($regexData)) {
						$regexs = json_decode($regexData);
						if (isset($regexs->status) && $regexs->status) {
							$content = preg_replace($regexs->data->search, $regexs->data->replace, $content);
						}
					}
				} catch (Exception $e) {
				}

				// last content
				if (is_array(json_decode($content))) {

					require_once JPATH_COMPONENT_ADMINISTRATOR . '/builder/classes/addon.php';
					$content = JwPageFactoryAddonHelper::__($content);

					// Check frontend editing
					if ($input->get('editarea', '', 'STRING') == 'frontend') {
						$content = JwPageFactoryAddonHelper::getFontendEditingPage($content);
					}

					echo json_encode(array('status' => true, 'data' => $content));
					die;
				}
			}
		}
	}

	echo json_encode(array('status' => false, 'data' => 'Error upload.'));
	die;
}

// Action Param Error
echo json_encode(array('status' => 'false', 'data' => 'Request Error'));
exit(0);