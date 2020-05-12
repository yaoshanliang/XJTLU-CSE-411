<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class KomentoViewMain extends JViewLegacy {}

class KomentoAdminView extends KomentoViewMain
{
	protected $app = null;
	protected $my = null;
	protected $input = null;
	protected $ajax = null;
	protected $theme = null;

	private $heading = '';
	private $description = '';

	public function __construct($options = array())
	{
		$this->app = JFactory::getApplication();
		$this->my = JFactory::getUser();
		$this->input = KT::request();
		$this->ajax = KT::ajax();
		$this->config = KT::config();
		$this->theme = KT::themes();

		parent::__construct($options);
	}

	public function display($tpl = null)
	{
		JToolbarHelper::title(JText::_('COM_KOMENTO'), 'komento');

		// Set the appropriate namespace
		$namespace 	= 'admin/' . $tpl;

		// Get the child contents
		$output = $this->theme->output($namespace);

		// Get the sidebar
		$sidebar = $this->getSidebar();

		// Determine if this is a tmpl view
		$tmpl = $this->input->get('tmpl', '', 'word');

		// Prepare the structure
		$theme = KT::getTemplate();

		$theme->set('heading', $this->heading);
		$theme->set('description', $this->description);
		$theme->set('output', $output);
		$theme->set('sidebar', $sidebar);

		$contents = $theme->output('admin/structure/default');

		echo $contents;
	}

