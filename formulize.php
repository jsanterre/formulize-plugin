<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// Get the path to Formulize stored as a component parameters
$params = JComponentHelper::getParams( 'com_formulize' );
$formulize_path = $params->get('formulize_path');
<<<<<<< HEAD

// TODO: Is this the place to put it?
Formulize::init();
$application = JFactory::getApplication();

=======
require_once $formulize_path."/integration_api.php";
>>>>>>> 86a8172f1d5bd430b94d32b8353a85711e9bff26

class plgUserFormulize extends JPlugin
{
	/*
		Note to Jeff:
		Need to add this code in session.php
		if(isset($GLOBALS['joomlaUserId'])) { // Joomla
		    $externalUid = $GLOBALS['joomlaUserId'];
		}
	*/
	public function onUserLogin($user, $options)
	{
		$application = JFactory::getApplication();
		// Search for the current userId
		// Need to query the database (weird)
		$email = $user['email'];
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);      
        $query->select('id,name')
			->from('#__users ')
			->where('email = ' .  "'". $email . "'" );            
        $db->setQuery($query);    
        if (!$db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
        }                         
		$rows = $db->loadObjectList();
		foreach($rows as $row){   
			$userId = $row->id;
		}
		// Set $GLOBALS to pass userId to the Formulize system
		$GLOBALS['joomlaUserId'] = $userId;
		// Start session in formulize
		Formulize::init();

		// For debugging
		$session = session_id();
		$application->enqueueMessage(JText::_('User ID:'.$GLOBALS['joomlaUserId']), 'message');
		$application->enqueueMessage(JText::_('Session ID:'.$session), 'message');
		
        return true;
    }
	
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		$application = JFactory::getApplication();
		// Get the user
		$joomlaUser =& JFactory::getUser($user['username']);
		// Create a new blank user for Formulize session
		$formulizeUser =& JFactory::getUser(0);
		// Build Formulize user
		$formulizeUser->uid = $joomlaUser->id;
		$formulizeUser->uname = $joomlaUser->name;
		$formulizeUser->login_name = $joomlaUser->username;
		$formulizeUser->email = $joomlaUser->email;
		
		// Create or update user in Formulize
		if($isnew)
		{
			// Create a user in Formulize
			$flag = Formulize::createUser($formulizeUser);
			if ( !$flag ) {
				$application->enqueueMessage(JText::_('User id:'.$userID.'\nError creating new user'), 'error');
			}
		}
		else
		{
			// Update a user in Formulize
			$flag = Formulize::updateUser($formulizeUser->uid, $formulizeUser);
			if ( !$flag ) {
				$application->enqueueMessage(JText::_('User id:'.$userID.'\nError updating user/'), 'error');
			}
		}
		
		// For debugging
		$name = $formulizeUser->uname;
		$application->enqueueMessage(JText::_('User name:'.$name), 'message');
		
        return flag;
    }
	
	public function onUserBeforeDelete($user)
	{
		$application = JFactory::getApplication();
		// Get the deleted user
		$joomlaUser =& JFactory::getUser($user['username']);
		$userID = $joomlaUser->id;
		
		// Delete the user in Formulize
		$flag = Formulize::deleteUser($userID);
		if ( !$flag ) {
<<<<<<< HEAD
			$application->enqueueMessage(JText::_('User id:'.$userID.'\nError deleting user/'), 'error');
		}
		// For debugging
=======
				$application->enqueueMessage(JText::_('User id:'.$userID.'\nError deleting user/'), 'error');
		}
	
		// For debugging	
>>>>>>> 86a8172f1d5bd430b94d32b8353a85711e9bff26
		$application->enqueueMessage(JText::_('User id:'.$userID), 'message');
		
        return true;
    }
	/* Note to Jeff:
		Need to create the table formulize_external_group_mapping
	*/
	public function onUserAfterSaveGroup($context, $group, $isnew)
	{
		$application = JFactory::getApplication();
		// Get the group
		$name = $group->title;
		$id = $group->id;
		$groupData = array();
		$groupData['name'] = $name;
		$groupData['groupid'] = $id; 
		$formulizeGroup = new FormulizeGroup($groupData);
		
		// Create or update a group in Formulize
		if($isnew)
		{
			// Create a user in Formulize
			$flag = Formulize::createGroup($formulizeGroup);
			if ( !$flag ) {
				$application->enqueueMessage(JText::_('Group id:'.$group->id.'\nError creating new group'), 'error');
			}
		}
		/*
		else
		{
			// Update a user in Formulize
			$flag = Formulize::updateUser($formulizeUser->uid, $formulizeUser);
			if ( !$flag ) {
				$application->enqueueMessage(JText::_('User id:'.$userID.'\nError updating user/'), 'error');
			}
		}
		*/
		// For debugging
		$application->enqueueMessage(JText::_('Name: '.$formulizeGroup->get('name')), 'message');
		$application->enqueueMessage(JText::_('Id: '.$formulizeGroup->get('groupid')), 'message');
	}
}
?>
