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

class KomentoParser extends KomentoBase
{
	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $emojiPath = array(
		'path' => '',
		'originalPath' => ''
	);

	/**
	 * Mapping of emoticons and smilies.
	 *
	 * @access protected
	 * @var array
	 */
	protected $emoticons = array();

	/**
	 * Map of smilies to emoticons.
	 *
	 * @access protected
	 * @var array
	 */
	protected $emojiMap = array();

	public function __construct()
	{
		parent::__construct();
	}

	public function parseComment($text)
	{
		$config = KT::getConfig();

		// word censoring
		if ($config->get('filter_word')) {
			$text = $this->parseCensor($text);
		}

		// parseBBcode to HTML
		$text = $this->parseBBCode($text);

		return $text;
	}

	public function parseBBCode($text)
	{
		$config = KT::getConfig();
		$nofollow = $config->get('links_nofollow') ? ' rel="nofollow"' : '';
		$maxdimension = ' style="max-width:100%"';

		if (!$config->get('enable_media_max_width') && ($config->get('max_image_width') || $config->get('max_image_height'))) {

			$maxdimension = ' style="';

			if ($config->get('max_image_width')) {
				$maxdimension .= 'max-width:' . $config->get('max_image_width') . 'px;';
			}

			if ($config->get('max_image_height')) {
				$maxdimension .= 'max-height:' . $config->get('max_image_height') . 'px;';
			}

			$maxdimension .= '"';
		}

		// Converts all html entities properly
		$text = htmlspecialchars($text , ENT_NOQUOTES);
		$text = trim($text);

		// Replace [code] blocks
		if ($this->config->get('bbcode_code')) {
			$text = $this->replaceCodes($text);
		}

		// Replace [gist] blocks
		if ($this->config->get('bbcode_gist')) {
			$text = $this->replaceGist($text);
		}

		$text = preg_replace_callback('/\[code(type="(.*?)")?\](.*?)\[\/code\]/ms', array('KomentoParser', 'escape'), $text);

		// avoid smileys in pre tag gets replaced
		$text = $this->encodePre($text);

		// BBCode to find...
		$in = array('/\[b\](.*?)\[\/b\]/ms',
					'/\[i\](.*?)\[\/i\]/ms',
					'/\[u\](.*?)\[\/u\]/ms',
					'/\[img\]((http|https):\/\/([a-z0-9\%._\s\*_\/+-?]+)\.(jpg|JPG|jpeg|JPEG|png|PNG|gif|GIF).*?)\[\/img]/ims',
					'/\[quote]([^\[\/quote\]].*?)\[\/quote\]/ims',
					'/\[quote](.*?)\[\/quote\]/ims',
					'/\[quote name="(.*?)"\](.*?)\[\/quote\]/ims',
					'/\[s\](.*?)\[\/s\]/ims'
		);
		
		// And replace them by...
		$out = array('<b>\1</b>',
					'<em>\1</em>',
					'<u>\1</u>',
					'<img src="\1" alt="\1"' . $maxdimension . ' />',
					'<blockquote>\1</blockquote>',
					'<blockquote>\1</blockquote>',
					'<span class="quote">' . JText::_('COM_KT_QUOTE_POSTED_BY') . ' \1' . ':</span><blockquote>\2</blockquote>',
					'<del>\1</del>'
		);

		// strip out bbcode data first
		$tmp = preg_replace($in, '', $text);

		// strip out bbcode url data
		$urlin = '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms';
		$tmp = preg_replace($urlin, '', $tmp);

		// strip out video links too
		$tmp = KT::videos()->strip($tmp);

		// Check if the content still contain [img] tag
		// if yes, dont allow to replace to hyperlink for prevent XSS attack
		if ((strpos($tmp, '[img]') === false || strpos($tmp, '[/img]') === false) && $this->config->get('auto_hyperlink')) {
			$text = $this->replaceURL($tmp, $text);
		}

		// replace video links
		if ($config->get('bbcode_video')) {
			$text = KT::videos()->replace($text);
		} else {
			$text = KT::videos()->strip($text);
		}

		// replace bbcode with html
		$text = preg_replace($in, $out, $text);

		// Replace url bbcode with html
		$text = $this->replaceBBUrl($text);

		// manual fix for unwrapped li issue
		$text = $this->replaceBBList($text);

		// Process smileys
		$text = $this->processSmiley($text);

		// change new line to br (without affecting pre)
		$text = nl2br($text);

		// done parsing emoticons and bbcode, decode pre text back
		$text = $this->decodePre($text);

		// paragraphs
		$text = str_replace("\r", "", $text);
		$text = "<p>".preg_replace("/(\n){2,}/", "</p><p>", $text)."</p>";
		$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', array('KomentoParser','removeBr'), $text);

		// fix [list] within [*] causing dom errors
		$text = preg_replace('/<li>(.*?)<ul>(.*?)<\/ul>(.*?)<\/li>/ms', "\\1<ul>\\2</ul>\\3", $text);
		$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);

