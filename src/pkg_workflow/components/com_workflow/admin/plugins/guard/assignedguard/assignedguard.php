<?php
/**
 * @version 1.1.6 Dated: 2014--28
 */
// no direct access
defined( '_JEXEC' ) or die;
jimport('workflow.plugin.workflowtrigger');

// class name = plg<Group><Name>
class plgGuardAssignedguard extends plgAbstractTrigger 
{
	protected $_type = 'guard';
   	protected $_namespace = 'Workflow.Transition.Guard.Assigned';
   
    public function __construct($params = array()) 
    {
		parent::__construct($params);
    }

    /**
     * Validate if the transition is blocked 
     */ 
    public function allowTransition($oDocument, $oUser) 
    {
        if (!$this->isLoaded()) {
            return true;
        }
        
        $context = $this->params->get('context');
        if(empty($context)){
            // No context in configuration data, return false
            return false;
        }else{
        	// Count if the user was assigned for this item
        	$db = JFactory::getDbo();
        	$query = $db->getQuery(true);
        	$query->select('user_id')
        		->from('#__pf_ref_users')
        		->where('item_type = '.$db->quote($context))
        		->where('item_id = '.(int)$oDocument->id);
        		
        	$db->setQuery($query);
        	$rows = $db->loadObjectList();
        	
        	if (count($rows)) return true;
        	
        	// Continue on group checking
        	$query->clear();
        	$query->select('group_id')
        		->from('#__pf_ref_groups')
        		->where('item_type = '.$db->quote($context))
        		->where('item_id = '.(int)$oDocument->id);
        		
        	$db->setQuery($query);
        	$rows = $db->loadObjectList();

        	return (count($rows) > 0);
        	
        }
        
        return false;
    }
    
    public function getExplain() 
    {
    	return JText::_('PLG_GUARD_ASSIGNEDGUARD_ITEM_NOT_ASSIGNED');
    }
 }

