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

JwAddonsConfig::addonConfig(
	array(
		'type' => 'repeatable',
		'addon_name' => 'form_builder',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_DESC'),
		'category' => 'Content',
		'attr' => array(
			'general' => array(
				'admin_label' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_ADMIN_LABEL'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_ADMIN_LABEL_DESC'),
					'std' => 'Form Builder'
				),
				'class' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_CLASS'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_CLASS_DESC'),
					'std' => ''
				),
				'form_elements' => array(
					'type' => 'buttons',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_ELEMENTS'),
					'std' => 'felements',
					'values' => array(
						array(
							'label' => 'Form Elements',
							'value' => 'felements'
						),
						array(
							'label' => 'Form Styles',
							'value' => 'fstyle'
						),
						array(
							'label' => 'Email Template',
							'value' => 'ftemplate'
						)
					),
				),

				'jw_form_builder_item' => array(
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_ITEMS'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => array(
						array(
							'title' => 'First Name',
							'field_name' => 'first-name',
							'field_placeholder' => 'First Name',
							'field_type' => 'text',
							'field_width' => 50,
						),
						array(
							'title' => 'Last Name',
							'field_name' => 'last-name',
							'field_placeholder' => 'Last Name',
							'field_type' => 'text',
							'field_width' => 50,
						),
						array(
							'title' => 'Email',
							'field_name' => 'email',
							'field_placeholder' => 'Email',
							'field_type' => 'email',
							'field_is_required' => 1,
							'field_required_star' => 1,
							'field_width' => 50,
						),
						array(
							'title' => 'Subject',
							'field_name' => 'subject',
							'field_placeholder' => 'Subject',
							'field_type' => 'text',
							'field_width' => 50,
						),
						array(
							'title' => 'Message',
							'field_name' => 'message',
							'field_placeholder' => 'Message',
							'field_type' => 'textarea',
							'field_is_required' => 1,
							'field_required_star' => 1,
						),
					),
					'attr' => array(
						'field_type' => array(
							'type' => 'select',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_TYPE'),
							'values' => array(
								'text' => 'Text',
								'email' => 'Email',
								'tel' => 'Phone',
								'textarea' => 'Textarea',
								'radio' => 'Radio',
								'checkbox' => 'Checkbox',
								'select' => 'Select',
								'date' => 'Date',
								'range' => 'Range',
								'number' => 'Number',
							),
							'std' => 'text',
						),
						'title' => array(
							'type' => 'text',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_LABEL'),
							'std' => 'Item 1',
						),
						'field_name' => array(
							'type' => 'text',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NAME'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NAME_DESC'),
							'depends' => array(
								array('field_type', '!=', 'checkbox'),
							),
							'std' => 'First Name',
						),
						'field_placeholder' => array(
							'type' => 'text',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_PLACEHOLDER'),
							'depends' => array(
								array('field_type', '!=', 'radio'),
								array('field_type', '!=', 'checkbox'),
								array('field_type', '!=', 'date'),
								array('field_type', '!=', 'range'),
							),
							'std' => 'First Name',
						),
						'field_width' => array(
							'type' => 'slider',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_WIDTH'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_WIDTH_DESC'),
							'max' => 100,
							'responsive' => true,
							'std' => array('md' => 50, 'sm' => 50, 'xs' => 50),
						),
						'tel_pattern' => array(
							'type' => 'text',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_TEL_PATTERN'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_TEL_PATTERN_DESC'),
							'depends' => array(
								array('field_type', '=', 'tel'),
							),
							'std' => '^\+(?:[0-9]●?){6,14}[0-9]$'
						),
						'range_min' => array(
							'type' => 'number',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_RANGE_MIN'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_RANGE_MIN_DESC'),
							'depends' => array(
								array('field_type', '=', 'range'),
							),
						),
						'range_max' => array(
							'type' => 'number',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_RANGE_MAX'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_RANGE_MAX_DESC'),
							'depends' => array(
								array('field_type', '=', 'range'),
							),
						),
						'range_step' => array(
							'type' => 'number',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_RANGE_STEP'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_RANGE_STEP_DESC'),
							'depends' => array(
								array('field_type', '=', 'range'),
							),
						),
						'number_min' => array(
							'type' => 'number',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NUMBER_MIN'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NUMBER_MIN_DESC'),
							'depends' => array(
								array('field_type', '=', 'number'),
							),
						),
						'number_max' => array(
							'type' => 'number',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NUMBER_MAX'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NUMBER_MAX_DESC'),
							'depends' => array(
								array('field_type', '=', 'number'),
							),
						),
						'number_step' => array(
							'type' => 'number',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NUMBER_STEP'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NUMBER_STEP_DESC'),
							'depends' => array(
								array('field_type', '=', 'number'),
							),
						),
						'jw_form_builder_inner_item_radio' => array(
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_RADIO_ITEMS'),
							'type' => 'repeatable',
							'depends' => array(
								array('field_type', '=', 'radio'),
							),
							'attr' => array(
								//inner item title
								'title' => array(
									'type' => 'text',
									'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_RADIO'),
									'std' => 'Radio',
								),
								'is_radio_checked' => array(
									'type' => 'checkbox',
									'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_IS_CHECKED'),
									'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_IS_CHECKED_DESC'),
									'std' => 0,
								),
							),
						),
						'jw_form_builder_inner_item_checkbox' => array(
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_CHECKBOX_ITEMS'),
							'type' => 'repeatable',
							'depends' => array(
								array('field_type', '=', 'checkbox'),
							),
							'attr' => array(
								//Admin label
								'title' => array(
									'type' => 'text',
									'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_CHECKBOX'),
									'std' => 'Checkbox',
								),
								'checkbox_field_name' => array(
									'type' => 'text',
									'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NAME'),
									'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_NAME_DESC'),
									'std' => 'checkbox-1',
								),
								'checkbox_is_required' => array(
									'type' => 'checkbox',
									'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_IS_REQUIRED'),
									'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_IS_REQUIRED_DESC'),
									'std' => 0,
								),
								'is_checkbox_checked' => array(
									'type' => 'checkbox',
									'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_IS_CHECKED'),
									'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_IS_CHECKED_DESC'),
									'std' => 0,
								),
							),
						),
						'jw_form_builder_inner_item_select' => array(
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_SELECT_ITEMS'),
							'type' => 'repeatable',
							'depends' => array(
								array('field_type', '=', 'select'),
							),
							'attr' => array(
								//inner item title
								'title' => array(
									'type' => 'text',
									'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_SELECT'),
									'std' => 'Select',
								),
								'is_selected' => array(
									'type' => 'checkbox',
									'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_IS_SELECT'),
									'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_IS_SELECT_DESC'),
									'std' => 0,
								),
							),
						),
						'is_resize' => array(
							'type' => 'checkbox',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_IS_RESIZE'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_IS_RESIZE_DESC'),
							'depends' => array(
								array('field_type', '=', 'textarea'),
							),
							'std' => 0,
						),
						'field_is_required' => array(
							'type' => 'checkbox',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_IS_REQUIRED'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_IS_REQUIRED_DESC'),
							'depends' => array(
								array('field_type', '!=', 'checkbox'),
							),
							'std' => 0,
						),
						'field_required_star' => array(
							'type' => 'checkbox',
							'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_REQUIRED_STAR'),
							'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FIELD_REQUIRED_STAR_DESC'),
							'depends' => array(
								array('title', '!=', ''),
							),
							'std' => 1,
						),
					),
				),
				//Email template
				'recipient_email' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_RECIPIENT_EMAIL'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_RECIPIENT_EMAIL_DESC'),
					'depends' => array(
						array('form_elements', '=', 'ftemplate'),
					),
				),
				'additional_header' => array(
					'type' => 'textarea',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_ADDITIONAL_HEADER'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_ADDITIONAL_HEADER_DESC'),
					'depends' => array(
						array('form_elements', '=', 'ftemplate'),
					),
					'std' => 'Reply-To: {{email}}