		return $text;
	}

	/**
	 * Parse smiley image
	 *
	 * @since   3.1
	 * @access  public
	 */
	public function processSmiley($text)
	{
		$jsonPath = KOMENTO_LIB . '/smileys/emoticons.json';

		// Check if there's an emoticon directory in template's folder
		$this->emojiPath['path'] = '/templates/' . JFactory::getApplication()->getTemplate() . '/html/com_komento/emoticons/';
		$this->emojiPath['originalPath'] = JURI::root(true) . '/media/com_komento/images/icons/emoji/';

		$overrideExists = JFolder::exists(JPATH_ROOT . $this->emojiPath['path']);

		// Set the path to our own directory
		// Force root URI in as '/' does not work properly on subfolder sites
		if (!$overrideExists) {
			$this->emojiPath['path'] = $this->emojiPath['originalPath'];
		} else {
			// check if the json file exits or not.
			$jsonFile = JPATH_ROOT . $this->emojiPath['path'] . 'emoticons.json';

			if (JFile::exists($jsonFile)) {
				$jsonPath = $jsonFile;
			}	
		}

		if (file_exists($jsonPath)) {
			$this->emoticons = json_decode(file_get_contents($jsonPath), true);

			foreach ($this->emoticons as $emoticon => $smilies) {
				foreach ($smilies as $smile) {
					$this->emojiMap[$smile] = $emoticon;
				}
			}
		}

		if (!empty($this->emoticons)) {
			foreach ($this->emoticons as $smilies) {
				foreach ($smilies as $smile) {
					$pattern = '/(?P<left>^|\n|\s)(?:' . preg_quote($smile, '/') . ')(?P<right>\n|\s|$)/is';
					$text = preg_replace_callback($pattern, array($this, 'emoticonCallback'), $text);
					$text = preg_replace_callback($pattern, array($this, 'emoticonCallback'), $text);
				}
			}
		}

		return $text;
	}

	/**
	 * Callback for smiley processing.
	 *
	 * @access protected
	 * @param array $matches
	 * @return string
	 */
	protected function emoticonCallback($matches) {
		$smiley = trim($matches[0]);

		if (count($matches) === 1 && isset($this->emojiMap[$smiley])) {
			$image = '<img class="emoji" src="' . $this->emojiPath['path'] . $this->emojiMap[$smiley] . '.png" alt="" width="20" height="20">';

			return $image;
		}

		if (count($matches) === 1 || !isset($this->emojiMap[$smiley])) {
			return $matches[0];
		}

		$l = isset($matches[1]) ? $matches[1] : '';
		$r = isset($matches[2]) ? $matches[2] : '';
		$smileyPath = $this->emojiPath['path'] . $this->emojiMap[$smiley] . '.png';

		// Check if the emoji is exists if override folder exists.
		if ($this->emojiPath['path'] != $this->emojiPath['originalPath']) {
			$absoulteSmiley = JPATH_ROOT . $smileyPath;

			// Override file is not exists
			if (!JFile::exists($absoulteSmiley)) {
				$smileyPath = $this->emojiPath['originalPath'] . $this->emojiMap[$smiley] . '.png';
			}
		}

		$image = '<img class="emoji" src="' . $smileyPath . '" alt="" width="20" height="20">';

		return $l . $image . $r;
	}


	public function parseCensor($text)
	{
		$filterHelper = KT::filter();
		$config = KT::getConfig();

		$textToBeFilter = explode(',', $config->get('filter_word_text'));

		// lets do some AI here. for each string, if there is a space,
		// remove the space and make it as a new filter text.
		if (count($textToBeFilter) > 0) {
			$newFilterSet   = array();
			foreach ($textToBeFilter as $item) {
				$item = trim($item);
				if (JString::stristr($item, ' ') !== false) {
					$newKeyWord = JString::str_ireplace(' ', '', $item);
					$newFilterSet[] = $newKeyWord;
				}
			}

			if (count($newFilterSet) > 0) {
				$tmpNewFitler = array_merge($textToBeFilter, $newFilterSet);
				$textToBeFilter = array_unique($tmpNewFitler);
			}
		}

		$filterHelper->strings = $textToBeFilter;
		$filterHelper->text = $text;

		return $filterHelper->filter();
	}

	public function encodePre($text)
	{
		$pattern = '/<pre.*?>(.*?)<\/pre>/s';
		preg_match_all($pattern , $text , $matches);

		if (isset($matches[0]) && is_array($matches[0])) {
			foreach ($matches[1] as $match) {
				$text = str_ireplace($match , base64_encode($match), $text);
			}
		}

		return $text;
	}

	public function decodePre($text)
	{
		$pattern = '/<pre.*?>(.*?)<\/pre>/s';
		preg_match_all($pattern , $text , $matches);

		if (isset($matches[0]) && is_array($matches[0])) {
			foreach ($matches[1] as $match) {
				$text = str_ireplace($match , base64_decode($match) , $text);
			}
		}

		return $text;
	}

	/**
	 * Replace gist blocks with correct gist url
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function replaceGist($text, $debug = false)
	{
		$codesPattern = '/\[gist( type=&quot;(.*?)&quot;)?\](.*?)\[\/gist\]/ms';
		$text = preg_replace_callback($codesPattern, array('KomentoParser', 'processGistBlocks'), $text);

		$codesPattern = '/\[gist( type="(.*?)")?\](.*?)\[\/gist\]/ms';
		$text = preg_replace_callback($codesPattern, array('KomentoParser', 'processGistBlocks'), $text);

		return $text;
	}

	/**
	 * Process gist blocks
	 *
	 * @since   4.0
	 * @access  public
	 * @param   string
	 * @return
	 */
	public function processGistBlocks($blocks)
	{
		// The codes are on the 3rd index.
		$code = $blocks[3];

		// Determine the language type
		$language = isset($blocks[2]) && !empty($blocks[2]) ? $blocks[2] : 'html';

		// Because the text / contents are already escaped, we need to revert back to the original html codes only for the codes.
		$code = html_entity_decode($code);

		// Send to gist to create the gist now.
		$github = KT::github();
		$url = $github->createGist($code, $language);

		return '<script src="' . $url . '.js" data-kt-scripts-gist></script>';
	}

	/**
	 * Replace code blocks with prism.js compatible codes
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function replaceCodes($text, $debug = false)
	{
		// [code type=&quot*&quot]*[/code]
		$codesPattern = '/\[code( type=&quot;(.*?)&quot;)?\](.*?)\[\/code\]/ms';
		$text = preg_replace_callback($codesPattern, array('KomentoParser', 'processCodeBlocks'), $text);

		// Replace [code type="*"]*[/code]
		$codesPattern = '/\[code( type="(.*?)")?\](.*?)\[\/code\]/ms';
		$text = preg_replace_callback($codesPattern, array('KomentoParser', 'processCodeBlocks'), $text);

		return $text;
	}


	/**
	 * Replace [code] blocks with prism.js compatibility
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function processCodeBlocks($blocks)
	{
		$code = $blocks[3];

		// Remove break tags
		$code = str_ireplace("<br />", "", $code);
		$code = str_replace("[", "&#91;", $code);
		$code = str_replace("]", "&#93;", $code);

		// Determine the language type
		$language = isset($blocks[2]) && !empty($blocks[2]) ? $blocks[2] : 'markup';

		// Fix legacy code blocks
		if ($language == 'xml' || $language == 'html') {
			$language = 'markup';
		}

		// Because the text / contents are already escaped, we need to revert back to the original html codes only
		// for the codes.
		$code = html_entity_decode($code);

		// Fix html codes not displaying correctly
		$code = htmlspecialchars($code, ENT_NOQUOTES);

		return '<pre class="line-numbers"><code class="language-' . $language . '">'.$code.'</code></pre>';
	}
	
	public function removeBr($s)
	{
		// return str_replace("<br />", "", $s[0]);
		return str_replace("hello", "*****", $s[0]);
	}

	public function escape($s)
	{
		$code = $s[3];

		$code = str_replace("[", "&#91;", $code);
		$code = str_replace("]", "&#93;", $code);

		$brush  = isset($s[2]) && !empty($s[2]) ? $s[2] : 'xml';
		$code   = html_entity_decode($code);

		$code = KT::string()->escape($code);

		if ($brush != '') {
			$result = '<pre><code class="language-' . htmlspecialchars($brush) . '">' . $code . '</code></pre>';

		} else {
			$result = '<pre><code>' . $code . '</code></pre>';
		}

		return $result;
	}

	public function replaceBBList($content)
	{
		// BBCode to find... e.g.
		// [list]
		// [*]hello world
		// [*]marihome
		// [/list]
		$bbcodeListItemsSearch = '#\[list.*?\](.*?)\[\/list\]#ims';

		// BBCode to find... e.g.
		// [*]hello world
		$bbcodeLISearch = array(
			 '/\[\*\]\s?(.*?)\n/ims',
			 '/\[\*\]\s?(.*?)/ims'
		);

		// And replace them by...
		$bbcodeLIReplace = array(
			 '<li>\1</li>',
			 '<li>\1</li>'
		);

		// And replace them by...
		$bbcodeLIReplaceString = array(
			 '\1',
			 '\1'
		);

		// BBCode to find...
		$bbcodeListPattern = array(
			 '/\[list\=(.*?)\]/ims',
			 '/\[list\]/ims',
			 '/\[\/list\]/ims'
		);

		$bbcodeULSearch = array(
			 '/\[list\=1\](.*?)\[\/list\]/ims',
			 '/\[list\=a\](.*?)\[\/list\]/ims',
			 '/\[list\=A\](.*?)\[\/list\]/ims',
			 '/\[list\=i\](.*?)\[\/list\]/ims',
			 '/\[list\=I\](.*?)\[\/list\]/ims',			 
			 '/\[list\](.*?)\[\/list\]/ims',
		);

		// And replace them by...
		$bbcodeULReplace = array(
			 '<ol start=1 style="list-style-type: decimal">\1</ol>',
			 '<ol start=a style="list-style-type: lower-alpha">\1</ol>',
			 '<ol start=A style="list-style-type: upper-alpha">\1</ol>',
			 '<ol start=i style="list-style-type: lower-roman">\1</ol>',
			 '<ol start=I style="list-style-type: upper-roman">\1</ol>',
			 '<ul>\1</ul>'
		);

		// And replace them by...
		$bbcodeULReplaceString = array('\2', '\1');

		preg_match_all($bbcodeListItemsSearch, $content, $matches);

		if (!$matches || !$matches[0]) {
			return $content;
		}

		$lists = array();

		// Fix any unclosed list tags
		foreach ($matches[0] as &$contents) {

			$original = $contents;

			$listStylePattern = array('\[list\=(.*?)\]', '\[list\]');
			$listStylePattern = implode('|', $listStylePattern);

			preg_match('/' . $listStylePattern . '/ims', $contents, $listStyleMatches);

			// The match of lists within this block should always be the first and last. Anything within the "list" should be considered as unclosed.
			$contents = preg_replace($bbcodeListPattern, '', $contents);

			// this match of list have to follow back what original list code pass in
			$contents = $listStyleMatches[0] . $contents . '[/list]';

			$item = new stdClass();
			$item->original = $original;
			$item->contents = $contents;

			$lists[] = $item;
		}

		foreach ($lists as $list) {

			// Check if this list contains any list items "[*]"
			if (JString::strpos($list->contents, '[*]') !== false) {

				$text = preg_replace($bbcodeULSearch, $bbcodeULReplace, $list->contents);
				$text = preg_replace($bbcodeLISearch, $bbcodeLIReplace, $text);
			} else {
				$text = preg_replace($bbcodeULSearch , $bbcodeULReplaceString, $list->contents);
			}

			// Update the content
			$content = JString::str_ireplace($list->original, $text, $content);
		}

		return $content;
	}

	/**
	 * Replaces the bbcode url tag
	 *
	 * @since   2.0
	 * @access  public
	 */
	public function replaceBBUrl($text)
	{
		$config = KT::getConfig();
		$nofollow = $config->get('links_nofollow') ? ' rel="nofollow"' : '';

		preg_match_all('/\[url\=(.*?)\](.*?)\[\/url\]/ims', $text, $matches);

		if (!empty($matches)) {

			$sources = array_shift($matches);

			$urls = $matches[0];
			$txts = $matches[1];

			for ($i = 0; $i < count($sources); $i++) {
				$source = $sources[$i];

				if (!empty($source)) {
					$url = $urls[$i];
					
					// prevent user add javascript in the url element
					$segments = explode(' ', $url);
					$url = $segments[0];

					// Ensure that the url doesn't contain " or ' or &quot;
					$url = str_ireplace(array('"', "'", '&quot;'), '', $url);

					$txt = $txts[$i];

					if (stripos($url, 'http://') !== 0 && stripos($url, 'https://') !== 0 && stripos($url, 'ftp://') !== 0) {
						$url = 'http://' . $url;
					}

					$replace = '<a target="_blank" href="' . $url . '"' . $nofollow . '>' . $txt . '</a>';

					$text = str_ireplace($source, $replace, $text);
				}
			}
		}

		return $text;
	}

	public function replaceURL($tmp, $text)
	{
		$pattern = '@(?i)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';

		preg_match_all($pattern, $tmp, $matches);

		$targetBlank = ' target="_blank"';
		$noFollow = $this->config->get('links_nofollow') ? ' rel="nofollow"' : '';

		// Do not proceed if there are no links to process
		if (!isset($matches[0]) || !is_array($matches[0]) || empty($matches[0])) {
			return $text;
		}

		$tmplinks = $matches[0];

		$links = array();
		$linksWithProtocols = array();
		$linksWithoutProtocols = array();

		// We need to separate the link with and without protocols to avoid conflict when there are similar url present in the content.
		if ($tmplinks) {
			foreach($tmplinks as $link) {

				if (stristr( $link , 'http://' ) === false && stristr( $link , 'https://' ) === false && stristr( $link , 'ftp://' ) === false ) {
					$linksWithoutProtocols[] = $link;
				} else if (stristr( $link , 'http://' ) !== false || stristr( $link , 'https://' ) !== false || stristr( $link , 'ftp://' ) === false ) {
					$linksWithProtocols[] = $link;
				}
			}
		}

		// the idea is the first convert the url to [EDWURLx] and [EDWOURLx] where x is the index. This is to prevent same url get overwritten with wrong value.
		$linkArrays = array();

		// global indexing.
		$idx = 1;

		// lets process the one with protocol
		if ($linksWithProtocols) {
			$linksWithProtocols = array_unique($linksWithProtocols);

			foreach($linksWithProtocols as $link) {

				$mypattern = '[EDWURL' . $idx . ']';

				$text = str_ireplace($link, $mypattern, $text);

				$obj = new stdClass();
				$obj->index = $idx;
				$obj->link = $link;
				$obj->newlink = $link;
				$obj->customcode = $mypattern;

				$linkArrays[] = $obj;

				$idx++;
			}
		}

		// Now we process the one without protocol
		if ($linksWithoutProtocols) {
			$linksWithoutProtocols = array_unique($linksWithoutProtocols);

			foreach($linksWithoutProtocols as $link) {
				$mypattern = '[EDWOURL' . $idx . ']';
				$text = str_ireplace($link, $mypattern, $text);

				$obj = new stdClass();
				$obj->index = $idx;
				$obj->link = $link;
				$obj->newlink = 'http://'. $link;
				$obj->customcode = $mypattern;

				$linkArrays[] = $obj;

				$idx++;
			}
		}

		// Let's replace back the link now with the proper format based on the index given.
		foreach ($linkArrays as $link) {
			$text = str_ireplace($link->customcode, $link->newlink, $text);

			$patternReplace = '@(?<![.*">])\b(?:(?:https?|ftp|file)://|[a-z]\.)[-A-Z0-9+&#/%=~_|$()?!:,.]*[A-Z0-9+&#/%=~_|$]@i';

			// Use preg_replace to only replace if the URL doesn't has <a> tag
			$text = preg_replace($patternReplace, '<a href="\0" ' . $targetBlank . $noFollow . '>\0</a>', $text);
		}

		// Not really sure why this is needed as it will cause some of the content to not rendered correctly.
		// We will comment this out for now. References : #1878
		// $text = JString::str_ireplace('&quot;', '"', $text);

		return $text;
	}

	// /**
	//  * Replaces hyperlinks with html anchor tags
	//  *
	//  * @since    3.0
	//  * @access   public
	//  */
	// public function replaceURL($tmp , $text)
	// {
	//  $nofollow = $this->config->get('links_nofollow') ? ' rel="nofollow"' : '';
	//  $pattern = '/(((http|https):\/{2})+(([0-9a-z_-]+\.)+(aero|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mn|mn|mo|mp|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|nom|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ra|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw|arpa)(:[0-9]+)?((\/([~0-9a-zA-Z\#\+\%@\.\/_-]+))?(\?[0-9a-zA-Z\+\%@\/&\[\];=_-]+)?)?))\b/i';

	//  preg_match_all($pattern , $tmp , $matches);

	//  if (isset($matches[0]) && is_array($matches[0])) {
	//      foreach ($matches[0] as $match) {
	//          $text = str_ireplace($match , '<a target="_blank" href="' . $match . '"' . $nofollow . '>' . $match . '</a>' , $text);
	//      }
	//  }

	//  return $text;
	// }
}
