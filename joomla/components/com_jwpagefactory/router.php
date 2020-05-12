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

class JwpagefactoryRouter extends JComponentRouterBase
{

	public function build(&$query)
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		$segments = array();

		if (empty($query['Itemid'])) {
			$menuItem = $menu->getActive();
			$menuItemGiven = false;
		} else {
			$menuItem = $menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}

		// Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_jwpagefactory') {
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (isset($query['view'])) {
			$view = $query['view'];
		} else {
			return $segments;
		}

		if (($menuItem instanceof stdClass) && $menuItem->query['view'] == $query['view']) {

			if (!$menuItemGiven) {
				$segments[] = $view;
			}

			unset($query['view']);
		}

		if ($menuItemGiven) {

			if (isset($query['view']) && $query['view']) {
				unset($query['view']);
			}
			$id = 0;
			if (isset($query['id']) && $query['id']) {
				$id = $query['id'];
				unset($query['id']);
			}

			if (isset($query['tmpl']) && $query['tmpl']) {
				unset($query['tmpl']);
			}

			if (isset($query['layout']) && $query['layout']) {
				$segments[] = $query['layout'];
				if ($id) {
					$segments[] = $id;
				}
				unset($query['layout']);
			}
		}

		return $segments;
	}

	// Parse
	public function parse(&$segments)
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		$total = count((array)$segments);
		$vars = array();
		$view = (isset($item->query['view']) && $item->query['view']) ? $item->query['view'] : '';

		if (count($segments) == 2 && $segments[0] == 'edit') {
			$vars['view'] = 'form';
			$vars['id'] = (int)$segments[1];
			$vars['tmpl'] = 'component';
			$vars['layout'] = 'edit';
			return $vars;
		}

		return $vars;
	}

}

function JwpagefactoryBuildRoute(&$query)
{
	$router = new JwpagefactoryRouter;
	return $router->build($query);
}

function JwpagefactoryParseRoute($segments)
{
	$router = new JwpagefactoryRouter;
	return $router->parse($query);
}
