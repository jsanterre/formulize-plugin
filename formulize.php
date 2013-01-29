<?php

// no direct access
defined('_JEXEC') or die;

class plgUserFormulize extends JPlugin
{
	// Not ready, need openSession from API and need onUserLogout
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
		
		// Get a handle to the Joomla! application object
		$application = JFactory::getApplication();
		// Get the path to Formulize stored as a component parameters
		$params = JComponentHelper::getParams( 'com_formulize' );
		$formulize_path = $params->get('formulize_path');
		require_once $formulize_path."/integration_api.php";
		// Create a session in Formulize
		//$flag = Formulize::openSession from API;
	
		// For debugging
		$name = $formulizeUser->uname;
		$application->enqueueMessage(JText::_('User name:'.$name), 'message');
 
        return true;
    }
	
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		if($isnew)
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
			
			// Get a handle to the Joomla! application object
			$application = JFactory::getApplication();
			// Get the path to Formulize stored as a component parameters
			$params = JComponentHelper::getParams( 'com_formulize' );
			$formulize_path = $params->get('formulize_path');
			require_once $formulize_path."/integration_api.php";
			// Create a user in Formulize
			$flag = Formulize::createUser($formulizeUser);
		
			// For debugging
			$name = $formulizeUser->uname;
			$application->enqueueMessage(JText::_('User name:'.$name), 'message');
		}
		else
		{
			// Do user update
		}
		
        return true;
    }

}


?>
