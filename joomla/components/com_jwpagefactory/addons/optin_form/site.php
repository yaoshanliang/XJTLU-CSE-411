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

class JwpagefactoryAddonOptin_form extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;

		// get pageid
		$input = JFactory::getApplication()->input;
		$page_id = $input->get('id', 0, 'INT');
		if (is_array($page_id)) {
			$page_id = $page_id[0];
		}

		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
		$content = (isset($settings->content) && $settings->content) ? $settings->content : '';

		$grid = (isset($settings->grid) && $settings->grid) ? $settings->grid : '';
		$media_type = (isset($settings->media_type) && $settings->media_type) ? $settings->media_type : '';
		$image = (isset($settings->image) && $settings->image) ? $settings->image : '';
		$alt_text = (isset($settings->alt_text) && $settings->alt_text) ? $settings->alt_text : '';
		$icon_name = (isset($settings->icon_name) && $settings->icon_name) ? $settings->icon_name : '';
		$media_position = (isset($settings->media_position) && $settings->media_position) ? $settings->media_position : 'top';

		$form_inline = (isset($settings->form_inline) && $settings->form_inline) ? $settings->form_inline : '';
		$alignment = (isset($settings->alignment) && $settings->alignment) ? $settings->alignment : '';
		$submit_btn_inside = (isset($settings->submit_btn_inside) && $settings->submit_btn_inside) ? $settings->submit_btn_inside : '';

		// Addon Options
		$show_checkbox = (isset($settings->show_checkbox)) ? $settings->show_checkbox : 0;
		$recaptcha = (isset($settings->recaptcha)) ? $settings->recaptcha : 0;
		$checkbox_title = (isset($settings->checkbox_title) && $settings->checkbox_title) ? $settings->checkbox_title : '';

		$platform = (isset($settings->platform) && $settings->platform) ? $settings->platform : 'mailchimp';
		$hide_name = (isset($settings->hide_name)) ? $settings->hide_name : 0;

		$mailchimp_api = (isset($settings->mailchimp_api) && $settings->mailchimp_api) ? $settings->mailchimp_api : '';
		$sendgrid_api = (isset($settings->sendgrid_api) && $settings->sendgrid_api) ? $settings->sendgrid_api : '';
		$sendinblue_api = (isset($settings->sendinblue_api) && $settings->sendinblue_api) ? $settings->sendinblue_api : '';
		$madmimi_api = (isset($settings->madmimi_api) && $settings->madmimi_api) ? $settings->madmimi_api : '';

		$optin_type = (isset($settings->optin_type) && $settings->optin_type) ? $settings->optin_type : 'normal';

		$button_text = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_FORM_SUBCSCRIBE');
		$use_custom_button = (isset($settings->use_custom_button) && $settings->use_custom_button) ? $settings->use_custom_button : 0;
		$button_position = (isset($settings->button_position) && $settings->button_position) ? $settings->button_position : '';
		$button_class = (isset($settings->button_type) && $settings->button_type) ? ' jwpf-btn-' . $settings->button_type : ' jwpf-btn-success';

		$button_text = (isset($settings->button_text) && $settings->button_text) ? $settings->button_text : '';
		$button_text_aria = (isset($settings->button_text) && $settings->button_text) ? $settings->button_text : '';

		if ($use_custom_button) {
			$button_class .= (isset($settings->button_size) && $settings->button_size) ? ' jwpf-btn-' . $settings->button_size : '';
			$button_class .= (isset($settings->button_shape) && $settings->button_shape) ? ' jwpf-btn-' . $settings->button_shape : ' jwpf-btn-rounded';
			$button_class .= (isset($settings->button_appearance) && $settings->button_appearance) ? ' jwpf-btn-' . $settings->button_appearance : '';
			$button_class .= (isset($settings->button_block) && $settings->button_block) ? ' ' . $settings->button_block : '';
			$button_class .= ' jwpf-btn-custom';

			$button_icon = (isset($settings->button_icon) && $settings->button_icon) ? $settings->button_icon : '';
			$button_icon_position = (isset($settings->button_icon_position) && $settings->button_icon_position) ? $settings->button_icon_position : 'left';

			$icon_arr = array_filter(explode(' ', $button_icon));
			if (count($icon_arr) === 1) {
				$button_icon = 'fa ' . $button_icon;
			}

			if ($button_icon_position == 'left') {
				$button_text = ($button_icon) ? '<i class="' . $button_icon . '" aria-hidden="true"></i> ' . $button_text : $button_text;
			} else {
				$button_text = ($button_icon) ? $button_text . ' <i class="' . $button_icon . '" aria-hidden="true"></i>' : $button_text;
			}
		}
		$output = '';

		// if cURL has't loaded or available in the server
		if (!extension_loaded('curl')) {
			$output .= '<div class="jwpf-addon jwpf-addon-optin-forms jwpf-alert jwpf-alert-warning">';
			$output .= '<p>' . JTEXT::_('COM_JWPAGEFACTORY_GLOBAL_CURL_NOT_AVAILABLE') . '</p>';
			$output .= '</div>';
			return $output;
		}

		// if selected platform hasn't api key inserted
		if (($platform == 'mailchimp' && $mailchimp_api == '') || ($platform == 'sendgrid' && $sendgrid_api == '') || ($platform == 'sendinblue' && $sendinblue_api == '') || ($platform == 'madmimi' && $madmimi_api == '')) {
			$output .= '<div class="jwpf-addon jwpf-addon-optin-forms jwpf-alert jwpf-alert-warning">';
			$output .= '<p>' . JTEXT::_('COM_JWPAGEFACTORY_ADDON_OPTIN_FORM_EMPTY_API') . ' ' . $platform . '.</p>';
			$output .= '</div>';
			return $output;
		} elseif ($platform == 'acymailing') {
			$acym_version = JwPgaeFactoryBase::getExtensionVersion(array('com_acymailing', 'com_acym'));
			if ($acym_version >= 6) {
				$acymailing_helper = rtrim(JPATH_ADMINISTRATOR, '/') . '/components/com_acym/helpers/helper.php';
			} else {
				$acymailing_helper = rtrim(JPATH_ADMINISTRATOR, '/') . '/components/com_acymailing/helpers/helper.php';
			}
			if (!file_exists($acymailing_helper)) {
				// if acymailing isn't installed
				$output .= '<div class="jwpf-addon jwpf-addon-optin-forms jwpf-alert jwpf-alert-warning">';
				$output .= '<p>' . JTEXT::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_ACYMAILING_NOT_INSTALLED') . '</p>';
				$output .= '</div>';

				return $output;
			} else {
				require_once $acymailing_helper;
			}
		}

		$info_wrap = '';
		$form_wrap = '';
		$raw_wrap = '';
		switch ($grid) {
			case '6-6':
				$raw_wrap = 'has-grid';
				$info_wrap = 'jwpf-col-sm-6';
				$form_wrap = 'jwpf-col-sm-6';
				break;
			case '5-7':
				$raw_wrap = 'has-grid';
				$info_wrap = 'jwpf-col-sm-5';
				$form_wrap = 'jwpf-col-sm-7';
				break;
			case '8-4':
				$raw_wrap = 'has-grid';
				$info_wrap = 'jwpf-col-sm-8';
				$form_wrap = 'jwpf-col-sm-4';
				break;
			case '2-10':
				$raw_wrap = 'has-grid';
				$info_wrap = 'jwpf-col-sm-2';
				$form_wrap = 'jwpf-col-sm-10';
				break;

			default:
				$info_wrap = 'jwpf-col-sm-12';
				$form_wrap = 'jwpf-col-sm-12';
				break;
		}

		$output .= '<div class="jwpf-addon jwpf-addon-optin-forms optintype-' . $optin_type . ' ' . $class . ' ' . $alignment . ' grid' . $grid . '">';
		$media = '';
		$media_class = '';
		if ($media_type == 'img') {
			$media_class .= ' jwpf-optin-form-img';
			if ($image) {
				$media .= '<img class="jwpf-img-responsive" src="' . $image . '" alt="' . $alt_text . '">';
			}
		} else {
			$media_class .= ' jwpf-optin-form-icon';
			if ($icon_name) {
				$media_icon_arr = array_filter(explode(' ', $icon_name));
				if (count($media_icon_arr) === 1) {
					$icon_name = 'fa ' . $icon_name;
				}
				$media .= '<i class="fa ' . $icon_name . '" aria-hidden="true"></i>';
			}
		}

		if ($grid == 'ws-4-4-4') {
			$output .= '<div class="jwpf-row justify-content-center">';
			$output .= '<div class="jwpf-col-sm-4">';
		} elseif ($grid == 'ws-2-8-2') {
			$output .= '<div class="jwpf-row justify-content-center">';
			$output .= '<div class="jwpf-col-sm-8">';
		} elseif ($grid == 'ws-3-6-3') {
			$output .= '<div class="jwpf-row justify-content-center">';
			$output .= '<div class="jwpf-col-sm-6">';
		}

		$output .= '<div class="jwpf-optin-form-box jwpf-row ' . $raw_wrap . '">';

		$output .= '<div class="jwpf-optin-form-info-wrap media-position-' . $media_position . ' ' . $info_wrap . '">';
		$output .= '<div class="jwpf-optin-form-img-wrap ' . $media_class . '">' . $media . '</div>';
		if (isset($title) || isset($content)) {
			$output .= '<div class="jwpf-optin-form-details-wrap">';
		}
		if ($title) {
			$output .= '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>';
		}
		if ($content) {
			$output .= '<div class="jwpf-optin-form-details">' . $content . '</div>';
		}
		if (isset($title) || isset($content)) {
			$output .= '</div>'; //.jwpf-optin-form-details-wrap
		}
		$output .= '</div>'; //.jwpf-optin-form-info-wrap


		$output .= '<div class="jwpf-optin-form-content ' . $form_wrap . '">';
		$forminline = ($form_inline) ? 'form-inline' : '';
		$button_inside = ($submit_btn_inside) ? 'submit-button-inside' : '';
		$output .= '<form class="jwpf-optin-form ' . $forminline . ' ' . $button_inside . '">';
		if (!$hide_name) {
			$output .= '<div class="jwpf-form-group name-wrap">';
			$output .= '<input type="text" name="fname" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_NAME') . '" required="required" aria-label="name">';
			$output .= '</div>'; //.jwpf-form-group
		}

		$output .= '<div class="jwpf-form-group email-wrap">';
		$output .= '<input type="email" name="email" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_EMAIL') . '" required="required" aria-label="name">';
		$output .= '</div>'; //.jwpf-form-group

		if ($recaptcha) {
			JPluginHelper::importPlugin('captcha', 'recaptcha');
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onInit', 'dynamic_recaptcha_' . $this->addon->id);
			$recaptcha = $dispatcher->trigger('onDisplay', array(null, 'dynamic_recaptcha_' . $this->addon->id, 'jwpf-dynamic-recaptcha'));

			$output .= '<div class="jwpf-form-group recaptcha-wrap">';
			$output .= (isset($recaptcha[0])) ? $recaptcha[0] : '<p class="jwpf-alert jwpf-alert-warning">' . JText::_('COM_JWPAGEFACTORY_RECAPTCHA_NOT_INSTALLED') . '</p>';
			$output .= '</div>'; //.jwpf-form-group
		}

		if ($show_checkbox) {
			$output .= '<div class="jwpf-form-group">';
			$output .= '<div class="jwpf-form-check">';
			$output .= '<input class="jwpf-form-check-input" type="checkbox" name="agreement" id="agreement" required="required">';
			$output .= '<label class="jwpf-form-check-label" for="agreement">' . $checkbox_title . '</label>';
			$output .= '</div>';
			$output .= '</div>';
		}

		if ($platform == 'acymailing') {
			$output .= '<input type="hidden" name="acymversion" value="' . $acym_version . '">';
		}
		$output .= '<input type="hidden" name="platform" value="' . $platform . '">';
		$output .= '<input type="hidden" name="hidename" value="' . $hide_name . '">';
		$output .= '<input type="hidden" name="pageid" value="' . $page_id . '">';
		$output .= '<input type="hidden" name="addonId" value="' . $this->addon->id . '">';

		$output .= '<div class="button-wrap ' . $button_position . '">';
		$output .= '<button type="submit" id="btn-' . $this->addon->id . '" class="jwpf-btn' . $button_class . '" aria-label="' . strip_tags($button_text_aria) . '"><i class="fa" aria-hidden="true"></i> ' . $button_text . '</button>';
		$output .= '</div>'; //.button-wrap

		$output .= '</form>';
		$output .= '<div style="display:none;margin-top:10px;" class="jwpf-optin-form-status"></div>';
		$output .= '</div>'; //.jwpf-optin-form-content

		$output .= '</div>'; //.jwpf-optin-form-box

		if (($grid == 'ws-4-4-4') || ($grid == 'ws-2-8-2') || ($grid == 'ws-3-6-3')) {
			$output .= '</div>'; //jwpf-offset
			$output .= '</div>'; //jwpf-row
		}

		$output .= '</div>'; //.jwpf-addon-optin-forms
		return $output;
	}

	public static function getAjax()
	{

		$input = JFactory::getApplication()->input;
		//inputs
		$inputs = $input->get('data', array(), 'ARRAY');

		foreach ($inputs as $input) {
			if ($input['name'] == 'email') {
				$email = $input['value'];
			}
			if ($input['name'] == 'hidename') {
				$hidename = $input['value'];
			}
			if ($input['name'] == 'fname') {
				$name = $input['value'];
			}
			if ($input['name'] == 'platform') {
				$platform = $input['value'];
			}
			if ($input['name'] == 'acymversion') {
				$acymversion = $input['value'];
			}
			if ($input['name'] == 'g-recaptcha-response') {
				$recaptcha = $input['value'];
				$showcaptcha = true;
			}
			if ($input['name'] == 'pageid') {
				$pageid = $input['value'];
			}
			if ($input['name'] == 'addonId') {
				$addonId = $input['value'];
			}

			if ($input['name'] == 'view_type') {
				$view_type = $input['value'];
			}

			if ($input['name'] == 'module_id') {
				$module_id = $input['value'];
			}
		}

		// get addon infos
		if ($view_type == 'module') {
			$page_info = self::getPageInfoById($module_id, $view_type, 'new');
			// if old version of module
			if (empty($page_info)) {
				$page_info = self::getPageInfoById($module_id, $view_type);
				$page_text = json_decode($page_info->params);
			} else { // if new version of module
				$page_text = new stdClass();
				$page_text->content = $page_info->text;
			}
			$addon_info = self::getAddonSettingByPageInfo($page_text->content, $addonId);
		} else {
			$page_info = self::getPageInfoById($pageid, $view_type);
			$addon_info = self::getAddonSettingByPageInfo($page_info->text, $addonId);
		}

		$output = array();
		$output['status'] = false;

		if (isset($showcaptcha) && $showcaptcha) {
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JEventDispatcher::getInstance();
			$res = $dispatcher->trigger('onCheckAnswer');
			if (!$res[0]) {
				$output['content'] = '<span class="jwpf-text-danger">' . JText::_('COM_JWPAGEFACTORY_INVALID_CAPTCHA') . '</span>';
				return json_encode($output);
			}
		}

		// valited email address
		if ($email) {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_INVALID_EMAIL');
				$output['status'] = false;
				return json_encode($output);
			}
		}

		// if hide name field then set value NULL
		if ($hidename) {
			$name = '';
		}

		if ($platform == 'mailchimp') {
			//mailchimp get crecentials
			$mcapi = (isset($addon_info->mailchimp_api) && $addon_info->mailchimp_api) ? $addon_info->mailchimp_api : '';
			$mclistid = (isset($addon_info->mailchimp_listid) && $addon_info->mailchimp_listid) ? $addon_info->mailchimp_listid : '';
			$mcaction = (isset($addon_info->mailchimp_action) && $addon_info->mailchimp_action) ? $addon_info->mailchimp_action : '';

			$memberId = md5(strtolower($email));
			$dataCenter = substr($mcapi, strpos($mcapi, '-') + 1);
			$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $mclistid . '/members/' . $memberId;
			$json = json_encode([
				'email_address' => $email,
				'status' => $mcaction, // "subscribed","unsubscribed","cleaned","pending"
				'merge_fields' => [
					'FNAME' => $name,
					'LNAME' => ''
				]
			]);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $mcapi);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			$result = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$err = curl_error($ch);
			curl_close($ch);

			//$response = json_decode($result)->status;
			// if curl error
			if ($err) {
				$output['content'] = 'cURL error: ' . $err;
				$output['status'] = false;
				return json_encode($output);
			}

			// store the status message based on response code
			if ($httpCode == 200) {
				if ($mcaction == 'pending') {
					$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_PENDING');
				} else {
					$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_CONFIRMED');
				}
				$output['status'] = true;
			} else {
				switch ($httpCode) {
					case 214: // if success
						$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_EXIST');
						$output['status'] = false;
						break;
					default:
						$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_ERROR');
						$output['status'] = false;
						break;
				} // if got response
			}
		} elseif ($platform == 'sendgrid') {
			//sendgrid get crecentials
			$sgapi = (isset($addon_info->sendgrid_api) && $addon_info->sendgrid_api) ? $addon_info->sendgrid_api : '';

			$input_data = json_encode(
				array(
					'email' => $email,
					'first_name' => $name,
					'last_name' => ''
				)
			);
			$input_data = '[' . $input_data . ']';
			$access_api = array(
				"authorization: Bearer " . $sgapi,
				"cache-control: no-cache"
			);

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, "https://api.sendgrid.com/v3/contactdb/recipients");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_ENCODING, '');
			curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, CURLOPT_SSL_VERIFYPEER);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_POSTFIELDS, $input_data);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $access_api);

			$result = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			$result_decode = json_decode($result);
			// if curl error
			if ($err) {
				$output['content'] = 'cURL error: ' . $err;
				$output['status'] = false;
				return json_encode($output);
			}

			if (isset($result_decode->error_count) && $result_decode->error_count == 0) {
				if ($result_decode->updated_count) {
					$output['status'] = true;
					$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_UPDATED');
				} else {
					$output['status'] = true;
					$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_CONFIRMED');
				}
			} else {
				$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_ERROR');
				$output['status'] = false;
				return json_encode($output);
			}

		}
		if ($platform == 'sendinblue') { // if sendinblue
			//sendinBlue get crecentials
			$sbapi = (isset($addon_info->sendinblue_api) && $addon_info->sendinblue_api) ? $addon_info->sendinblue_api : '';
			$sblistid = (isset($addon_info->sendinblue_listid) && $addon_info->sendinblue_listid) ? $addon_info->sendinblue_listid : '';

			$data_input = array(
				'email' => $email,
				'attributes' => array('NAME' => $name),
				'listid' => array($sblistid)
			);

			$ch = curl_init('https://api.sendinblue.com/v2.0/user/createdituser');
			$auth_header = 'api-key:' . $sbapi;
			$content_header = "Content-Type:application/json";
			$timeout = 30000; //default timeout: 30 secs
			if ($timeout != '' && ($timeout <= 0 || $timeout > 60000)) {
				throw new \Exception('value not allowed for timeout');
			}
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				// Windows only over-ride
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth_header, $content_header));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_input));
			$data = curl_exec($ch);
			$err = curl_error($ch);
			if (!is_string($data) || !strlen($data)) {
				$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_ERROR');
				$output['status'] = false;
				return json_encode($output);
			}
			curl_close($ch);
			$result = json_decode($data, true);
			// if curl error
			if ($err) {
				$output['content'] = 'cURL error: ' . $err;
				$output['status'] = false;
				return json_encode($output);
			}
			if ($result['code'] == 'success') { // if success
				$output['status'] = true;
				if (isset($result['data']['updated']) && $result['data']['updated']) {
					$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_UPDATED');
				} else {
					$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_CONFIRMED');
				}
			} else {
				$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_ERROR');
				$output['status'] = false;
			}
		} elseif ($platform == 'madmimi') { // if madmimi
			//madmimi get crecentials
			$mmuname = (isset($addon_info->madmimi_user) && $addon_info->madmimi_user) ? $addon_info->madmimi_user : '';
			$mmapi = (isset($addon_info->madmimi_api) && $addon_info->madmimi_api) ? $addon_info->madmimi_api : '';
			$mmlistname = (isset($addon_info->madmimi_listname) && $addon_info->madmimi_listname) ? $addon_info->madmimi_listname : '';

			$user = array('email' => $email, 'firstName' => $name, 'add_list' => $mmlistname);
			$authenticate = array('username' => $mmuname, 'api_key' => $mmapi);
			// generate CSV
			$csv = "";
			$keys = array_keys($user);
			foreach ($keys as $key => $value) {
				$value = self::escape_for_csv($value);
				$csv .= $value . ",";
			}
			$csv = substr($csv, 0, -1);
			$csv .= "\n";
			foreach ($user as $key => $value) {
				$value = self::escape_for_csv($value);
				$csv .= $value . ",";
			}
			$csv = substr($csv, 0, -1);
			$csv .= "\n";

			$options = array('csv_file' => $csv) + $authenticate;
			// do reqiest
			$request_options = http_build_query($options);
			$url = 'https://api.madmimi.com/audience_members';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request_options);
			$result = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);

			// if curl error
			if ($result === false && $err) {
				$output['content'] = 'cURL error: ' . $err;
				$output['status'] = false;
				return json_encode($output);
			}

			if (is_numeric($result)) {
				$output['status'] = true;
				$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_CONFIRMED');
			} else {
				$output['status'] = false;
				$output['content'] = $result;
				return json_encode($output);
			}
		} elseif ($platform == 'acymailing') { // if AcyMailing
			// if acymailing isn't installed
			if ($acymversion >= 6) {
				$acymailing_helper = rtrim(JPATH_ADMINISTRATOR, '/') . '/components/com_acym/helpers/helper.php';
			} else {
				$acymailing_helper = rtrim(JPATH_ADMINISTRATOR, '/') . '/components/com_acymailing/helpers/helper.php';
			}
			// include acymailing helper
			if (!include_once($acymailing_helper)) {
				$output['status'] = false;
				$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_ACYMAILING_NOT_INSTALLED');
				return json_encode($output);
			}

			$acymailing_listids = (isset($addon_info->acymailing_listids) && $addon_info->acymailing_listids) ? $addon_info->acymailing_listids : '';

			$user_info = new stdClass();
			$user_info->email = $email;
			$user_info->name = $name;
			if ($acymversion >= 6) { // if version is more than or equal 6

				$userClass = acym_get('class.user');
				$userId = $userClass->save($user_info); // this function will return you the ID of the user inserted in the AcyMailing table

				if (!is_int($userId)) {
					return false;
				}

				// if selected all list
				if ((is_array($acymailing_listids) && in_array('', $acymailing_listids)) || $acymailing_listids == '') {
					$acy_list_class = acym_get('class.list');
					$acy_lists = $acy_list_class->getAll();

					$acymailing_listids = array();
					foreach ($acy_lists as $key => $acy_list) {
						$acymailing_listids[$key] = $acy_list->listid;
					}
				}
				if (empty($acymailing_listids) || empty($userId)) {
					$output['status'] = false;
					$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_ERROR');
				}

				//$newSubscription = array();
				if (!empty($acymailing_listids)) {
					$output['status'] = true;
					$results = $userClass->subscribe($userId, $acymailing_listids);
				}

			} else { // for version less than or equal 5


				$subscriberClass = acymailing_get('class.subscriber');
				$subid = $subscriberClass->save($user_info); //this function will return you the ID of the user inserted in the AcyMailing table

				// if selected all list
				if ((is_array($acymailing_listids) && in_array('', $acymailing_listids)) || $acymailing_listids == '') {
					$acy_list_class = acymailing_get('class.list');
					$acy_lists = $acy_list_class->getLists();

					$acymailing_listids = array();
					foreach ($acy_lists as $key => $acy_list) {
						$acymailing_listids[$key] = $acy_list->listid;
					}
				}

				$userClass = acymailing_get('class.subscriber');
				$new_subscription = array();
				if (!empty($acymailing_listids)) {
					foreach ($acymailing_listids as $listId) {
						$newList = array();
						$newList['status'] = 1;
						$new_subscription[$listId] = $newList;
					}
				}
				if (empty($new_subscription) || empty($subid)) {
					$output['status'] = false;
					$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_ERROR');
				}
				if ($userClass->subid($subid)) {
					$subid = $userClass->subid($subid);
				}
				$results = $userClass->saveSubscription($subid, $new_subscription);
			}

			if (isset($results) && $results) {
				$output['status'] = true;
				$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_CONFIRMED');
			} else {
				$output['status'] = false;
				$output['content'] = JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_PLATFORM_EMAIL_ERROR');
			}

		}
		return json_encode($output);
	}

	public static function escape_for_csv($s)
	{
		// Watch out! We may have quotes! So quote them.
		$s = str_replace('"', '""', $s);
		if (preg_match('/,/', $s) || preg_match('/"/', $s) || preg_match("/\n/", $s)) {
			// Quote the whole thing b/c we have a newline, comma or quote.
			return '"' . $s . '"';
		} else {
			// False alarm. We're good.
			return $s;
		}
	}

	public function stylesheets()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/css/magnific-popup.css');
	}

	public function scripts()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/js/jquery.magnific-popup.min.js');
	}

	public static function getPageInfoById($pageid, $view_type = 'page', $version = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		if ($view_type == 'module') {
			if ($version == 'new') {
				$query->from($db->quoteName('#__jwpagefactory', 'a'));
				$query->where($db->quoteName('a.extension_view') . " = " . $db->quote('module'));
				$query->where($db->quoteName('a.view_id') . " = " . $db->quote((int)$pageid));
			} else {
				$query->from($db->quoteName('#__modules', 'a'));
				$query->where($db->quoteName('a.id') . " = " . $db->quote((int)$pageid));
			}
		} else if ($view_type == 'article') {
			$query->from($db->quoteName('#__jwpagefactory', 'a'));
			$query->where($db->quoteName('a.view_id') . " = " . $db->quote((int)$pageid));
		} else {
			$query->from($db->quoteName('#__jwpagefactory', 'a'));
			$query->where($db->quoteName('a.id') . " = " . $db->quote((int)$pageid));
		}

		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}

	public static function getAddonSettingByPageInfo($pageContent, $addonId)
	{
		$addonInfo = false;
		$pageContent = json_decode($pageContent);

		foreach ($pageContent as $key => $row) {
			foreach ($row->columns as $key => $column) {
				foreach ($column->addons as $key => $addon) {
					if ($addon->id == $addonId) {
						$addonInfo = $addon->settings;
						break;
					}
				}
			}
		}
		return $addonInfo;
	}

	public function js()
	{
		$settings = $this->addon->settings;
		$optin_timein = (isset($settings->optin_timein) && $settings->optin_timein) ? $settings->optin_timein : 0;
		$optin_timeout = (isset($settings->optin_timeout) && $settings->optin_timeout) ? $settings->optin_timeout : 0;

		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$js = 'jQuery(function($){

			var addonId = $("' . $addon_id . '"),
				prentSectionId	= addonId.parent().closest("section");

			if($("' . $addon_id . '").find(".optintype-popup").length !== 0 && $("body:not(.layout-edit)").length !== 0){
				prentSectionId.hide();
			}

			if($("' . $addon_id . '").find(".optintype-popup").length !== 0 && $("body:not(.layout-edit)").length !== 0){
				//var parentSection 	= $("' . $addon_id . '").parent().closest("section"),
				var addonWidth 			= addonId.parent().outerWidth(),
						optin_timein		= ' . $optin_timein . ',
						optin_timeout		= ' . $optin_timeout . ',
						prentSectionId	= ".com-jwpagefactory:not(.layout-edit) #" + addonId.attr("id");

					$(window).load(function () {
					setTimeout(function(){
						$.magnificPopup.open({
						items: {
							src: "<div class=\"jwpf-optin-form-popup-wrap\" \">"+$(addonId)[0].outerHTML + "</div>"
							//src: "<div style=\"width:+"addonWidth"+\">" + $(addonId)[0].outerHTML + "</div>"
						},
						type: "inline",
								mainClass: "mfp-fade",
								disableOn: function() {
								return true;
							},
								callbacks: {
							open: function() {
								if(optin_timeout){
								setTimeout(function(){
									$("' . $addon_id . '").magnificPopup("close");
								}, optin_timeout);
								}
							}
							}
					});
					}, optin_timein);
				}); //window
			};
		})';

		return $js;
	}

	public function css()
	{
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_jwpagefactory/layouts';
		$css_path = new JLayoutFile('addon.css.button', $layout_path);
		$settings = $this->addon->settings;

		$icon_style = (isset($settings->icon_size) && $settings->icon_size) ? 'font-size: ' . $settings->icon_size . 'px;' : '';
		$icon_style .= (isset($settings->icon_color) && $settings->icon_color) ? 'color: ' . $settings->icon_color . ';' : '';
		$optin_width = (isset($settings->optin_width) && $settings->optin_width) ? 'width: ' . $settings->optin_width . 'px;' : 'width: 500px;';

		$border_position = (isset($settings->custom_input_border_side) && $settings->custom_input_border_side) ? $settings->custom_input_border_side : '';
		$custom_input = (isset($settings->custom_input) && $settings->custom_input) ? $settings->custom_input : '';
		$custom_input_borderless = (isset($settings->custom_input_borderless) && $settings->custom_input_borderless) ? $settings->custom_input_borderless : '';

		$custom_input_style = '';
		if ($custom_input) {
			$custom_input_style .= (isset($settings->custom_input_bgcolor) && $settings->custom_input_bgcolor) ? 'background-color: ' . $settings->custom_input_bgcolor . ';' : '';
			$custom_input_style .= (isset($settings->custom_input_color) && $settings->custom_input_color) ? 'color: ' . $settings->custom_input_color . ';' : '';
			$custom_input_style .= (isset($settings->custom_input_borderless) && $settings->custom_input_borderless) ? 'border:none;' : '';
			$custom_input_style .= (isset($settings->custom_input_border) && $settings->custom_input_border && $custom_input_borderless == 0) ? 'border: none; border-' . $border_position . 'width: ' . $settings->custom_input_border . 'px;' : '';
			$custom_input_style .= (isset($settings->custom_input_border_color) && $settings->custom_input_border_color && $custom_input_borderless == 0) ? 'border-' . $border_position . 'color: ' . $settings->custom_input_border_color . ';' : '';
			$custom_input_style .= (isset($settings->custom_input_border_style) && $settings->custom_input_border_style && $custom_input_borderless == 0) ? 'border-' . $border_position . 'style: ' . $settings->custom_input_border_style . ';' : '';
			$custom_input_style .= (isset($settings->custom_input_bdr) && gettype($settings->custom_input_bdr) == 'string') ? 'border-radius: ' . $settings->custom_input_bdr . 'px;' : '';
			$custom_input_style .= (isset($settings->custom_input_padding) && trim($settings->custom_input_padding)) ? 'padding: ' . $settings->custom_input_padding . ';' : '';
			$placeholder_color = (isset($settings->custom_input_color) && $settings->custom_input_color) ? 'color: ' . $settings->custom_input_color . ';' : '';
		}

		$css = '';
		if ($icon_style) {
			$css .= $addon_id . ' .jwpf-optin-form-icon {';
			$css .= $icon_style;
			$css .= "\n" . '}' . "\n";
		}

		if ($optin_width) {
			$css .= '.jwpf-optin-form-popup-wrap > ' . $addon_id . ' {';
			$css .= $optin_width;
			$css .= "\n" . '}' . "\n";
		}

		if ($custom_input_style) {
			$css .= $addon_id . ' .jwpf-optin-form input {';
			$css .= $custom_input_style;
			$css .= "\n" . '}' . "\n";

			$css .= $addon_id . ' .jwpf-optin-form input::placeholder {';
			$css .= $placeholder_color;
			$css .= 'opacity:1;';
			$css .= '}';
		}

		$use_custom_button = (isset($settings->use_custom_button) && $settings->use_custom_button) ? $settings->use_custom_button : '';
		$font_size = (isset($settings->fontsize) && $settings->fontsize) ? $settings->fontsize : '';

		if ($use_custom_button) {
			$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $settings, 'id' => 'btn-' . $this->addon->id));
			$css .= $addon_id . ' .jwpf-btn-custom {';
			$css .= 'font-size:' . $font_size . 'px;';
			$css .= '}';
		}

		$custom_input_padding_sm = (isset($settings->custom_input_padding_sm) && trim($settings->custom_input_padding_sm)) ? 'padding: ' . $settings->custom_input_padding_sm . ';' : '';

		if ($custom_input_padding_sm) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			$css .= $addon_id . ' .jwpf-optin-form input {';
			$css .= $custom_input_padding_sm;
			$css .= '}';
			$css .= '}';
		}

		$custom_input_padding_xs = (isset($settings->custom_input_padding_xs) && trim($settings->custom_input_padding_xs)) ? 'padding: ' . $settings->custom_input_padding_xs . ';' : '';

		if ($custom_input_padding_xs) {
			$css .= '@media (max-width: 767px) {';
			$css .= $addon_id . ' .jwpf-optin-form input {';
			$css .= $custom_input_padding_xs;
			$css .= '}';
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
			<#
			var grid = data.grid || "";
			var info_wrap = "";
			var form_wrap = "";
			var raw_wrap  = "";
			switch (grid) {
				case "6-6":
					raw_wrap  = "has-grid";
					info_wrap = "jwpf-col-sm-6";
					form_wrap = "jwpf-col-sm-6";
					break;
				case "5-7":
					raw_wrap  = "has-grid";
					info_wrap = "jwpf-col-sm-5";
					form_wrap = "jwpf-col-sm-7";
					break;
				case "8-4":
					raw_wrap  = "has-grid";
					info_wrap = "jwpf-col-sm-8";
					form_wrap = "jwpf-col-sm-4";
					break;
				case "2-10":
					raw_wrap  = "has-grid";
					info_wrap = "jwpf-col-sm-2";
					form_wrap = "jwpf-col-sm-10";
					break;

				default:
					info_wrap = "jwpf-col-sm-12";
					form_wrap = "jwpf-col-sm-12";
					break;
			}

			var media = "";
			var media_class = "";
			let icon_arr = (typeof data.icon_name !== "undefined" && data.icon_name) ? data.icon_name.split(" ") : "";
			let icon_name = icon_arr.length === 1 ? "fa "+data.icon_name : data.icon_name;
			
			if(data.media_type == "img"){
				media_class = " jwpf-optin-form-img";
				if(data.image && data.image.indexOf("https://") == -1 && data.image.indexOf("http://") == -1){
					media = \'<img class="jwpf-img-responsive" src="\' + pagefactory_base + data.image + \'" alt="\' + data.alt_text + \'">\';
				} else if(data.image){
					media = \'<img class="jwpf-img-responsive" src="\' + data.image + \'" alt="\' + data.alt_text + \'">\';
				}
			} else{
				media_class = " jwpf-optin-form-icon";
				if(data.icon_name){
					media = \'<i class="\' + icon_name + \'"></i>\';
				}
			}

			var forminline = (data.form_inline) ? "form-inline" : "";
			var button_inside = (data.submit_btn_inside) ? "submit-button-inside" : "";

			var button_text = Joomla.JText._("COM_JWPAGEFACTORY_ADDON_OPTIN_FORM_SUBCSCRIBE");
			var use_custom_button = (data.use_custom_button) ? data.use_custom_button : 0;
			var button_class = (data.button_type) ? " jwpf-btn-" + data.button_type : " jwpf-btn-success";
	
			button_text = (data.button_text) ? data.button_text : "";
			let btn_icon_arr = (typeof data.button_icon !== "undefined" && data.button_icon) ? data.button_icon.split(" ") : "";
			let btn_icon_name = btn_icon_arr.length === 1 ? "fa "+data.button_icon : data.button_icon;

			if(use_custom_button) {
				button_class += (data.button_size) ? " jwpf-btn-" + data.button_size : "";
				button_class += (data.button_shape) ? " jwpf-btn-" + data.button_shape: " jwpf-btn-rounded";
				button_class += (data.button_appearance) ? " jwpf-btn-" + data.button_appearance : "";
				button_class += (data.button_block) ? " " + data.button_block : "";
				button_class += " jwpf-btn-custom";
				var button_icon = (data.button_icon) ? data.button_icon : false;
				var button_icon_position = (data.button_icon_position) ? data.button_icon_position: "left";
	
				if(button_icon_position == "left") {
					button_text = (button_icon) ? \'<i class="\' + btn_icon_name + \'"></i> \' + button_text : button_text;
				} else {
					button_text = (button_icon) ? button_text + \' <i class="\' + btn_icon_name + \'"></i>\' : button_text;
				}
			}

			var button_fontstyle = data.button_fontstyle || "";
			#>
			<style type="text/css">
			#jwpf-addon-{{ data.id }} .jwpf-optin-form-icon {
				font-size: {{ data.icon_size }}px;
				color: {{ data.icon_color }};
			}
			<# if(data.custom_input){ #>
				#jwpf-addon-{{ data.id }} .jwpf-optin-form input {
					background-color: {{ data.custom_input_bgcolor }};
					color: {{ data.custom_input_color }};
	
					<# if(data.custom_input_borderless){ #>
						border:none;
					<# } else { #>
						border: none;
						border-{{ data.custom_input_border_side }}width: {{ data.custom_input_border }}px;
						border-{{ data.custom_input_border_side }}color: {{ data.custom_input_border_color }};
						border-{{ data.custom_input_border_side }}style: {{ data.custom_input_border_style }};
					<# } #>
					border-radius: {{ data.custom_input_bdr }}px;
					<# if(_.isObject(data.custom_input_padding)) { #>
						padding: {{ data.custom_input_padding.md }};
					<# } else { #>
						padding: {{ data.custom_input_padding }};
					<# } #>
				}
				#jwpf-addon-{{ data.id }} .jwpf-optin-form input::placeholder {
					color: {{ data.custom_input_color }};
					opacity: 1;
				}
			<# } #>
			.jwpf-optin-form-popup-wrap > #jwpf-addon-{{ data.id }} {
				<# if(data.optin_width){ #>
					width: {{ data.optin_width }}px;
				<# } else { #>
					width: 500px;
				<# } #>
			}
			<# if(data.use_custom_button){ #>
				#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom{
					letter-spacing: {{ data.button_letterspace }};
					<# if(_.isArray(button_fontstyle)) { #>
						<# if(button_fontstyle.indexOf("underline") !== -1){ #>
							text-decoration: underline;
						<# } #>
						<# if(button_fontstyle.indexOf("uppercase") !== -1){ #>
							text-transform: uppercase;
						<# } #>
						<# if(button_fontstyle.indexOf("italic") !== -1){ #>
							font-style: italic;
						<# } #>
						<# if(button_fontstyle.indexOf("lighter") !== -1){ #>
							font-weight: lighter;
						<# } else if(button_fontstyle.indexOf("normal") !== -1){#>
							font-weight: normal;
						<# } else if(button_fontstyle.indexOf("bold") !== -1){#>
							font-weight: bold;
						<# } else if(button_fontstyle.indexOf("bolder") !== -1){#>
							font-weight: bolder;
						<# } #>
					<# } #>
					<# if(data.button_type=="custom"){ #>
						background-color: {{data.button_background_color}};
						color: {{ data.button_color }};
						<# if(data.button_appearance == "outline"){ #>
							border-color: {{ data.button_background_color }};
							background-color: transparent;
						<# } else if(data.button_appearance == "3d"){ #>
							border-bottom-color: {{ data.button_background_color_hover }};
							background-color: {{ data.button_background_color }};
						<# } else if(data.button_appearance == "gradient"){ #>
							border: none;
							<# if(typeof data.button_background_gradient.type !== "undefined" && data.button_background_gradient.type == "radial"){ #>
								background-image: radial-gradient(at {{ data.button_background_gradient.radialPos || "center center"}}, {{ data.button_background_gradient.color }} {{ data.button_background_gradient.pos || 0 }}%, {{ data.button_background_gradient.color2 }} {{ data.button_background_gradient.pos2 || 100 }}%);
							<# } else { #>
								background-image: linear-gradient({{ data.button_background_gradient.deg || 0}}deg, {{ data.button_background_gradient.color }} {{ data.button_background_gradient.pos || 0 }}%, {{ data.button_background_gradient.color2 }} {{ data.button_background_gradient.pos2 || 100 }}%);
							<# } #>
						<# } #>
					<# } #>
					<# if(_.isObject(data.fontsize)){ #>
						font-size: {{data.fontsize.md}}px;
					<# } else { #>
						font-size: {{data.fontsize}}px;
					<# } #>
					<# if(_.isObject(data.button_padding)) { #>
						padding:{{ data.button_padding.md }};
					<# } else { #>
						padding:{{ data.button_padding }};
					<# } #>
				}
	
				#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom:hover{
					color: {{ data.button_color_hover }};
					<# if(data.button_type=="custom"){ #>
						background-color: {{ data.button_background_color_hover }};
						<# if(data.button_appearance == "outline"){ #>
							border-color: {{ data.button_background_color_hover }};
						<# } else if(data.button_appearance == "gradient"){ #>
							<# if(typeof data.button_background_gradient_hover.type !== "undefined" && data.button_background_gradient_hover.type == "radial"){ #>
								background-image: radial-gradient(at {{ data.button_background_gradient_hover.radialPos || "center center"}}, {{ data.button_background_gradient_hover.color }} {{ data.button_background_gradient_hover.pos || 0 }}%, {{ data.button_background_gradient_hover.color2 }} {{ data.button_background_gradient_hover.pos2 || 100 }}%);
							<# } else { #>
								background-image: linear-gradient({{ data.button_background_gradient_hover.deg || 0}}deg, {{ data.button_background_gradient_hover.color }} {{ data.button_background_gradient_hover.pos || 0 }}%, {{ data.button_background_gradient_hover.color2 }} {{ data.button_background_gradient_hover.pos2 || 100 }}%);
							<# } #>
						<# } #>
					<# } #>
				}
			<# }
			let custom_btn_style_sm = "";
			custom_btn_style_sm += (_.isObject(data.fontsize) && data.fontsize.sm) ? \'font-size:\'+data.fontsize.sm+\'px;\' : "";
			custom_btn_style_sm += (_.isObject(data.button_padding) && data.button_padding.sm) ? \'padding:\'+data.button_padding.sm+\';\' : "";
			
			if(_.isObject(data.custom_input_padding) || custom_btn_style_sm) { #>
				@media (min-width: 768px) and (max-width: 991px) {
					#jwpf-addon-{{ data.id }} .jwpf-optin-form input {
						padding: {{data.custom_input_padding.sm}};
					}
					<# if(custom_btn_style_sm) { #>
						#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom {
							{{custom_btn_style_sm}}
						}
					<# } #>
				}
			<# }
	
			let custom_btn_style_xs = "";
			custom_btn_style_xs += (_.isObject(data.fontsize) && data.fontsize.xs) ? \'font-size:\'+data.fontsize.xs+\'px;\' : "";
			custom_btn_style_xs += (_.isObject(data.button_padding) && data.button_padding.xs) ? \'padding:\'+data.button_padding.xs+\';\' : "";
			
			if(_.isObject(data.custom_input_padding) || custom_btn_style_xs) { #>
				@media (max-width: 767px) {
					#jwpf-addon-{{ data.id }} .jwpf-optin-form input {
						padding: {{data.custom_input_padding.xs}};
					}
					<# if(custom_btn_style_xs) { #>
						#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom {
							{{custom_btn_style_xs}}
						}
					<# } #>
				}
			<# } #>
			</style>
			<div class="jwpf-addon jwpf-addon-optin-forms {{ data.alignment }} grid{{ grid }} {{data.class}}">
				<# if(grid == "ws-4-4-4"){ #>
					<div class="jwpf-row justify-content-center">
					<div class="jwpf-col-sm-4">
				<# } else if(grid == "ws-2-8-2"){ #>
					<div class="jwpf-row justify-content-center">
					<div class="jwpf-col-sm-8">
				<# } else if(grid == "ws-3-6-3"){ #>
					<div class="jwpf-row justify-content-center">
					<div class="jwpf-col-sm-6">
				<# } #>
				<div class="jwpf-optin-form-box jwpf-row {{ raw_wrap }}">
				
					<div class="jwpf-optin-form-info-wrap media-position-{{ data.media_position || "top" }} {{ info_wrap }}">
						<div class="jwpf-optin-form-img-wrap {{ media_class }}">
						{{{ media }}}
						</div>
						<# if(data.title || data.content){ #>
							<div class="jwpf-optin-form-details-wrap">
						<# } #>
							<# if(data.title) { #>
								<{{ data.heading_selector || "h3" }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector || "h3" }}>
							<# } #>
							<# if(data.content) { #>
								<div id="addon-text-{{data.id}}" class="jwpf-optin-form-details jw-editable-content" data-id={{data.id}} data-fieldName="content">{{{ data.content }}}</div>
							<# } #>
						<# if(data.title || data.content){ #>
							</div>
						<# } #>
					</div>
		
		
					<div class="jwpf-optin-form-content {{ form_wrap }}">
						<form class="jwpf-optin-form {{ forminline }} {{button_inside}}">
							<# if (!data.hide_name) { #>
								<div class="jwpf-form-group name-wrap">
									<input type="text" name="fname" class="jwpf-form-control" placeholder="{{ Joomla.JText._(\'COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_NAME\') }}" required="required">
								</div>
							<# } #>
		
							<div class="jwpf-form-group email-wrap">
								<input type="email" name="email" class="jwpf-form-control" placeholder="{{ Joomla.JText._(\'COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_EMAIL\') }}" required="required">
							</div>
							
							<# if (data.recaptcha) { #>
								<div class="jwpf-form-group recaptcha-wrap">
									<img src="components/com_jwpagefactory/assets/images/captcha.png" >
								</div>
							<# } #>
							
							<# if (data.show_checkbox) { #>
								<div class="jwpf-form-group">
									<div class="jwpf-form-check">
										<input class="jwpf-form-check-input" type="checkbox" name="agreement" id="agreement" required="required">
										<label class="jwpf-form-check-label" for="agreement">{{{ data.checkbox_title }}}</label>
									</div>
								</div>
							<# } #>
	
							<div class="button-wrap {{ data.button_position}}">
								<button type="submit" id="btn-{{ data.id }}" class="jwpf-btn {{ button_class }}"><i class="fa"></i> {{{ button_text }}}</button>
							</div>
		
						</form>
						<div style="display:none;margin-top:10px;" class="jwpf-optin-form-status"></div>
					</div>
		
				</div>
				<# if((grid == "ws-4-4-4") || (grid == "ws-2-8-2") || (grid == "ws-3-6-3")){ #>
					</div>
					</div>
				<# } #>
			</div>
		';

		return $output;
	}
}