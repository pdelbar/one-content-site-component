<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class OneViewsOneconfigHtml extends JViewHtml
{
  function render()
  {
    $app = JFactory::getApplication();
    $layout = $app->input->get('layout');

    //retrieve task list from model
    $model = new OneModelsOneconfig();

    switch($layout) {

      case "oneconfig":
        $this->oneconfig  = $model->getItem();
        $this->_oneconfigListView = OneHelpersView::load('Oneconfig','_entry','phtml');
        break;

      case "list":
      default:
        $this->oneconfigs = $model->listItems();
        $this->_oneconfigListView = OneHelpersView::load('Oneconfig','_entry','phtml');
        break;

    }

    //display
    return parent::render();
  }
}