
<?php if (!$options['lock'] && $this->my->allow('add_comment')) { ?>
Komento.require()
.script('site/form/form')
.done(function($) {

	$('[data-kt-form]').implement(Komento.Controller.Form, {
		"location": <?php echo $this->my->canShareLocation() ? 'true' : 'false';?>,
		"location_key": "<?php echo $this->config->get('location_key');?>",
		"attachments": {
			"enabled": <?php echo $this->my->canUploadAttachments() ? 'true' : 'false';?>,
			"upload_max_size": "<?php echo $this->config->get('upload_max_size');?>mb",
			"upload_max_files": "<?php echo $this->config->get('upload_max_file');?>",
			"extensions": "<?php echo $this->config->get('upload_allowed_extension');?>"
		},
		"bbcode": Komento.bbcode,
		"showCode": <?php echo $this->config->get('bbcode_code') ? 'true' : 'false'; ?>,
		"showCaptcha": <?php echo $showCaptcha ? 'true' : 'false'; ?>,
		"recaptcha": <?php echo $this->config->get('antispam_captcha_type') == 1 && $this->config->get('antispam_captcha_enable') ? 'true' : 'false';?>,
		"recaptcha_invisible": <?php echo $this->config->get('antispam_recaptcha_invisible') && $this->config->get('antispam_captcha_type') == 1 ? 'true' : 'false';?>,
		"markupSet": Komento.bbcodeButtons
	});
});
<?php } ?>