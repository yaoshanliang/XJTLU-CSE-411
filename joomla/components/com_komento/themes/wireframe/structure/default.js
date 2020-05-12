Komento.ready(function($){
	// declare master namespace variable for shared values
	Komento.component = "<?php echo $component; ?>";
	Komento.cid = "<?php echo $cid; ?>";
	Komento.contentLink	= "<?php echo $contentLink; ?>";
	Komento.sort = "<?php echo $activeSort; ?>";
	Komento.loadedCount = parseInt(<?php echo count($comments); ?>);
	Komento.totalCount = parseInt(<?php echo $commentCount; ?>);
	Komento.bbcode = <?php echo $this->config->get('enable_bbcode') ? 'true' : 'false';?>;
	Komento.backgrounds = <?php echo $this->config->get('enable_backgrounds') ? 'true' : 'false';?>;
	Komento.smileys = <?php echo $this->config->get('enable_smileys') ? 'true' : 'false';?>;

	<?php  if ($this->config->get('enable_bbcode')) { ?>
	Komento.bbcodeButtons = function() {
			var settings = {
				previewParserVar: 'data',
				markupSet: [],
				resizeHandle: false,
				onTab: {
					keepDefault: false,
					replaceWith: '    '
				}
			};

			<?php if ($this->config->get('bbcode_bold')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_BOLD', true);?>",
				key:'B',
				openWith:'[b]',
				closeWith:'[/b]',
				className:'kmt-markitup-bold'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_italic')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_ITALIC', true); ?>",
				key:'I',
				openWith:'[i]',
				closeWith:'[/i]',
				className:'kmt-markitup-italic'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_underline')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_UNDERLINE', true); ?>",
				key:'U',
				openWith:'[u]',
				closeWith:'[/u]',
				className:'kmt-markitup-underline'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_bold') || $this->config->get('bbcode_italic') || $this->config->get('bbcode_underline')) { ?>
			settings.markupSet.push({separator:'---------------' });
			<?php } ?>

			<?php if ($this->config->get('bbcode_link')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_LINK', true); ?>",
				key:'L',
				openWith:'[url="[![Link:!:http://]!]"(!( title="[![Title]!]")!)]', closeWith:'[/url]',
				placeHolder: "<?php echo JText::_('COM_KOMENTO_BBCODE_LINK_TEXT', true); ?>",
				className:'kmt-markitup-link'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_picture')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_PICTURE', true); ?>",
				key:'P',
				replaceWith:'[img][![Url]!][/img]',
				className:'kmt-markitup-picture'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_video')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_VIDEO', true); ?>",
				replaceWith: function(h) {
					Komento.dialog({
						"content": Komento.ajax('site/views/bbcode/video', {"caretPosition": h.caretPosition, "element": $(h.textarea).attr('id') })
					});
				},
				className: 'kmt-markitup-video'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_link') || $this->config->get('bbcode_picture')) { ?>
			settings.markupSet.push({separator:'---------------' });
			<?php } ?>

			<?php if ($this->config->get('bbcode_bulletlist')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_BULLETLIST', true); ?>",
				openWith:'[list]\n[*]',
				closeWith:'\n[/list]',
				className:'kmt-markitup-bullet'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_numericlist')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_NUMERICLIST', true); ?>",
				openWith:'[list=[![Starting number]!]]\n[*]',
				closeWith:'\n[/list]',
				className:'kmt-markitup-numeric'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_bullet')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_BULLET', true); ?>",
				openWith:'[*]',
				className:'kmt-markitup-list'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_bulletlist') || $this->config->get('bbcode_numericlist') || $this->config->get('bbcode_bullet')) { ?>
			settings.markupSet.push({separator:'---------------' });
			<?php } ?>

			<?php if ($this->config->get('bbcode_quote')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_QUOTE', true); ?>",
				openWith:'[quote]',
				closeWith:'[/quote]',
				className:'kmt-markitup-quote'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_code')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('COM_KOMENTO_BBCODE_CODE', true); ?>",
				openWith:'[code type="xml"]',
				closeWith:'[/code]',
				className:'kmt-markitup-code'
			});
			<?php } ?>

			<?php if ($this->config->get('bbcode_gist')) { ?>
			settings.markupSet.push({
				name: "<?php echo JText::_('Gist', true); ?>",
				openWith:'[gist type="php"]',
				closeWith:'[/gist]',
				className:'kmt-markitup-gist'
			});
			<?php } ?>

			return settings;
		};
	<?php } ?>
});


Komento
.require()
.script('site/comments/wrapper', 'site/comments/list')
<?php if ($this->config->get('bbcode_code')) { ?>
.script('site/vendors/prism')
<?php } ?>

<?php if ($this->config->get('enable_ratings')) { ?>
.library('raty')
<?php } ?>
.done(function($) {

	// Implement the wrapper
	$('[data-kt-wrapper]').implement(Komento.Controller.Wrapper, {
		"total": parseInt("<?php echo $commentCount;?>"),
		"lastchecktime": '<?php echo KT::date()->toSql(); ?>',
		"initList": <?php echo $this->my->allow('read_comment') && (!isset($ajaxcall) || $ajaxcall == 0) ? 'true' : 'false';?>,
		"ratings": <?php echo $this->config->get('enable_ratings') ? 'true' : 'false'; ?>,
		"prism": <?php echo $this->config->get('bbcode_code') ? 'true' : 'false';?>
	});
});
