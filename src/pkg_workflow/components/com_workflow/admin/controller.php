<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Workflow Component Controller
 *
 * @package     Workflow
 * @subpackage  com_workflow
 * @since       1.0
 */
class WorkflowController extends JController
{
	/**
	 * Override the display method for the controller.
	 *
	 * @return  void
	 * @since   1.0
	 */
		
	protected $default_view = 'dashboard';
	
	function display($cachable=false, $urlparams=null)
	{
		// Load the component helper.
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/workflow.php';
		// Load the submenu.
		$view = JRequest::getCmd('view', 'workflows');
		WorkflowHelper::addSubmenu($view);

		// Display the view.
		parent::display($cachable, $urlparams);
	}
}