<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// Get the path to Formulize stored as a component parameters
$params = JComponentHelper::getParams( 'com_formulize' );
$formulize_path = $params->get('formulize_path');
require_once $formulize_path."/integration_api.php";

class plgUserFormulize extends JPlugin
{
	// Need to add onUserLogout as well...
	// Not ready... The call to getUser doesn't seem to work...
	// Necessary since $user doesn't contain user id
	public function onUserLogin($user, $options)
	{
        // Get the current user
		$joomlaUser =& JFactory::getUser($user['username']);
		// Create a new blank user for Formulize session
		$formulizeUser =& JFactory::getUser(0);
		// Build Formulize user
		$formulizeUser->uid = $joomlaUser->id;
		$formulizeUser->uname = $joomlaUser->name;
		$formulizeUser->login_name = $joomlaUser->username;
		$formulizeUser->email = $joomlaUser->email;
		
		// Create a session in Formulize
		// API is not ready???
		//$flag = Formulize::openSessionSomething();
	
		// For debugging
		$application = JFactory::getApplication();
		$name = $formulizeUser->uname;
		$application->enqueueMessage(JText::_('User name:'.$name), 'message');
 
        return true;
    }
	
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		// Get the new user
		$joomlaUser =& JFactory::getUser($user['username']);
		// Create a new blank user for Formulize session
		$formulizeUser =& JFactory::getUser(0);
		// Build Formulize user
		$formulizeUser->uid = $joomlaUser->id;
		$formulizeUser->uname = $joomlaUser->name;
		$formulizeUser->login_name = $joomlaUser->username;
		$formulizeUser->email = $joomlaUser->email;
		// For debugging
		$application = JFactory::getApplication();
		$name = $formulizeUser->uname;
		$application->enqueueMessage(JText::_('User name:'.$name), 'message');
		
		if($isnew)
		{
			// Create a user in Formulize
			$flag = Formulize::createUser($formulizeUser);
		}
		else
		{
			// Do user update
			// API is not ready???
			//$flag = Formulize::updateUser($formulizeUser->uid, $formulizeUser);
		}
		
        return true;
    }
	
	public function onUserBeforeDelete($user)
	{
		// Get the deleted user
		$joomlaUser =& JFactory::getUser($user['username']);
		$userID = $joomlaUser->id;
		
		// Delete the user in Formulize
		// API is not ready???
		//$flag = Formulize::deleteUser($userID);
	
		// For debugging
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('User id:'.$userID), 'message');
		
        return true;
    }
}
?>