Reply-name: {{first-name}} {{last-name}}
Cc: {{email}}
Bcc: admin@yourcompany.com'
				),
				'from' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_FORM_EMAIL'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_FORM_EMAIL_DESC'),
					'depends' => array(
						array('form_elements', '=', 'ftemplate'),
					),
					'std' => 'Your Name: admin@example.com'
				),
				'email_subject' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SUBJECT'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_SUBJECT_DESC'),
					'depends' => array(
						array('form_elements', '=', 'ftemplate'),
					),
					'std' => '{{subject}} | {{email}} | {{site-name}}'
				),
				'email_template' => array(
					'type' => 'textarea',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FORM_TEMPLATE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FORM_TEMPLATE_DESC'),
					'depends' => array(
						array('form_elements', '=', 'ftemplate'),
					),
					'std' => '<p><strong>From:</strong>{{first-name}} {{last-name}}</p>
<p><strong>Email:</strong>{{email}}</p>
<p><strong>Subject:</strong>{{subject}}</p>
<p><strong>Message:</strong>{{message}}</p>'
				),
				//From & recipeint Option
				'email_separator' => array(
					'type' => 'separator',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_EMAIL_SEPARATOR'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
				),
				'required_field_message' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_REQUIRED_MESSAGE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_REQUIRED_MESSAGE_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => 'Please fill the required field.'
				),
				'success_message' => array(
					'type' => 'textarea',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_SUCCESS_MESSAGE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_SUCCESS_MESSAGE_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => 'Email successfully sent!'
				),
				'failed_message' => array(
					'type' => 'textarea',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FAILED_MESSAGE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_FAILED_MESSAGE_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => 'Email sent failed, fill required field and try again!'
				),
				//Redirect option
				'others_separator' => array(
					'type' => 'separator',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_OTHERS_SEPARATOR'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
				),
				'enable_redirect' => array(
					'type' => 'checkbox',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_REDIRECT'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_REDIRECT_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => 0,
				),
				'redirect_url' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_REDIRECT_URL'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_REDIRECT_URL_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
						array('enable_redirect', '=', 1),
					),
				),
				'enable_captcha' => array(
					'type' => 'checkbox',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_SHOW_RECAPTCHA'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_SHOW_RECAPTCHA_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => 1,
				),
				'captcha_type' => array(
					'type' => 'select',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CAPTCHA_TYPE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CAPTCHA_TYPE_DESC'),
					'values' => array(
						'default' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CAPTCHA_TYPE_DEFAULT'),
						'gcaptcha' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CAPTCHA_TYPE_GCHAPTCHA'),
						'igcaptcha' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CAPTCHA_TYPE_INVISIBLE_GCHAPTCHA'),
					),
					'std' => 'default',
					'depends' => array(
						array('form_elements', '=', 'felements'),
						array('enable_captcha', '=', 1),
					),
				),
				'captcha_question' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_CAPTCHA_QUESTION'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_CAPTCHA_QUESTION_DESC'),
					'std' => '3 + 4 = ?',
					'depends' => array(
						array('form_elements', '=', 'felements'),
						array('enable_captcha', '=', 1),
						array('captcha_type', '=', 'default'),
					),
				),
				'captcha_answer' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_CAPTCHA_ANSWER'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_CAPTCHA_ANSWER_DESC'),
					'std' => '7',
					'depends' => array(
						array('form_elements', '=', 'felements'),
						array('enable_captcha', '=', 1),
						array('captcha_type', '=', 'default'),
					),
				),
				'enable_policy' => array(
					'type' => 'checkbox',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_POLICY'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_POLICY_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => 0,
				),
				'policy_text' => array(
					'type' => 'textarea',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_POLICY_TEXT'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_POLICY_TEXT_DESC'),
					'std' => 'I agree with the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a> and I declare that I have read the information that is required in accordance with <a href="http://eur-lex.europa.eu/legal-content/EN/TXT/?uri=uriserv:OJ.L_.2016.119.01.0001.01.ENG&amp;toc=OJ:L:2016:119:TOC" target="_blank">Article 13 of GDPR.</a>',
					'depends' => array(
						array('form_elements', '=', 'felements'),
						array('enable_policy', '=', 1),
					),
				),
				//Button Options
				'button_separator' => array(
					'type' => 'separator',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_BTN_SEPARATOR'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
				),
				'btn_text' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_TEXT'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_TEXT_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => 'Send Message',
				),
				'btn_position' => array(
					'type' => 'select',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_POSITION'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'values' => array(
						'jwpf-text-left' => JText::_('COM_JWPAGEFACTORY_GLOBAL_LEFT'),
						'jwpf-text-center' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CENTER'),
						'jwpf-text-right' => JText::_('COM_JWPAGEFACTORY_GLOBAL_RIGHT'),
					),
					'std' => 'jwpf-text-left',
				),
				'btn_icon' => array(
					'type' => 'icon',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_ICON'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_ICON_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
				),
				'btn_icon_position' => array(
					'type' => 'select',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_ICON_POSITION'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'values' => array(
						'left' => JText::_('COM_JWPAGEFACTORY_GLOBAL_LEFT'),
						'right' => JText::_('COM_JWPAGEFACTORY_GLOBAL_RIGHT'),
					),
					'std' => 'left',
				),
				//Form style
				'jw_form_style_option' => array(
					'type' => 'buttons',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_STYLE'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
					),
					'std' => 'field_style',
					'values' => array(
						array(
							'label' => 'Field Style',
							'value' => 'field_style'
						),
						array(
							'label' => 'Label Style',
							'value' => 'label_style'
						),
						array(
							'label' => 'Button Style',
							'value' => 'btn_style'
						)
					),
				),
				'field_gutter' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_STYLE_GUTTER'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_STYLE_GUTTER_DESC'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
					'max' => 200,
					'responsive' => true,
					'std' => array('md' => 15, 'sm' => 15, 'xs' => 15),
				),
				'field_horizontal_space' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_STYLE_HORI_GAP'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_STYLE_HORI_GAP_DESC'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
					'max' => 200,
					'responsive' => true,
					'std' => array('md' => '', 'sm' => '', 'xs' => ''),
				),

				'field_bg_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_BGCOLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'field_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'field_font_size' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_FONTSIZE'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
					'max' => 200,
					'responsive' => true,
					'std' => array('md' => '', 'sm' => '', 'xs' => ''),
				),
				'field_placeholder_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_PLACEHOLDER_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'input_height' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_HEIGHT'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
					'max' => 200,
					'std' => '',
					'responsive' => true,
					'std' => array('md' => '', 'sm' => '', 'xs' => ''),
				),
				'field_border_width' => array(
					'type' => 'margin',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_WIDTH'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_WIDTH_DESC'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'field_border_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_BORDER_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'field_border_radius' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_BORDER_RADIUS'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
					'max' => 200,
				),
				'textarea_height' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_TEXTAREA_HEIGHT'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
					'max' => 1000,
					'responsive' => true,
					'std' => array('md' => '', 'sm' => '', 'xs' => ''),
				),
				'field_padding' => array(
					'type' => 'padding',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_PADDING'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_PADDING_DESC'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
					'responsive' => true,
				),
				//Checkbox & radio color option
				'checkbox_style_separator' => array(
					'type' => 'separator',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_CHECKBOX_OPTION'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'checkbox_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_CHECKBOX_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'radio_style_separator' => array(
					'type' => 'separator',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_RADIO_OPTION'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'radio_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_RADIO_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				//Hover
				'field_hover_separator' => array(
					'type' => 'separator',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_GLOBAL_HOVER_OPTIONS'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'field_hover_bg_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_HOVER_BG_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'field_focus_border_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_FOCUS_BORDER_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),
				'field_hover_placeholder_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AJAX_CONTACT_INPUT_HOVER_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'field_style'),
					),
				),

				//Label style
				'label_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_LABEL_COLOR'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'label_style'),
					),
				),
				'label_font_size' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_LABEL_FONTSIZE'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'label_style'),
					),
					'max' => 200,
					'responsive' => true,
					'std' => array('md' => '', 'sm' => '', 'xs' => ''),
				),
				'label_font_style' => array(
					'type' => 'fontstyle',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_FONT_STYLE'),
					'depends' => array('use_custom_button' => 1)
				),
				'label_margin' => array(
					'type' => 'margin',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MARGIN'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MARGIN_DESC'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'label_style'),
					),
					'responsive' => true,
				),

				//Button style options
				'btn_type' => array(
					'type' => 'select',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_STYLE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_STYLE_DESC'),
					'values' => array(
						'default' => JText::_('COM_JWPAGEFACTORY_GLOBAL_DEFAULT'),
						'primary' => JText::_('COM_JWPAGEFACTORY_GLOBAL_PRIMARY'),
						'secondary' => JText::_('COM_JWPAGEFACTORY_GLOBAL_SECONDARY'),
						'success' => JText::_('COM_JWPAGEFACTORY_GLOBAL_SUCCESS'),
						'info' => JText::_('COM_JWPAGEFACTORY_GLOBAL_INFO'),
						'warning' => JText::_('COM_JWPAGEFACTORY_GLOBAL_WARNING'),
						'danger' => JText::_('COM_JWPAGEFACTORY_GLOBAL_DANGER'),
						'dark' => JText::_('COM_JWPAGEFACTORY_GLOBAL_DARK'),
						'link' => JText::_('COM_JWPAGEFACTORY_GLOBAL_LINK'),
						'custom' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CUSTOM'),
					),
					'std' => 'primary',
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_appearance' => array(
					'type' => 'select',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_APPEARANCE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_APPEARANCE_DESC'),
					'values' => array(
						'' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_APPEARANCE_FLAT'),
						'gradient' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_APPEARANCE_GRADIENT'),
						'outline' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_APPEARANCE_OUTLINE'),
						'3d' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_APPEARANCE_3D'),
					),
					'std' => '',
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_size' => array(
					'type' => 'select',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SIZE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SIZE_DESC'),
					'values' => array(
						'' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SIZE_DEFAULT'),
						'lg' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SIZE_LARGE'),
						'xlg' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SIZE_XLARGE'),
						'sm' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SIZE_SMALL'),
						'xs' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SIZE_EXTRA_SAMLL'),
					),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('btn_type', '=', 'custom'),
						array('jw_form_style_option', '=', 'btn_style'),
					),
					'std' => ''
				),
				'btn_shape' => array(
					'type' => 'select',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SHAPE'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SHAPE_DESC'),
					'values' => array(
						'rounded' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SHAPE_ROUNDED'),
						'square' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SHAPE_SQUARE'),
						'round' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_SHAPE_ROUND'),
					),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					),
					'std' => 'rounded'
				),
				'btn_fontsize' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_FONT_SIZE'),
					'std' => array('md' => 16),
					'responsive' => true,
					'max' => 400,
					'depends' => array(
						array('btn_type', '=', 'custom'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_font_family' => array(
					'type' => 'fonts',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_GLOBAL_FONT_FAMILY'),
					'selector' => array(
						'type' => 'font',
						'font' => '{{ VALUE }}',
						'css' => '.jwpf-btn { font-family: "{{ VALUE }}"; }'
					),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_font_style' => array(
					'type' => 'fontstyle',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_FONT_STYLE'),
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),

				'btn_letterspace' => array(
					'type' => 'select',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_LETTER_SPACING'),
					'values' => array(
						'-10px' => '-10px',
						'-9px' => '-9px',
						'-8px' => '-8px',
						'-7px' => '-7px',
						'-6px' => '-6px',
						'-5px' => '-5px',
						'-4px' => '-4px',
						'-3px' => '-3px',
						'-2px' => '-2px',
						'-1px' => '-1px',
						'0px' => 'Default',
						'1px' => '1px',
						'2px' => '2px',
						'3px' => '3px',
						'4px' => '4px',
						'5px' => '5px',
						'6px' => '6px',
						'7px' => '7px',
						'8px' => '8px',
						'9px' => '9px',
						'10px' => '10px'
					),
					'std' => '0px',
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),

				'button_status' => array(
					'type' => 'buttons',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_ENABLE_BACKGROUND_OPTIONS'),
					'std' => 'normal',
					'values' => array(
						array(
							'label' => 'Normal',
							'value' => 'normal'
						),
						array(
							'label' => 'Hover',
							'value' => 'hover'
						),
					),
					'tabs' => true,
					'depends' => array(
						array('btn_type', '=', 'custom'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_background_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_BACKGROUND_COLOR'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_BACKGROUND_COLOR_DESC'),
					'std' => '#EF6D00',
					'depends' => array(
						array('btn_appearance', '!=', 'gradient'),
						array('btn_type', '=', 'custom'),
						array('button_status', '=', 'normal'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_background_gradient' => array(
					'type' => 'gradient',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_GRADIENT'),
					'std' => array(
						"color" => "#B4EC51",
						"color2" => "#429321",
						"deg" => "45",
						"type" => "linear"
					),
					'depends' => array(
						array('btn_appearance', '=', 'gradient'),
						array('btn_type', '=', 'custom'),
						array('button_status', '=', 'normal'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_COLOR'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_COLOR_DESC'),
					'std' => '#FFFFFF',
					'depends' => array(
						array('btn_type', '=', 'custom'),
						array('button_status', '=', 'normal'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					),
				),
				'btn_background_color_hover' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_BACKGROUND_COLOR_HOVER'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_BACKGROUND_COLOR_HOVER_DESC'),
					'std' => '#de6906',
					'depends' => array(
						array('btn_appearance', '!=', 'gradient'),
						array('btn_type', '=', 'custom'),
						array('button_status', '=', 'hover'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_background_gradient_hover' => array(
					'type' => 'gradient',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_GRADIENT'),
					'std' => array(
						"color" => "#429321",
						"color2" => "#B4EC51",
						"deg" => "45",
						"type" => "linear"
					),
					'depends' => array(
						array('btn_appearance', '=', 'gradient'),
						array('btn_type', '=', 'custom'),
						array('button_status', '=', 'hover'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_color_hover' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_COLOR_HOVER'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_COLOR_HOVER_DESC'),
					'std' => '#FFFFFF',
					'depends' => array(
						array('btn_type', '=', 'custom'),
						array('button_status', '=', 'hover'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					),
				),
				'link_button_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_COLOR'),
					'std' => '',
					'depends' => array(
						array('btn_type', '=', 'link'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'link_button_border_width' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_WIDTH'),
					'max' => 30,
					'std' => '',
					'depends' => array(
						array('btn_type', '=', 'link'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'link_border_color' => array(
					'type' => 'color',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_COLOR'),
					'std' => '',
					'depends' => array(
						array('btn_type', '=', 'link'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'link_button_padding_bottom' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BUTTON_PADDING_BOTTOM'),
					'max' => 100,
					'std' => '',
					'depends' => array(
						array('btn_type', '=', 'link'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					)
				),
				'btn_margin' => array(
					'type' => 'margin',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MARGIN'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MARGIN_DESC'),
					'placeholder' => '10px 10px 10px 10px',
					'depends' => array(
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					),
					'responsive' => true,
					'std' => '0px 0px 0px 0px',
				),
				'btn_padding' => array(
					'type' => 'padding',
					'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_PADDING'),
					'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_PADDING_DESC'),
					'std' => '',
					'depends' => array(
						array('btn_type', '=', 'custom'),
						array('form_elements', '=', 'fstyle'),
						array('jw_form_style_option', '=', 'btn_style'),
					),
					'responsive' => true,
					'std' => '8px 22px 10px 22px',
				),

				'btn_class' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_BTN_CLASS'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_FORM_BUILDER_BTN_CLASS_DESC'),
					'depends' => array(
						array('form_elements', '=', 'felements'),
					),
					'std' => ''
				),
			),
		),
	)
);
