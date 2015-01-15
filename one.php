<?php // No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//sessions
jimport( 'joomla.session.session' );


//load tables
JTable::addIncludePath(JPATH_COMPONENT.'/tables');

//load classes
JLoader::registerPrefix('One', JPATH_COMPONENT);

//Load plugins
JPluginHelper::importPlugin('one');

//Load styles and javascripts
OneHelpersStyle::load();

//application
$app = JFactory::getApplication();

// Require specific controller if requested
if($controller = $app->input->get('controller','default')) {
  require_once (JPATH_COMPONENT.'/controller/'.$controller.'.php');
}

// Create the controller
$classname  = 'OneController'.ucfirst($controller);
$controller = new $classname();

// Perform the Request task
$controller->execute();