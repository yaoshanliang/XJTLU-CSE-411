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
defined ('_JEXEC') or die ('Restricted access');

class JwpagefactoryHelperImage extends JImage
{

	public function createThumbs($thumbSizes, $creationMethod = self::SCALE_INSIDE, $thumbsFolder = null)
	{
		// Make sure the resource handle is valid.
		if (!$this->isLoaded())
		{
			throw new LogicException('No valid image was loaded.');
		}

		// Process thumbs
		$thumbsCreated = array();

		// Generate sizes
		$newThumbSizes = array();
		$thumbNames = array();
		foreach ($thumbSizes as $key => $value) {
			$newThumbSizes[] = $value;
			$thumbNames[] 	= $key;
		}

		if ($thumbs = $this->generateThumbs($newThumbSizes, $creationMethod))
		{
			// Parent image properties
			$imgProperties = self::getImageFileProperties($this->getPath());

			foreach ($thumbs as $key=>$thumb)
			{
				// Get thumb properties
				$thumbWidth  = $thumb->getWidth();
				$thumbHeight = $thumb->getHeight();

				// Generate thumb name
				$filename      = pathinfo($this->getPath(), PATHINFO_FILENAME);
				$fileExtension = pathinfo($this->getPath(), PATHINFO_EXTENSION);

				if($thumbsFolder) {
					$thumbsFolder = dirname($this->getPath()) . '/' . $thumbsFolder;
					$thumbFileName = $filename . '.' . $fileExtension;
				} else {
					$thumbsFolder = dirname($this->getPath());
					$thumbFileName = $filename . '_'. $thumbNames[$key] .'.' . $fileExtension;
				}

				// Save thumb file to disk
				$thumbFileName = $thumbsFolder . '/' . $thumbFileName;

				if ($thumb->toFile($thumbFileName, $imgProperties->type))
				{
					// Return JImage object with thumb path to ease further manipulation
					$thumb->path     = $thumbFileName;
					$thumbsCreated[] = $thumb;
				}
			}
		}

		return $thumbsCreated;
	}
}
