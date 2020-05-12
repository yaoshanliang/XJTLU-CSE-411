<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="kt-form-section" data-kt-form>

	<a name="commentform" id="commentform"></a>

	<?php if (isset($options['lock']) && $options['lock']) { ?>
		<div class="kt-locked-wrap">
			<i class="fa fa-lock"></i>&nbsp; <?php echo JText::_('COM_KOMENTO_FORM_LOCKED'); ?>
		</div>
	<?php } ?>

	<?php if (!isset($options['lock']) || !$options['lock']) { ?>

		<?php if ($this->my->allow('add_comment') || ($this->my->guest && $this->config->get('enable_login_form'))) { ?>
		<div class="formArea kmt-form-area">

			<?php if ($this->my->allow('add_comment')) { ?>
				<h3 class="kt-form-title"><?php echo JText::_('COM_KOMENTO_FORM_LEAVE_YOUR_COMMENTS');?></h3>
				
				<div class="kt-form-header">
					<div class="o-flag">
						<div class="o-flag__image">
							<?php echo $this->html('html.avatar', $this->my); ?>
						</div>
						<div class="o-flag__body">
							<ol class="g-list-inline g-list-inline--delimited kt-form-header__list">
								<li>
									<?php if ($this->my->guest) { ?>
										<?php echo JText::_('COM_KOMENT_POSTING_AS_GUEST');?>

										<?php if ($allowRegistration) { ?>
											<?php echo JText::sprintf('COM_KOMENT_LOGIN_LINK', KT::login()->getRegistrationLink(), KT::login()->getLoginLink());?>
										<?php } ?>

									<?php } else { ?>
										<?php echo JText::sprintf('COM_KOMENT_POSTING_AS', $this->html('html.name', $this->my->id)); ?>
									<?php } ?>
								</li>
							</ol>
						</div>
					</div>
				</div>
			<?php } ?>

			<?php if ($this->my->guest && $this->config->get('enable_login_form')) { ?>
				<?php echo KT::login()->getLoginForm();?>
			<?php } ?>

			<?php if ($this->my->allow('add_comment')) { ?>
				<form class="kt-form" data-kt-form-element>

					<?php if ($showHeaders) { ?>
					<div class="o-grid o-grid--gutters t-lg-mt--xl">

						<?php if ($showNameField) { ?>
						<div class="o-grid__cell">
							<?php echo $this->html('form.floatinglabel', JText::_('COM_KOMENTO_FORM_NAME') . ($requireNameField ? ' (' . JText::_('COM_KOMENTO_FORM_REQUIRED') . ')' : ''), 'name', 'textbox', !$this->my->guest ? $this->escape($this->my->name) : ''); ?>
						</div>
						<?php } ?>

						<?php if ($showEmailField) { ?>
						<div class="o-grid__cell">
							<?php echo $this->html('form.floatinglabel', JText::_('COM_KOMENTO_FORM_EMAIL') . ($requireEmailField ? ' (' . JText::_('COM_KOMENTO_FORM_REQUIRED') . ')' : ''), 'email', 'textbox', !$this->my->guest ? $this->escape($this->my->email) : ''); ?>
						</div>
						<?php } ?>

						<?php if ($showWebsiteField) { ?>
						<div class="o-grid__cell">
							<?php echo $this->html('form.floatinglabel', JText::_('COM_KOMENTO_FORM_WEBSITE') . ($requireWebsiteField ? ' (' . JText::_('COM_KOMENTO_FORM_REQUIRED') . ')' : ''), 'url'); ?>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
					
					<div class="kt-form-alert o-alert o-alert--danger t-lg-mt--md t-hidden" data-kt-alert></div>

					<div class="kt-form-composer">
						<div class="kt-form-editor-wrap">
							<?php echo $this->output('site/form/editor'); ?>

							<?php if ($this->config->get('enable_ratings') || $this->config->get('antispam_min_length_enable') || $this->config->get('antispam_max_length_enable')) { ?>
								<div class="kt-editor-info">
									<div class="kt-editor-action">
										<?php if ($this->config->get('enable_ratings')) { ?>
										<div class="kt-editor-action__ratings">
											<?php echo $this->output('site/ratings/form'); ?>
										</div>
										<?php } ?>
										<?php if ($this->config->get('antispam_min_length_enable') || $this->config->get('antispam_max_length_enable')) { ?>
										<div class="kt-editor-action__char-count">
											<span><b data-kt-text-counter>0</b> <?php echo JText::_('COM_KOMENTO_FORM_CHARACTERS_COUNTER'); ?></span>
										</div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>

							<div class="kt-editor-info"
								<?php echo $this->my->canUploadAttachments() ? 'data-kt-attachments' : '';?>
								<?php echo $this->my->canShareLocation() ? 'data-kt-location' : '';?>
							>
								<div class="kt-editor-action">
									<?php if ($this->my->canUploadAttachments()) { ?>
									<div class="kt-editor-action__attach" data-kt-attachments-form>
										<a href="javascript:void(0);" class="kt-editor-action__btn kt-upload-btn" data-kt-attachments-button
											data-kt-provide="tooltip"
											data-original-title="<?php echo JText::sprintf('COM_KOMENTO_FORM_EXTENSION_ALLOWED_LIST', implode(', ', array_map('trim', explode(',', $this->config->get('upload_allowed_extension'))))); ?>"
										>
										<i class="fa fa-paperclip"></i> <?php echo JText::_('COM_KT_UPLOAD_ATTACHMENT'); ?>
										</a>

										<div class="kt-attachments-list__item t-hidden" data-kt-attachments-item data-template>
											<div class="kt-attachments-item">

												<div class="kt-attachments-item__preview">
													<div class="kt-attachments-preview is-icon" data-preview-type>
														<div class="kt-attachments-preview__content"></div>
													</div>
												</div>

												<div class="kt-attachments-item__content">
													<a href="javascript:void(0);" class="kt-attachments-item__name" data-title></a>
													<div class="kt-attachments-item__size" ><span data-size></span> kb</div>
												</div>
												<div class="kt-attachments-item__actions">
													<a href="javascript:void(0);" data-kt-attachments-item-remove><i class="fa fa-trash-o"></i></a>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>

									<?php if ($this->my->canShareLocation()) { ?>
									<div class="kt-editor-action__location">
										<a href="javascript:void(0);" class="kt-editor-action__btn" data-kt-location-button>
											<i class="fa fa-map-marker"></i> <?php echo JText::_('COM_KT_SHARE_LOCATION');?>
										</a>
									</div>
									<?php } ?>

								</div>

								<div class="kt-editor-data">

									<div class="kt-editor-attachments t-hidden" data-kt-attachments-wrapper>
										<div class="kt-editor-attachments__title kt-editor-data__title" data-kt-attachments-title>
											<?php echo JText::_('COM_KOMENTO_COMMENT_ATTACHMENTS');?> (<span class="fileCounter" data-kt-attachments-counter>0</span> / <?php echo $this->config->get('upload_max_file'); ?>)
										</div>
										<div class="kt-attachments-list" data-kt-attachments-list>
										</div>
									</div>


									<div class="kt-editor-data__location t-hidden" data-kt-location-form>
										<div class="kt-editor-data__title"><?php echo JText::_('COM_KOMENTO_SHARE_LOCATION');?></div>
										<div class="kt-editor-data__location-input">
											<div class="o-input-group">
												<input type="text" name="address" placeholder="<?php echo JText::_('COM_KOMENTO_COMMENT_WHERE_ARE_YOU');?>" data-kt-location-address class="o-form-control"/>
												<span class="o-input-group__btn">

													<button type="button" class="btn btn-kt-default-o" data-kt-location-detect><i class="fa fa-map-marker"></i><span class="o-loader o-loader--sm"></span></button>
													<button class="btn btn-kt-danger-o" type="button" data-kt-location-remove><i class="fa fa-times"></i></button>
												</span>
											</div>
										</div>
										<input type="hidden" name="latitude" data-kt-location-lat />
										<input type="hidden" name="longitude" data-kt-location-lng />
									</div>
								</div>
							</div>
						</div>
					</div>

					

					<?php if ($showCaptcha) { ?>
					<div class="kt-form-captcha">
						<?php echo KT::captcha()->html(); ?>
					</div>
					<?php } ?>

					<div class="kt-form-submit">
						<div class="kt-form-submit__cell">
							<?php if ($showSubscribe) { ?>
							<div class="subscribeForm kmt-form-subscription <?php echo $subscription !== null ? 'subscribed' : '';?>">
								<?php if (!is_null($subscription) && $this->my->id) { ?>
									<div>
										<?php echo JText::_($subscription ? 'COM_KOMENTO_FORM_ALREADY_SUBSCRIBE' : 'COM_KOMENTO_FORM_SUBSCRIBE_PENDING'); ?>
										<a href="javascript:void(0);" data-kt-unsubscribe><?php echo JText::_('COM_KOMENTO_FORM_UNSUBSCRIBE'); ?></a>
									</div>
								<?php } else { ?>
									<div class="o-checkbox">
										<input type="checkbox" name="subscribe" id="subscribe-comments" value="1" <?php echo !is_null($subscription) ? 'checked' : '';?>>
										<label for="subscribe-comments">
											<?php echo JText::_('COM_KOMENTO_FORM_ALSO_SUBSCRIBE_TO_THIS_THREAD'); ?>
										</label>
									</div>
								<?php } ?>
							</div>
							<?php } ?>

							<?php if ($showTerms) { ?>
							<div class="o-checkbox">
								<input type="checkbox" name="tnc" id="kt-terms" value="1" class="input checkbox" data-kt-terms />
								<label for="kt-terms">
									<?php echo JText::_('COM_KOMENTO_FORM_AGREE_TNC'); ?>
									<a href="javascript:void(0);" class="kmt-tnc-read" data-kt-tnc-view><?php echo JText::_('COM_KOMENTO_FORM_READ_TNC'); ?></a>.
								</label>
							</div>
							<?php } ?>
						</div>

						<div class="kt-form-submit__cell">
							<button type="button" class="btn btn-kt-default btn-kt-cancel" data-kt-cancel><?php echo JText::_('COM_KOMENTO_FORM_CANCEL'); ?></button>

							<button type="button" class="btn btn-kt-primary" data-kt-submit>
								<?php echo JText::_('COM_KOMENTO_FORM_SUBMIT'); ?>
								<span class="o-loader o-loader--sm"></span>
							</button>
						</div>
					</div>

					<input type="hidden" name="parent_id" value="0" data-kt-parent />
					<input type="hidden" name="task" value="commentSave" />
					<input type="hidden" name="pageItemId" class="pageItemId" value="<?php echo JRequest::getInt('Itemid'); ?>" />
				</form>
			<?php } ?>
		</div>
		<?php } ?>
	<?php } ?>
</div>
