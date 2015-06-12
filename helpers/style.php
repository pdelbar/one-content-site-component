<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class OneHelpersStyle
{
  static function load()
  {
    $document = JFactory::getDocument();

    //stylesheets
    $document->addStylesheet(JURI::base().'components/com_one/assets/css/style.css');

    //javascripts
    $document->addScript(JURI::base().'components/com_one/assets/js/one.js');
  }
}