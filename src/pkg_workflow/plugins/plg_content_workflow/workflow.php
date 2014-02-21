<?php
defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

/**
 * Content Workflow Plugin.
 *
 * @package     Workflow
 * @subpackage  content_workflow
 * @since       1.0
 */
class plgContentWorkflow extends JPlugin
{

	/**
	 * 
	 * Looking for workflow detail and update content item for saving
	 * @param unknown_type $context
	 * @param unknown_type $table
	 * @param unknown_type $is_new
	 */
	public function onContentBeforeSave($context, $table, $is_new)
    {	
    	if ( property_exists($table, 'workflow_id') && property_exists($table, 'workflow_state_id') 
    		&& empty($table->workflow_id) ) 
    	{
    		$db = JFactory::getDbo();
    		$query = $db->getQuery(true);
			$query->select('workflow_id')
				->from('#__wf_typemaps')
				->where('context = '.$db->quote($context))
				->where('published = 1');
			$db->setQuery($query);
			$workflow_id = $db->loadResult();
			
			if (!$workflow_id) {
				JFactory::getApplication()->enqueueMessage('Cannot find workflow mapping for '.$context);
				return true;
			}
			
			$query = $db->getQuery(true);
			$query->select('a.id, a.title, a.ordering')
				->from('#__wf_states AS a')
				->where('published = 1')
				->where('start_state = 1')
				->where('workflow_id ='.(int)$workflow_id)
				->order('a.ordering ASC');
			$db->setQuery($query);
			$row = $db->loadObject();
			
			$start_state_id = $row->id;
			
			if (empty($start_state_id)) { 
				JFactory::getApplication()->enqueueMessage('Cannot find workflow start state for '.$context);
				return true;
			}
    		
			$table->workflow_id = $workflow_id;
			$table->workflow_state_id = $start_state_id;
			JFactory::getApplication()->enqueueMessage($context.' item was inserted into '.$row->title .' workflow.' );		
			
			return true;
    	} 		
    }	
    
}