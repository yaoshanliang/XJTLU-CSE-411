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
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addScriptdeclaration('var pagefactory_base="' . JURI::root() . 'administrator/";');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/pbfont.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/installer.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/notice.js');

require_once JPATH_ADMINISTRATOR . '/components/com_jwpagefactory/helpers/integrations.php';
$integrations = JwpagefactoryHelperIntegrations::integrations_list();

$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');

?>

<div class="jw-pagefactory-admin-top"></div>
<div class="jw-pagefactory-admin-notice"></div>

<div class="jw-pagefactory-admin clearfix" style="position: relative;">
	<div id="j-sidebar-container" class="span2">
		<?php echo JLayoutHelper::render('brand'); ?>
		<?php echo $this->sidebar; ?>
	</div>

	<div id="j-main-container" class="span10">
		<div class="jw-pagefactory-main-container-inner">

			<div class="jw-pagefactory-pages-toolbar clearfix"></div>

			<div class="jw-pagefactory-integrations clearfix">
				<ul class="jw-pagefactory-integrations-list clearfix">
					<?php
					foreach ($integrations as $key => $item) {
						$class = "available";

						if (count((array)$this->items)) {
							foreach ($this->items as $this->item) {
								if ($this->item->component == $key) {
									if ($this->item->state == 0) {
										$class = "installed";
									} else if ($this->item->state == 1) {
										$xml = JFactory::getXML(JPATH_SITE . '/plugins/' . $item->plugin->group . '/' . $item->plugin->name . '/' . $item->plugin->name . '.xml');
										$plug_version = (string)$xml->version;

										if ($item->version > $plug_version) {
											$class = "update";
										} else {
											$class = "enabled";
										}
									}
								}
							}
						}

						?>
						<li class="<?php echo $class; ?>" data-integration="<?php echo $key; ?>">
							<div>
								<div>
									<img src="<?php echo $item->thumb; ?>" alt="<?php echo $item->title; ?>">
									<span>
										<i class="fa fa-check-circle"></i><?php echo $item->title; ?>
										<div class="jw-pagefactory-btns">
											<a class="jw-pagefactory-btn jw-pagefactory-btn-success jw-pagefactory-btn-sm jw-pagefactory-btn-install" href="#"><i></i>Install</a>
											<a class="jw-pagefactory-btn jw-pagefactory-btn-warning jw-pagefactory-btn-sm jw-pagefactory-btn-update" href="#"><i></i>Update</a>
											<a class="jw-pagefactory-btn jw-pagefactory-btn-primary jw-pagefactory-btn-sm jw-pagefactory-btn-enable" href="#"><i></i>Enable</a>
											<a class="jw-pagefactory-btn jw-pagefactory-btn-default jw-pagefactory-btn-sm jw-pagefactory-btn-disable" href="#"><i></i>Disable</a>
											<a class="jw-pagefactory-btn jw-pagefactory-btn-danger jw-pagefactory-btn-sm jw-pagefactory-btn-uninstall" href="#"><i></i>Uninstall</a>
										</div>
									</span>
								</div>
							</div>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