	/**
	 * Proxy for setting a variable to the template.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function set($key, $value = '')
	{
		$this->theme->set($key, $value);
	}

	/**
	 * Allows child to set heading title
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function heading($title, $desc = '')
	{
		if (empty($desc)) {
			$this->description = JText::_($title . '_DESC');
		} else {
			$this->description = JText::_($desc);
		}

		$this->heading = JText::_($title);
	}

	/**
	 * Checks if the current viewer can really access this section
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function checkAccess($rule)
	{
		if (!$this->my->authorise($rule , 'com_komento')) {
			$this->app->enqueueMessage('JERROR_ALERTNOAUTHOR', 'error');
			return $this->app->redirect('index.php?option=com_komento');
		}
	}

	/**
	 * Prepares the sidebar
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getSidebar()
	{
		$file = JPATH_COMPONENT . '/defaults/sidebar.json';
		$contents = JFile::read($file);

		$view = $this->input->get('view', '', 'cmd');
		$layout = $this->input->get('layout', '', 'cmd');

		$result = json_decode($contents);
		$menus = array();

		foreach ($result as &$row) {

			// Check if the user is allowed to view this sidebar
			if (isset($row->access) && $row->access) {
				if (!$this->my->authorise($row->access, 'com_komento')) {
					continue;
				}
			}

			if (!isset($row->view)) {
				$row->link = 'index.php?option=com_komento';
				$row->view = '';
			}

			if (isset($row->counter)) {
				$row->counter = $this->getCounter($row->counter);
			}

			if (!isset($row->link)) {
				$row->link = 'index.php?option=com_komento&view=' . $row->view;
			}

			if (isset($row->childs) && $row->childs) {

				foreach ($row->childs as &$child) {

					$child->link = 'index.php?option=com_komento&view=' . $row->view;

					if ($child->url) {
						foreach ($child->url as $key => $value) {

							if (!empty($value)) {
								$child->link .= '&' . $key . '=' . $value;
							}
						}
					}

					// Processes items with counter
					if (isset($child->counter)) {
						$child->counter = $this->getCounter($child->counter);
					}
				}
			}

			$menus[] = $row;
		}

		// Get local version
		$localVersion = KT::version()->getLocalVersion();

		$theme = KT::themes();
		$theme->set('localVersion', $localVersion);
		$theme->set('layout', $layout);
		$theme->set('view', $view);
		$theme->set('menus', $menus);

		$output = $theme->output('admin/structure/sidebar/default');

		return $output;
	}

	/**
	 * Processes counters from the menus.json
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getCounter($namespace)
	{
		static $counters = array();

		list($model, $method) = explode('/', $namespace);

		if (!isset($counters[$namespace])) {
			$model = KT::model($model);

			$counters[$namespace] = $model->$method();
		}

		return $counters[$namespace];
	}

	public function renderCheckbox( $configName , $state = null )
	{
		if( is_null( $state ) )
		{
			$config = KT::getConfig();
			$state = $config->get( $configName, 0 );
		}

		ob_start();
	?>
		<div class="toggle-options">
			<label class="option-enable<?php echo $state == 1 ? ' selected' : '';?>"><span><?php echo JText::_( 'COM_KOMENTO_YES_OPTION' );?></span></label>
			<label class="option-disable<?php echo $state == 0 ? ' selected' : '';?>"><span><?php echo JText::_( 'COM_KOMENTO_NO_OPTION' ); ?></span></label>
			<input name="<?php echo $configName; ?>" value="<?php echo $state;?>" type="radio" id="<?php echo $configName; ?>" class="radiobox" checked="checked" />
		</div>
	<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function renderOption( $value, $text )
	{
		return JHtml::_( 'select.option', $value, JText::_( $text ) );
	}

	public function renderDropdown( $configName, $state = null, $options )
	{
		if( is_null( $state ) )
		{
			$config = KT::getConfig();
			$state = $config->get( $configName, '' );
		}

		$this->makeListOptions( $options );

		return JHtml::_('select.genericlist', $options, $configName, '.fa- class="form-control"', 'value', 'text', $state, $configName );
	}

	public function renderInput( $configName, $state = null, $options = null )
	{
		if( is_null( $state ) )
		{
			$config = KT::getConfig();
			$state = $config->get( $configName, '' );
		}

		$size = 5;
		$pretext = '';
		$posttext = '';
		$align = '';
		$class = '';
		if( is_array( $options ) )
		{
			if( isset( $options['size'] ) )
			{
				$size = $options['size'];
			}

			if( isset( $options['pretext'] ) )
			{
				$pretext = $options['pretext'];
			}

			if( isset( $options['posttext'] ) )
			{
				$posttext = $options['posttext'];
			}

			if( isset( $options['align'] ) )
			{
				$align = $options['align'];
			}

			if( isset( $options['class'] ) )
			{
				$class = $options['class'];
			}
		}
		else
		{
			if( $options != '' )
			{
				$size = $options;
			}
		}

		ob_start();
		?>
		<span class="small"><?php echo $pretext; ?></span><input type="text" class="inputbox <?php echo $class; ?>" id="<?php echo $configName; ?>" name="<?php echo $configName; ?>" value="<?php echo $this->escape( $state ); ?>" size="<?php echo $size; ?>"<?php echo $align ? ' style="text-align:'.$align.';"' : ''; ?>/><span class="small"><?php echo $posttext; ?></span>
		<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function renderMultilist( $configName, $selected = '', $options )
	{
		if( empty( $selected ) )
		{
			$selected = array();
		}

		if( !is_array( $selected ) )
		{
			$selected	= explode( ',' , $selected );
		}

		$key = $configName . '[]';

		$this->makeListOptions( $options );

		return JHTML::_( 'select.genericlist', $options, $key, 'multiple="multiple" size="10" style="height: auto !important;"', 'value', 'text', $selected );
	}

	public function renderText( $value, $type = 'notice' )
	{
		// Prepare text class
		$class = '';

		switch( $type )
		{
			case 'error':
			case 'warning':
				$class = 'warning';
			break;
		}

		ob_start();
	?>
		<tr>
			<td width="300" class="key">
			</td>
			<td valign="top">
				<div class="has-tip">
					<p class="<?php echo $class; ?>"><?php echo $value ;?></p>
				</div>
			</td>
		</tr>

	<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function renderFilters( $options = array() , $value , $element )
	{
		ob_start();

		foreach( $options as $key => $val )
		{
		?>
		<a class="kmt-filter<?php echo $value == $key ? ' kmt-filter-active' : '';?>" href="javascript:void(0);" onclick="Foundry('#<?php echo $element;?>').val('<?php echo $key;?>');submitform();"><?php echo JText::_( $val ); ?></a>
		<?php
		}
		?>
		<input type="hidden" name="filter_type" id="filter_type" value="<?php echo $this->escape($value); ?>" />
		<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function makeListOptions( &$options = array() )
	{
		// accepts array of object with either format:
		// $object->id & $object->title | $object->treename
		// $object->value & $object->text

		if (!is_array($options)) {
			$options = array();
		}

		foreach ($options as &$option) {
			// convert array to object
			if (is_array($option)) {
				$tmp = new stdClass();
				$tmp->id = $option[0];
				$tmp->title = $option[1];
				$option = $tmp;
			}

			if (isset($option->value) && !isset($option->id)) {
				$option->id = $option->value;
			}

			if (isset($option->text) && !isset($option->title)) {
				$option->title = $option->text;
			}

			// if it is a tree item, then treename always take effect
			if (isset($option->treename)) {
				$option->title = $option->treename;
			}

			$option = $this->renderOption($option->id, $option->title);
		}
	}

	public function renderTextarea( $configName, $state = '', $options = array())
	{
		if( $state == '' )
		{
			$config = KT::getConfig();
			$state = $config->get( $configName, '' );
		}

		$cols = isset( $options['cols'] ) ? $options['cols'] : 25;
		$rows = isset( $options['rows'] ) ? $options['rows'] : 5;

		ob_start();
		?>
		<textarea name="<?php echo $configName; ?>" class="inputbox full-width" cols="<?php echo $cols; ?>" rows="<?php echo $rows; ?>"><?php echo str_replace( '<br />', "\n", $state ); ?></textarea>
		<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function renderColumnsConfiguration( $columns, $columnsConfig )
	{
		$html = '<button type="button" class="btn btn-primary" onclick="Komento.saveColumns()">' . JText::_( 'COM_KOMENTO_COMMENTS_SAVE_COLUMNS' ) . '</button>';

		$html .= '<table class="table table-options">';

		foreach( $columns as $column )
		{
			$html .= '<tr><td>' . JText::_( 'COM_KOMENTO_COLUMN_' . strtoupper( $column ) ) . '</td><td>' . $this->renderCheckbox( 'column_' . $column, $columnsConfig->get( 'column_' . $column ) ) . '</td></tr>';
		}

		$html .= '</table>';

		return $html;
	}

	/**
	 * Calls a specific method from the view.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function call($method)
	{
		if (!method_exists($this, $method)) {
			return false;
		}

		// Get a list of arguments since we do not know
		// how many arguments are passed in here.
		$args = func_get_args();

		// Remove the first argument since the first argument is the method.
		array_shift($args);

		return call_user_func_array(array($this, $method), $args);
	}
}
