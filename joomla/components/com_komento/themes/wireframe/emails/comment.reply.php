<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;" class="email-container">


	<!-- 1 Column Text + Button : BEGIN -->
	<tr>
		<td bgcolor="#ffffff">
			<table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td style="padding: 20px 20px 20px; text-align: left;">
						<h1 style="margin: 0; font-family: sans-serif; font-size: 22px; line-height: 27px; color: #666666; font-weight: normal;"><?php echo JText::sprintf('COM_KOMENTO_EMAILS_REPLY_HEADING', ucfirst($commentAuthorName));?></h1>
					</td>
				</tr>

			</table>
		</td>
	</tr>
	<!-- 1 Column Text + Button : END -->



	<!-- 1 Column Text + Button : BEGIN -->
	<tr>
		<td bgcolor="#ffffff">
			<table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="100%">

				<tr>
					<td style="padding: 20px 20px; font-family: sans-serif; font-size: 14px; line-height: 20px; color: #555555; text-align: left;">
						<p style="margin: 0;"><?php echo JText::sprintf('COM_KOMENTO_EMAILS_NEW_AUTHOR_REPLY', ucfirst($commentAuthorName)); ?></p>
					</td>
				</tr>

				<tr>
					<td style="padding: 0 20px 40px; font-family: sans-serif; font-size: 14px; line-height: 20px; color: #555555; text-align: left;">
						<p style="margin: 0;">
							<?php echo $commentContent; ?>
						</p>
					</td>
				</tr>
				<tr>
					<td style="padding: 0 20px 40px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">

						<table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="left" >
							<tr>
								<td>
									<!-- Button : BEGIN -->
									<table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td>
										  <table border="0" cellspacing="0" cellpadding="0">
											<tr>
											  <td align="center" style="border-radius: 3px;" bgcolor="#748A94"><a href="<?php echo $commentPermalink;?>" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; text-decoration: none;border-radius: 3px; padding: 12px 18px; border: 1px solid #748A94; display: inline-block;"><?php echo JText::_('COM_KOMENTO_EMAILS_BUTTON_VIEW_REPLY');?> &rarr;</a></td>
											</tr>
										  </table>
										</td>
									  </tr>
									</table>
									<!-- Button : END -->
								</td>

							</tr>

						</table>

					</td>
				</tr>

			</table>
		</td>
	</tr>
	<!-- 1 Column Text + Button : END -->



	<!-- Clear Spacer : BEGIN -->
	<tr>
		<td height="40" style="font-size: 0; line-height: 0;">
			&nbsp;
		</td>
	</tr>
	<!-- Clear Spacer : END -->


</table>
