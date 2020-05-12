<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */

/**
 * Parse CSS code for JW Page Factory
 */
if (!class_exists('JwpfCustomCssParser')) {

	class JwpfCustomCssParser
	{
		protected $cssData;
		protected $css;
		protected $newCss;
		protected $id;
		protected $addonWrapperId;
		private static $instance;

		public function cssWork($css, $id, $addonWrapperId)
		{
			$this->cssData = array('all' => array());
			$this->css = $css;
			$this->id = $id;
			$this->addonWrapperId = $addonWrapperId;
			$this->parseCss();
			$this->addId();

			return $this->newCss;
		}

		public static function getCss($css, $id, $addonWrapperId = null)
		{
			$css = trim($css);
			if (empty($css)) {
				return false;
			}

			if (self::$instance === null) {
				self::$instance = new JwpfCustomCssParser();
			}

			$parsedCss = self::$instance->cssWork($css, $id, $addonWrapperId);

			return $parsedCss;
		}

		protected function addId()
		{
			$newCss = '';
			$id = $this->id;
			$addonWrapperId = $this->addonWrapperId;
			if (count((array)$this->cssData)) {
				foreach ($this->cssData as $media => $mediaCss) {
					if ($media != 'all') {
						$newCss .= "@media ${media}{";
					}

					foreach ($mediaCss as $selector => $values) {
						$selectors = explode(',', $selector);
						$newSelectors = array();

						foreach ($selectors as $tmpSelector) {
							if (preg_match("/#addonId/", $tmpSelector)) {
								$tmpSelector = str_replace("#addonId", $id, $tmpSelector);
								$tmpSelector = str_replace("#addonWrapper", $addonWrapperId, $tmpSelector);
								$newSelectors[] = "${tmpSelector}";
							} elseif (preg_match("/#addonWrapper/", $tmpSelector)) {
								$tmpSelector = str_replace("#addonWrapper", $addonWrapperId, $tmpSelector);
								$newSelectors[] = "${tmpSelector}";
							} else {
								$newSelectors[] = "${id} ${tmpSelector}";
							}
						}

						$newSelector = implode(',', $newSelectors);

						$newCss .= "${newSelector}{";
						foreach ($values as $cssProp => $cssValue) {
							$newCss .= "${cssProp}:${cssValue};";
						}
						$newCss .= "}";
					}

					if ($media != 'all') {
						$newCss .= "}";
					}
				}
			}

			$this->newCss = $newCss;
		}


		protected function parseCss()
		{
			$currentMedia = 'all';
			$mediaList = array();
			$section = false;
			$css = trim($this->css);
			if (strlen($css) == 0) {
				return false;
			}
			$css = preg_replace('/\/\*.*\*\//Us', '', $css);
			while (preg_match('/^\s*(\@(media|import|local)([^\{\}]+)(\{)|([^\{\}]+)(\{)|([^\{\}]*)(\}))/Usi', $css, $match)) {
				if (isset($match[8]) && ($match[8] == '}')) {
					if ($section !== false) {
						$code = trim($match[7]);
						$idx = 0;
						$inQuote = false;
						$property = false;
						$codeLen = strlen($code);
						$parenthesis = array();
						while ($idx < $codeLen) {

							if ($code == null) {
								break;
							}
							$c = $code{$idx};
							$idx++;
							if ($inQuote !== false) {
								if ($inQuote === $c) {
									$inQuote = false;
								}
							} elseif (($inQuote === false) && ($c == '(')) {
								array_push($parenthesis, '(');
							} elseif (($inQuote === false) && ($c == ')')) {
								array_pop($parenthesis);
							} elseif (($c == '\'') || ($c == '"')) {
								$inQuote = $c;
							} elseif (($property === false) && ($c == ':')) {
								$property = trim(substr($code, 0, $idx - 1));
								if (preg_match('/^(.*)\[([0-9]*)\]$/Us', $property, $propMatch)) {
									$property = $propMatch[1] . '[' . static::$propCounter . ']';
									static::$propCounter += 1;
								}
								$code = substr($code, $idx);
								$idx = 0;
							} elseif ((count((array)$parenthesis) == 0) && ($c == ';')) {
								$value = trim(substr($code, 0, $idx - 1));
								$code = substr($code, $idx);
								$idx = 0;
								$this->AddProperty($currentMedia, $section, $property, $value);
								$property = false;
							}
						}
						if (($idx > 0) && ($property !== false)) {
							$value = trim($code);
							$this->AddProperty($currentMedia, $section, $property, $value);
						}
						$section = false;
					} elseif (count((array)$mediaList) > 0) {
						\
							array_pop($mediaList);
						if (count((array)$mediaList) > 0) {
							$currentMedia = end($mediaList);
						} else {
							$currentMedia = 'all';
						}
					}
				} elseif (isset($match[6]) && ($match[6] == '{')) {
					$section = trim($match[5]);
					if (!isset($this->cssData[$currentMedia][$section])) {
						$this->cssData[$currentMedia][$section] = array();
					}
				} elseif (isset($match[4]) && ($match[4] == '{')) {
					if ($match[2] == 'media') {
						// New media
						$media = trim($match[3]);
						$mediaList[] = $media;
						$currentMedia = $media;
						if (!isset($this->cssData[$currentMedia])) {
							$this->cssData[$currentMedia] = array();
						}
					}
				}

				$stripCount = strlen($match[0]);
				$css = trim(substr($css, $stripCount));
			}
		}

		protected function AddProperty($media, $section, $property, $value)
		{

			$media = trim($media);
			if ($media == '') {
				$media = 'all';
			}
			$section = trim($section);
			$property = trim($property);
			if (strlen($property) > 0) {
				$value = trim($value);
				if ($media == 'all') {
					$this->cssData[$media][$section][$property] = $value;
					$keys = array_keys($this->cssData);
					foreach ($keys as $key) {
						if (!isset($this->cssData[$key][$section])) {
							$this->cssData[$key][$section] = array();
						}
						$this->cssData[$key][$section][$property] = $value;
					}
				} else {
					if (!isset($this->cssData[$media])) {
						$this->cssData[$media] = $this->cssData['all'];
					}
					if (!isset($this->cssData[$media][$section])) {
						$this->cssData[$media][$section] = array();
					}
					$this->cssData[$media][$section][$property] = $value;
				}
			}
		}

	}
}