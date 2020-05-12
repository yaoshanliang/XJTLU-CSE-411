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
defined('_JEXEC') or die('Restricted access');

class JwpagefactoryAddonAjax_contact extends JwpagefactoryAddons
{

	public function render()
	{
		//CSRF
		\JHtml::_('jquery.token');

		$settings = $this->addon->settings;

		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		// Addon options
		$recipient_email = (isset($settings->recipient_email) && $settings->recipient_email) ? $settings->recipient_email : '';
		$from_email = (isset($settings->from_email) && $settings->from_email) ? $settings->from_email : '';
		$from_name = (isset($settings->from_name) && $settings->from_name) ? $settings->from_name : '';
		$show_phone = (isset($settings->show_phone) && $settings->show_phone) ? $settings->show_phone : '';
		$formcaptcha = (isset($settings->formcaptcha) && $settings->formcaptcha) ? $settings->formcaptcha : '';
		$captcha_type = (isset($settings->captcha_type)) ? $settings->captcha_type : 'default';
		$captcha_question = (isset($settings->captcha_question) && $settings->captcha_question) ? $settings->captcha_question : '';
		$captcha_answer = (isset($settings->captcha_answer) && $settings->captcha_answer) ? $settings->captcha_answer : '';
		$button_text = JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SEND');
		$use_custom_button = (isset($settings->use_custom_button) && $settings->use_custom_button) ? $settings->use_custom_button : 0;
		$show_checkbox = (isset($settings->show_checkbox) && $settings->show_checkbox) ? $settings->show_checkbox : 0;
		$checkbox_title = (isset($settings->checkbox_title) && $settings->checkbox_title) ? $settings->checkbox_title : '';
		$button_class = (isset($settings->button_type) && $settings->button_type) ? ' jwpf-btn-' . $settings->button_type : ' jwpf-btn-success';
		$button_text = (isset($settings->button_text) && $settings->button_text) ? $settings->button_text : JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SEND');
		$button_aria_text = (isset($settings->button_text) && $settings->button_text) ? $settings->button_text : JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SEND');

		$name_input_col = (isset($settings->name_input_col) && $settings->name_input_col) ? ' jwpf-col-sm-' . $settings->name_input_col : 'jwpf-col-sm-12';
		$email_input_col = (isset($settings->email_input_col) && $settings->email_input_col) ? ' jwpf-col-sm-' . $settings->email_input_col : 'jwpf-col-sm-12';
		$captcha_input_col = (isset($settings->captcha_input_col) && $settings->captcha_input_col) ? ' jwpf-col-sm-' . $settings->captcha_input_col : 'jwpf-col-sm-12';
		$subject_input_col = (isset($settings->subject_input_col) && $settings->subject_input_col) ? ' jwpf-col-sm-' . $settings->subject_input_col : 'jwpf-col-sm-12';
		$phone_input_col = (isset($settings->phone_input_col) && $settings->phone_input_col) ? ' jwpf-col-sm-' . $settings->phone_input_col : 'jwpf-col-sm-12';
		$message_input_col = (isset($settings->message_input_col) && $settings->message_input_col) ? ' jwpf-col-sm-' . $settings->message_input_col : 'jwpf-col-sm-12';

		$show_label = (isset($settings->show_label) && $settings->show_label) ? $settings->show_label : false;
		$button_position = (isset($settings->button_position) && $settings->button_position) ? $settings->button_position : 'jwpf-text-left';

		if ($use_custom_button) {
			$button_class .= (isset($settings->button_size) && $settings->button_size) ? ' jwpf-btn-' . $settings->button_size : '';
			$button_class .= (isset($settings->button_shape) && $settings->button_shape) ? ' jwpf-btn-' . $settings->button_shape : ' jwpf-btn-rounded';
			$button_class .= (isset($settings->button_appearance) && $settings->button_appearance) ? ' jwpf-btn-' . $settings->button_appearance : '';
			$button_class .= (isset($settings->button_block) && $settings->button_block) ? ' ' . $settings->button_block : '';
			$button_icon = (isset($settings->button_icon) && $settings->button_icon) ? $settings->button_icon : '';
			$button_icon_position = (isset($settings->button_icon_position) && $settings->button_icon_position) ? $settings->button_icon_position : 'left';

			$icon_arr = array_filter(explode(' ', $button_icon));
			if (count($icon_arr) === 1) {
				$button_icon = 'fa ' . $button_icon;
			}

			if ($button_icon_position == 'left') {
				$button_text = ($button_icon) ? '<span class="' . $button_icon . '" aria-hidden="true"></span> ' . $button_text : $button_text;
			} else {
				$button_text = ($button_icon) ? $button_text . ' <span class="' . $button_icon . '" aria-hidden="true"></span>' : $button_text;
			}
		}

		$output = '<div class="jwpf-addon jwpf-addon-ajax-contact ' . $class . '">';

		if ($title) {
			$output .= '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>';
		}

		$output .= '<div class="jwpf-ajax-contact-content">';
		$output .= '<form class="jwpf-ajaxt-contact-form">';
		$output .= '<div class="jwpf-row">';

		$output .= '<div class="jwpf-form-group ' . $name_input_col . '">';
		if ($show_label) {
			$output .= '<label for="name">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_NAME') . '</label>';
		}
		$output .= '<input type="text" name="name" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_NAME') . '" required="required">';
		$output .= '</div>';

		$output .= '<div class="jwpf-form-group ' . $email_input_col . '">';
		if ($show_label) {
			$output .= '<label for="email">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_EMAIL') . '</label>';
		}
		$output .= '<input type="email" name="email" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_EMAIL') . '" required="required">';
		$output .= '</div>';

		if ($show_phone) {
			$output .= '<div class="jwpf-form-group ' . $phone_input_col . '">';
			if ($show_label) {
				$output .= '<label for="phone">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_PHONE') . '</label>';
			}
			$output .= '<input type="text" name="phone" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_PHONE') . '" required="required">';
			$output .= '</div>';
		}

		$output .= '<div class="jwpf-form-group ' . $subject_input_col . '">';
		if ($show_label) {
			$output .= '<label for="subject">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SUBJECT') . '</label>';
		}
		$output .= '<input type="text" name="subject" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SUBJECT') . '" required="required">';
		$output .= '</div>';

		if ($formcaptcha && $captcha_type == 'default') {
			$output .= '<div class="jwpf-form-group ' . $captcha_input_col . '">';
			if ($show_label) {
				$output .= '<label for="captcha_question">' . $captcha_question . '</label>';
			}
			$output .= '<input type="text" name="captcha_question" class="jwpf-form-control" placeholder="' . $captcha_question . '" required="required">';
			$output .= '</div>';
		}

		$output .= '<div class="jwpf-form-group ' . $message_input_col . '">';
		if ($show_label) {
			$output .= '<label for="message">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_MESSAGE') . '</label>';
		}
		$output .= '<textarea name="message" rows="5" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_MESSAGE') . '" required="required"></textarea>';
		$output .= '</div>';

		$output .= '</div>';

		$output .= '<input type="hidden" name="recipient" value="' . base64_encode($recipient_email) . '">';
		$output .= '<input type="hidden" name="from_email" value="' . base64_encode($from_email) . '">';
		$output .= '<input type="hidden" name="from_name" value="' . base64_encode($from_name) . '">';
		$output .= '<input type="hidden" name="addon_id" value="' . $this->addon->id . '">';

		if ($formcaptcha && $captcha_type == 'default') {
			$output .= '<input type="hidden" name="captcha_answer" value="' . md5($captcha_answer) . '">';
		} elseif ($formcaptcha && $captcha_type == 'gcaptcha') {
			JPluginHelper::importPlugin('captcha', 'recaptcha');
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onInit', 'dynamic_recaptcha_' . $this->addon->id);
			$recaptcha = $dispatcher->trigger('onDisplay', array(null, 'dynamic_recaptcha_' . $this->addon->id, 'jwpf-dynamic-recaptcha'));

			$output .= (isset($recaptcha[0])) ? $recaptcha[0] : '<p class="jwpf-text-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_CAPTCHA_NOT_INSTALLED') . '</p>';
		} elseif ($formcaptcha && $captcha_type == 'igcaptcha') {
			JPluginHelper::importPlugin('captcha', 'recaptcha_invisible');
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onInit', 'invisible_recaptcha_' . $this->addon->id);
			$recaptcha = $dispatcher->trigger('onDisplay', array(null, 'invisible_recaptcha_' . $this->addon->id, 'jwpf-dynamic-recaptcha'));

			$output .= (isset($recaptcha[0])) ? $recaptcha[0] : '<p class="jwpf-text-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INVISIBLE_CAPTCHA_NOT_INSTALLED') . '</p>';
		}

		if ($show_checkbox) {
			$output .= '<div class="jwpf-form-group">';
			$output .= '<div class="jwpf-form-check">';
			$output .= '<input class="jwpf-form-check-input" type="checkbox" name="agreement" id="agreement-' . $this->addon->id . '" required="required">';
			$output .= '<label class="jwpf-form-check-label" for="agreement-' . $this->addon->id . '">' . $checkbox_title . '</label>';
			$output .= '</div>';
			$output .= '</div>';
		}

		$output .= '<input type="hidden" name="captcha_type" value="' . $captcha_type . '">';
		$output .= '<div class="' . $button_position . '">';
		$output .= '<button type="submit" id="btn-' . $this->addon->id . '" aria-label="' . strip_tags($button_aria_text) . '" class="jwpf-btn' . $button_class . '"><i class="fa" aria-hidden="true"></i>' . $button_text . '</button>';

		$output .= '</div>';
		$output .= '</form>';
		$output .= '<div style="display:none;margin-top:10px;" class="jwpf-ajax-contact-status"></div>';

		$output .= '</div>';

		$output .= '</div>';

		return $output;
	}

	public static function getAjax()
	{
		// if cache isn't enable
		if (!JFactory::getConfig()->get('caching') && !JPluginHelper::getPlugin('system', 'cache')) {
			// Check CSRF
			\JSession::checkToken() or die('Restricted Access');
		}

		// include page builder page model
		require_once JPATH_BASE . '/components/com_jwpagefactory/models/page.php';

		$input = JFactory::getApplication()->input;
		$viewid = $input->get('id', 0, 'INT');

		$mail = JFactory::getMailer();
		$message = '';
		$showcaptcha = false;

		//inputs
		$inputs = $input->get('data', array(), 'ARRAY');

		foreach ($inputs as $input) {

			if ($input['name'] == 'captcha_type') {
				$captcha_type = $input['value'];
			}

			if ($input['name'] == 'view_type') {
				$view_type = $input['value'];
			}

			if ($input['name'] == 'addon_id') {
				$addon_id = $input['value'];
			}

			if ($input['name'] == 'module_id') {
				$module_id = $input['value'];
			}

			if ($input['name'] == 'recipient') {
				$recipient = base64_decode($input['value']);
			}

			if ($input['name'] == 'from_email') {
				$from_email = base64_decode($input['value']);
			}

			if ($input['name'] == 'from_name') {
				$from_name = base64_decode($input['value']);
			}

			if ($input['name'] == 'email') {
				$email = $input['value'];
			}

			if ($input['name'] == 'name') {
				$name = $input['value'];
			}

			if ($input['name'] == 'subject') {
				$subject = $input['value'];
			}

			if ($input['name'] == 'phone') {
				$phone = $input['value'];
			}

			if ($input['name'] == 'message') {
				$message = nl2br($input['value']);
			}

			if ($input['name'] == 'captcha_question') {
				$captcha_question = $input['value'];
			}

			if ($input['name'] == 'captcha_answer') {
				$captcha_answer = $input['value'];
				$showcaptcha = true;
			}

			if ($input['name'] == 'g-recaptcha-response') {
				$gcaptcha = $input['value'];
				$showcaptcha = true;
			}
			if ($input['name'] == 'agreement') {
				$agreement = $input['value'];
			}
		}

		// get addon infos
		if ($view_type == 'module') {
			$item_data = new stdClass();
			$page_info = self::getPageInfoById($module_id, $view_type, 'new');
			// if old version of module
			if (empty($page_info)) {
				$page_info = self::getPageInfoById($module_id, $view_type);
				$item_data->text = json_encode(json_decode($page_info->params)->content);
			} else { // if new version of module
				$item_data->text = $page_info->text;
			}
		} elseif ($view_type == 'article') {
			$item_data = new stdClass();
			$item_data = self::getPageInfoById($viewid, $view_type);
		} else {
			$model = new JwpagefactoryModelPage();
			$item_data = $model->getItem($viewid);
		}

		$output = array();
		$output['status'] = false;
		$output['gcaptchaId'] = '';

		// Match has addon id
		if (self::verifyAddon($item_data->text, $addon_id) == false) {
			$output['content'] = '<span class="jwpf-text-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_FAILED') . '</span>';
			return json_encode($output);
		}

		if ($showcaptcha) {
			if ($captcha_type == 'gcaptcha' || $captcha_type == 'igcaptcha') {
				if ($gcaptcha == '') {
					$output['content'] = '<span class="jwpf-text-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INVALID_CAPTCHA') . '</span>';
					return json_encode($output);
				} else {
					if ($captcha_type == 'igcaptcha') {
						JPluginHelper::importPlugin('captcha', 'recaptcha_invisible');
						$output['gcaptchaId'] = 'invisible_recaptcha_' . $addon_id;
						$output['gcaptchaType'] = 'invisible';
					} else {
						JPluginHelper::importPlugin('captcha', 'recaptcha');
						$output['gcaptchaId'] = 'dynamic_recaptcha_' . $addon_id;
						$output['gcaptchaType'] = 'dynamic';
					}
					$dispatcher = JEventDispatcher::getInstance();
					$res = $dispatcher->trigger('onCheckAnswer', $gcaptcha);
					// if module then verify gcaptcha
					if ($view_type == 'module') {
						$res = ($gcaptcha != null || strlen($gcaptcha) != 0) ? array(true) : array(false);
					}
					if (!$res[0]) {
						$output['content'] = '<span class="jwpf-text-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INVALID_CAPTCHA') . '</span>';
						return json_encode($output);
					}
				}
			} else {
				if (md5($captcha_question) != $captcha_answer) {
					$output['content'] = '<span class="jwpf-text-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_WRONG_CAPTCHA') . '</span>';
					return json_encode($output);
				}
			}
		}

		//get sender UP
		$senderip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		// Subject Structure
		$site_name = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		$mail_subject = $subject . ' | ' . $email . ' | ' . $site_name;

		// Message structure
		$mail_body = '<div>';
		if (isset($name) && $name) {
			$mail_body .= '<p><strong>' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_NAME') . '</strong>: ' . $name . '</p>';
		}
		$mail_body .= '<p><strong>' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_EMAIL') . '</strong>: ' . $email . '</p>';
		if (isset($phone) && $phone) {
			$mail_body .= '<p><strong>' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_PHONE') . '</strong>: ' . $phone . '</p>';
		}
		if (isset($message) && $message) {
			$mail_body .= '<p><strong>' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_MESSAGE') . '</strong>: ' . $message . '</p>';
		}
		if (isset($agreement) && $agreement) {
			$mail_body .= '<p><strong>' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_TAC') . '</strong>: ' . JText::_('JYES') . '</p>';
		} else {
			$mail_body .= '<p><strong>' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_TAC') . '</strong>: ' . JText::_('JNO') . '</p>';
		}
		$mail_body .= '<p><strong>' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SENDER_IP') . '</strong>: ' . $senderip . '</p>';
		$mail_body .= '</div>';

		$sender = array($email, $name);
		if (!empty($from_email)) {
			$sender = array($from_email, $from_name);
			$mail->addReplyTo($email, $name);
		}

		$mail->setSender($sender);
		$mail->addRecipient($recipient);
		$mail->setSubject($mail_subject);
		$mail->isHTML(true);
		$mail->Encoding = 'base64';
		$mail->setBody($mail_body);

		if ($mail->Send()) {
			$output['status'] = true;
			$output['content'] = '<span class="jwpf-text-success">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SUCCESS') . '</span>';
		} else {
			$output['content'] = '<span class="jwpf-text-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_FAILED') . '</span>';
		}

		return json_encode($output);
	}

	public static function getPageInfoById($item_id, $view_type = 'page', $version = '')
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		if ($view_type == 'module') {
			if ($version == 'new') {
				$query->from($db->quoteName('#__jwpagefactory', 'a'));
				$query->where($db->quoteName('a.extension_view') . " = " . $db->quote('module'));
				$query->where($db->quoteName('a.view_id') . " = " . $db->quote((int)$item_id));
			} else {
				$query->from($db->quoteName('#__modules', 'a'));
				$query->where($db->quoteName('a.id') . " = " . $db->quote((int)$item_id));
			}
		} else if ($view_type == 'article') {
			$query->from($db->quoteName('#__jwpagefactory', 'a'));
			$query->where($db->quoteName('a.view_id') . " = " . $db->quote((int)$item_id));
		} else {
			$query->from($db->quoteName('#__jwpagefactory', 'a'));
			$query->where($db->quoteName('a.id') . " = " . $db->quote((int)$item_id));
		}
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;

		$layout_path = JPATH_ROOT . '/components/com_jwpagefactory/layouts';
		$css_path = new JLayoutFile('addon.css.button', $layout_path);
		$css = '';

		$use_custom_button = (isset($settings->use_custom_button) && $settings->use_custom_button) ? $settings->use_custom_button : 0;

		if ($use_custom_button) {
			$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $settings, 'id' => 'btn-' . $this->addon->id));
		}
		//Input Style
		$input_style = '';
		$input_style .= (isset($settings->field_bg_color) && $settings->field_bg_color) ? 'background: ' . $settings->field_bg_color . ';' : '';
		$input_style .= (isset($settings->field_color) && $settings->field_color) ? 'color: ' . $settings->field_color . ';' : '';
		$input_style .= (isset($settings->field_font_size) && $settings->field_font_size) ? 'font-size: ' . $settings->field_font_size . 'px;' : '';
		$input_style .= (isset($settings->field_border_color) && $settings->field_border_color) ? 'border-color: ' . $settings->field_border_color . ';' : '';
		$input_style .= (isset($settings->field_border_width) && trim($settings->field_border_width)) ? 'border-width: ' . $settings->field_border_width . ';' : '';
		$input_style .= (isset($settings->field_border_radius) && gettype($settings->field_border_radius) == 'string') ? 'border-radius: ' . $settings->field_border_radius . 'px;' : '';
		$input_style .= (isset($settings->field_padding) && trim($settings->field_padding)) ? 'padding: ' . $settings->field_padding . ';' : '';

		$field_margin = (isset($settings->field_margin) && trim($settings->field_margin)) ? 'margin: ' . $settings->field_margin . ';' : '';

		$input_height = (isset($settings->input_height) && $settings->input_height) ? 'height: ' . $settings->input_height . 'px;' : '';
		$textarea_height = (isset($settings->textarea_height) && $settings->textarea_height) ? 'height: ' . $settings->textarea_height . 'px;' : '';
		$field_placeholder_color = (isset($settings->field_placeholder_color) && $settings->field_placeholder_color) ? 'color: ' . $settings->field_placeholder_color . ';' : '';
		$field_hover_placeholder_color = (isset($settings->field_hover_placeholder_color) && $settings->field_hover_placeholder_color) ? 'color: ' . $settings->field_hover_placeholder_color . ';' : '';
		$field_hover_style = '';
		$field_hover_style .= (isset($settings->field_hover_bg_color) && $settings->field_hover_bg_color) ? 'background: ' . $settings->field_hover_bg_color . ';' : '';
		$field_hover_style .= (isset($settings->field_hover_color) && $settings->field_hover_color) ? 'color: ' . $settings->field_hover_color . ';' : '';
		$field_hover_style .= (isset($settings->field_focus_border_color) && $settings->field_focus_border_color) ? 'border-color: ' . $settings->field_focus_border_color . ';' : '';

		if ($input_style || $input_height) {
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group input:not(.jwpf-form-check-input) {';
			$css .= $input_style;
			$css .= $input_height;
			$css .= 'transition:.35s;';
			$css .= '}';
		}
		if ($input_style || $textarea_height) {
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form div.jwpf-form-group textarea{';
			$css .= $input_style;
			$css .= $textarea_height;
			$css .= 'transition:.35s;';
			$css .= '}';
		}
		if ($field_margin) {
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form div.jwpf-form-group {';
			$css .= $field_margin;
			$css .= '}';
		}
		if ($field_placeholder_color) {
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group input::placeholder,';
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group textarea::placeholder{';
			$css .= $field_placeholder_color;
			$css .= 'opacity: 1;';
			$css .= '}';
		}
		if ($field_hover_placeholder_color) {
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group input:hover::placeholder,';
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group textarea:hover::placeholder{';
			$css .= $field_hover_placeholder_color;
			$css .= 'opacity: 1;';
			$css .= '}';
		}
		if ($field_hover_style) {
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group input:hover,';
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group input:active,';
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group input:focus,';
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group textarea:hover,';
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group textarea:active,';
			$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group textarea:focus{';
			$css .= $field_hover_style;
			$css .= '}';
		}

		//Icon style
		$button_icon_margin = (isset($settings->button_icon_margin) && trim($settings->button_icon_margin)) ? 'margin:' . $settings->button_icon_margin . ';' : '';

		if ($button_icon_margin) {
			$css .= $addon_id . ' .jwpf-btn span {';
			$css .= $button_icon_margin;
			$css .= '}';
		}

		//Responsive
		$input_height_sm = (isset($settings->input_height_sm) && $settings->input_height_sm) ? 'height: ' . $settings->input_height_sm . 'px;' : '';
		$field_margin_sm = (isset($settings->field_margin_sm) && $settings->field_margin_sm) ? 'margin: ' . $settings->field_margin_sm . ';' : '';
		$textarea_height_sm = (isset($settings->textarea_height_sm) && $settings->textarea_height_sm) ? 'height: ' . $settings->textarea_height_sm . 'px;' : '';
		if ($input_height_sm || $textarea_height_sm || $field_margin_sm) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			if ($input_height_sm) {
				$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group input:not(.jwpf-form-check-input) {';
				$css .= $input_height_sm;
				$css .= '}';
			}
			if ($textarea_height_sm) {
				$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form div.jwpf-form-group textarea{';
				$css .= $textarea_height_sm;
				$css .= '}';
			}
			if ($field_margin_sm) {
				$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form div.jwpf-form-group{';
				$css .= $field_margin_sm;
				$css .= '}';
			}
			$css .= '}';
		}
		$input_height_xs = (isset($settings->input_height_xs) && $settings->input_height_xs) ? 'height: ' . $settings->input_height_xs . 'px;' : '';
		$field_margin_xs = (isset($settings->field_margin_xs) && $settings->field_margin_xs) ? 'margin: ' . $settings->field_margin_xs . ';' : '';
		$textarea_height_xs = (isset($settings->textarea_height_xs) && $settings->textarea_height_xs) ? 'height: ' . $settings->textarea_height_xs . 'px;' : '';
		if ($input_height_xs || $textarea_height_xs || $field_margin_xs) {
			$css .= '@media (max-width: 767px) {';
			if ($input_height_xs) {
				$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form .jwpf-form-group input:not(.jwpf-form-check-input) {';
				$css .= $input_height_xs;
				$css .= '}';
			}
			if ($textarea_height_xs) {
				$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form div.jwpf-form-group textarea{';
				$css .= $textarea_height_xs;
				$css .= '}';
			}
			if ($field_margin_xs) {
				$css .= '#jwpf-addon-' . $this->addon->id . ' .jwpf-ajaxt-contact-form div.jwpf-form-group {';
				$css .= $field_margin_xs;
				$css .= '}';
			}
			$css .= '}';
		}

		return $css;
	}

	public static function verifyAddon($pageContent, $addonId)
	{
		$addonInfo = false;
		$pageContent = json_decode($pageContent);

		foreach ($pageContent as $key => $row) {
			foreach ($row->columns as $key => $column) {
				foreach ($column->addons as $key => $addon) {
					// if direct addon
					if (($addon->id == $addonId) && ($addon->name == 'ajax_contact')) {
						return true;
						break;
					}

					// if has inner array
					if (isset($addon->columns) && count($addon->columns) && $addon->columns) {
						foreach ($addon->columns as $key => $inner_column) {
							foreach ($inner_column->addons as $key => $inner_addon) {
								if (($inner_addon->id == $addonId) && ($inner_addon->name == 'ajax_contact')) {
									return true;
									break;
								}
							}
						}
					} // END:: has inner columns

					// if repeatable addon (tab, accordion)
					$inner_items = 'jw_' . $addon->name . '_item';
					if (isset($addon->settings->$inner_items) && count($addon->settings->$inner_items) && $addon->settings->$inner_items) {
						foreach ($addon->settings->$inner_items as $inner_item) {
							if (isset($inner_item->content) && is_array($inner_item->content) && !empty($inner_item->content)) {
								foreach ($inner_item->content as $inner_addon) {
									if (($inner_addon->id == $addonId) && ($inner_addon->name == 'ajax_contact')) {
										return true;
										break;
									}
								}
							}
						}
					} // END:: repeatable addon (tab, accordion)
				}
			}
		}
		return false;
	}

	public static function getTemplate()
	{
		$output = '
			<#
				var classList = "";
				classList += " jwpf-btn-"+data.button_type;
				classList += " jwpf-btn-"+data.button_size;
				classList += " jwpf-btn-"+data.button_shape;
				if(!_.isEmpty(data.button_appearance)){
					classList += " jwpf-btn-"+data.button_appearance;
				}
		
				classList += " "+data.button_block;
		
				var modern_font_style = false;
				var button_fontstyle = data.button_fontstyle || "";
				var button_font_style = data.button_font_style || "";
		
				var button_padding = "";
				var button_padding_sm = "";
				var button_padding_xs = "";
		
				if(data.button_padding){
					if(_.isObject(data.button_padding)){
						if(data.button_padding.md.trim() !== ""){
							button_padding = data.button_padding.md.split(" ").map(item => {
								if(_.isEmpty(item)){
									return "0";
								}
								return item;
							}).join(" ")
						}
		
						if(data.button_padding.sm.trim() !== ""){
							button_padding_sm = data.button_padding.sm.split(" ").map(item => {
								if(_.isEmpty(item)){
									return "0";
								}
								return item;
							}).join(" ")
						}
		
						if(data.button_padding.xs.trim() !== ""){
							button_padding_xs = data.button_padding.xs.split(" ").map(item => {
								if(_.isEmpty(item)){
									return "0";
								}
								return item;
							}).join(" ")
						}
					} else {
						if(data.button_padding.trim() !== ""){
							button_padding = data.button_padding.split(" ").map(item => {
								if(_.isEmpty(item)){
										return "0";
								}
								return item;
							}).join(" ")
						}
					}
				}
			#>
			<style type="text/css">
	
				#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-{{ data.button_type }}{
					letter-spacing: {{ data.button_letterspace }};
					<# if(_.isObject(button_font_style) && button_font_style.underline) { #>
						text-decoration: underline;
						<# modern_font_style = true #>
					<# } #>
	
					<# if(_.isObject(button_font_style) && button_font_style.italic) { #>
						font-style: italic;
						<# modern_font_style = true #>
					<# } #>
	
					<# if(_.isObject(button_font_style) && button_font_style.uppercase) { #>
						text-transform: uppercase;
						<# modern_font_style = true #>
					<# } #>
	
					<# if(_.isObject(button_font_style) && button_font_style.weight) { #>
						font-weight: {{ button_font_style.weight }};
						<# modern_font_style = true #>
					<# } #>
	
					<# if(!modern_font_style) { #>
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
					<# } #>
				}
	
				<# if(data.button_type == "custom"){ #>
					#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom{
						color: {{ data.button_color }};
						<# if(_.isObject(data.fontsize)){ #>
							font-size: {{data.fontsize.md}}px;
						<# } else { #>
							font-size: {{data.fontsize}}px;
						<# } #>
						padding: {{ button_padding }};
						<# if(data.button_appearance == "outline"){ #>
							border-color: {{ data.button_background_color }}
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
						<# } else { #>
							background-color: {{ data.button_background_color }};
					<# } #>
				}
	
					#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom:hover{
						color: {{ data.button_color_hover }};
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
					}
					@media (min-width: 768px) and (max-width: 991px) {
						#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom{
							<# if(_.isObject(data.fontsize)){ #>
								font-size: {{data.fontsize.sm}}px;
							<# } #>
							padding: {{ button_padding_sm }};
						}
					}
					@media (max-width: 767px) {
						#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom{
							<# if(_.isObject(data.fontsize)){ #>
								font-size: {{data.fontsize.xs}}px;
							<# } #>
							padding: {{ button_padding_xs }};
						}
					}
	
				<# } #>
	
				<# if(data.field_bg_color || data.field_color || data.field_font_size || data.field_border_color || data.field_border_width || data.field_border_radius || data.field_padding || data.input_height){
				#>
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group input:not(.jwpf-form-check-input) {
						background:{{data.field_bg_color}};
						color:{{data.field_color}};
						font-size:{{data.field_font_size}}px;
						border-color:{{data.field_border_color}};
						border-width:{{data.field_border_width}};
						border-radius:{{data.field_border_radius}}px;
						padding:{{data.field_padding}};
						<# if(_.isObject(data.input_height)){ #>
							height:{{data.input_height.md}}px;
						<# } #>
						transition:.35s;
					}
				<# }
				if(data.field_bg_color || data.field_color || data.field_font_size || data.field_border_color || data.field_border_width || data.field_border_radius || data.field_padding || data.textarea_height){
				#>
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form div.jwpf-form-group textarea{
						background:{{data.field_bg_color}};
						color:{{data.field_color}};
						font-size:{{data.field_font_size}}px;
						border-color:{{data.field_border_color}};
						border-width:{{data.field_border_width}};
						border-radius:{{data.field_border_radius}}px;
						padding:{{data.field_padding}};
						<# if(_.isObject(data.textarea_height)){ #>
							height:{{data.textarea_height.md}}px;
						<# } #>
						transition:.35s;
					}
				<# }
				if(_.isObject(data.field_margin)){
				#>
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form div.jwpf-form-group {
						<# if(_.isObject(data.field_margin)){ #>
							margin:{{data.field_margin.md}};
						<# } #>
					}
				<# }
				if(data.field_placeholder_color){
				#>
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group input::placeholder,
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group textarea::placeholder{
						color:{{data.field_placeholder_color}};
						opacity: 1;
					}
				<# }
				if(data.field_hover_placeholder_color){
				#>
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group input:hover::placeholder,
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group textarea:hover::placeholder{
						color:{{data.field_hover_placeholder_color}};
						opacity: 1;
					}
				<# }
				if(data.field_hover_bg_color || data.field_hover_color || data.field_focus_border_color){
				#>
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group input:hover,
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group input:active,
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group input:focus,
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group textarea:hover,
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group textarea:active,
					#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group textarea:focus{
						background: {{data.field_hover_bg_color }};
						color:{{data.field_hover_color}};
						border-color:{{data.field_focus_border_color}};
					}
				<# }
				if(_.isObject(data.input_height) || _.isObject(data.textarea_height) || _.isObject(data.field_margin)){
				#>
					@media (min-width: 768px) and (max-width: 991px) {
						<# if(_.isObject(data.input_height)){ #>
							#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group input:not(.jwpf-form-check-input) {
								height:{{data.input_height.sm}}px;
							}
						<# }
						if(_.isObject(data.textarea_height)){
						#>
							#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form div.jwpf-form-group textarea{
								height: {{data.textarea_height.sm}}px;
							}
						<# }
						if(_.isObject(data.field_margin)){
						#>
							#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form div.jwpf-form-group {
								margin: {{data.field_margin.sm}};
							}
						<# } #>
					}
				<# }
				if(_.isObject(data.input_height) || _.isObject(data.textarea_height) || _.isObject(data.field_margin)){
				#>
					@media (max-width: 767px) {
						<# if(_.isObject(data.input_height)){ #>
							#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form .jwpf-form-group input:not(.jwpf-form-check-input) {
								height:{{data.input_height.xs}}px;
							}
						<# }
						if(_.isObject(data.textarea_height)){
						#>
							#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form div.jwpf-form-group textarea{
								height:{{data.textarea_height.xs}}px;
							}
						<# }
						if(_.isObject(data.field_margin)){
						#>
							#jwpf-addon-{{ data.id }} .jwpf-ajaxt-contact-form div.jwpf-form-group {
								margin:{{data.field_margin.xs}};
							}
						<# } #>
					}
				<# } #>
	
				<# if(data.button_type == "link"){ #>
					#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-link{
						color: {{data.link_button_color}};
						border-color: {{data.link_border_color}};
						border-width: 0 0 {{data.link_button_border_width}}px 0;
						padding: 0 0 {{data.link_button_padding_bottom}}px 0;
						text-decoration: none;
						border-radius: 0;
					}
					<# if(data.link_button_status == "hover") { #>
						#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-link:hover,
						#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-link:focus{
							color: {{data.link_button_hover_color}};
							border-color: {{data.link_button_border_hover_color}};
						}
					<# } #>
				<# } #>
				#jwpf-addon-{{ data.id }} .jwpf-btn span{
					<# if(_.isObject(data.button_icon_margin)){ #>
						margin: {{data.button_icon_margin.md}};
					<# } else { #>
						margin: {{data.button_icon_margin}};
					<# } #>
				}
	
			</style>
			<div class="jwpf-addon jwpf-addon-ajax-contact {{ data.class }}">
				<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
				<div class="jwpf-ajax-contact-content">
					<form class="jwpf-ajaxt-contact-form">
						<div class="jwpf-row">
							<div class="jwpf-form-group jwpf-col-sm-{{ data.name_input_col || 12 }}">
								<# if(data.show_label){ #>
									<label for="name">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_NAME') . '</label>
								<# } #>
								<input type="text" name="name" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_NAME') . '" required="required">
							</div>
	
							<div class="jwpf-form-group jwpf-col-sm-{{ data.email_input_col || 12 }}">
								<# if(data.show_label){ #>
									<label for="email">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_EMAIL') . '</label>
								<# } #>
								<input type="email" name="email" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_EMAIL') . '" required="required">
							</div>
							
							<# if(data.show_phone) { #>
								<div class="jwpf-form-group jwpf-col-sm-{{ data.phone_input_col || 12 }}">
									<# if(data.show_label){ #>
										<label for="subject">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_PHONE') . '</label>
									<# } #>
									<input type="text" name="phone" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_PHONE') . '" required="required">
								</div>
							<# } #>
	
							<div class="jwpf-form-group jwpf-col-sm-{{ data.subject_input_col || 12 }}">
								<# if(data.show_label){ #>
									<label for="subject">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SUBJECT') . '</label>
								<# } #>
								<input type="text" name="subject" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SUBJECT') . '" required="required">
							</div>
	
							<# if(data.formcaptcha && data.captcha_type == "default") { #>
								<div class="jwpf-form-group jwpf-col-sm-{{ data.captcha_input_col || 12 }}">
									<# if(data.show_label){ #>
										<label for="captcha_question">{{ data.captcha_question }}</label>
									<# } #>
									<input type="text" name="captcha_question" class="jwpf-form-control" placeholder="{{ data.captcha_question }}" required="required">
								</div>
							<# } #>
							<div class="jwpf-form-group jwpf-col-sm-{{ data.message_input_col || 12 }}">
								<# if(data.show_label){ #>
									<label for="message">' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_MESSAGE') . '</label>
								<# } #>
								<textarea name="message" rows="5" class="jwpf-form-control" placeholder="' . JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_MESSAGE') . '" required="required"></textarea>
							</div>
						</div>
						<# if(data.formcaptcha && data.captcha_type == "gcaptcha"){ #>
							<div class="jwpf-row">
								<div class="jwpf-form-group jwpf-col-sm-12">
									<img src="components/com_jwpagefactory/assets/images/captcha.png" >
								</div>
							</div>
						<# } #>
						<# if(data.formcaptcha && data.captcha_type == "igcaptcha"){ #>
							<div class="jwpf-row">
								<div class="jwpf-form-group jwpf-col-sm-12">
									<img src="components/com_jwpagefactory/assets/images/captcha-2.png" >
								</div>
							</div>
						<# } #>
						<# if(data.show_checkbox){ #>
							<div class="jwpf-row">
								<div class="jwpf-form-group jwpf-col-sm-12">
									<div class="jwpf-form-check">
										<input class="jwpf-form-check-input" type="checkbox" id="agreement" required="required">
										<label class="jwpf-form-check-label" for="agreement">{{{ data.checkbox_title }}}</label>
									</div>
								</div>
							</div>
						<# } 
							let iconLeft = "";
							let iconRight = "";
							let icon_arr = (typeof data.button_icon !== "undefined" && data.button_icon) ? data.button_icon.split(" ") : "";
							let icon_name = icon_arr.length === 1 ? "fa "+data.button_icon : data.button_icon;

							if(data.button_icon_position == "left" && !_.isEmpty(data.button_icon)){
								iconLeft = \'<span class="\' + icon_name + \'"></span>\';
							} else {
								iconRight = \'<span class="\' + icon_name + \'"></span>\';
							}
						#>
						<div class="jwpf-row">
							<div class="jwpf-col-sm-12 {{data.button_position}}">
								<button type="submit" id="btn-{{ data.id }}" class="jwpf-btn {{classList}}">{{{iconLeft}}} {{ data.button_text }} {{{iconRight}}}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		';

		return $output;
	}

}