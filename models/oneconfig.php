<?php // no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

class OneModelsOneconfig extends OneModelsDefault
{

  /**
   * Protected fields
   **/
  var $_id     = null;
  var $_option  = null;
  var $_value   = null;


  function __construct()
  {
    parent::__construct();
  }

  /**
   * Builds the query to be used by the book model
   * @return   object  Query object
   *
   *
   */
  protected function _buildQuery()
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(TRUE);

    $query->select('oc.id, oc.option, oc.value');
    $query->from('#__one_config as oc');

    return $query;
  }

  /**
   * Builds the filter for the query
   * @param    object  Query object
   * @return   object  Query object
   *
   */
  protected function _buildWhere(&$query)
  {

    if(is_numeric($this->_id))
    {
      $query->where('oc.id = ' . (int) $this->_id);
    }

    if($this->_option)
    {
      $query->where("oc.option = '" . $this->_option . "'");
    }

    if($this->_value)
    {
      $query->where("oc.value = '" . $this->_value . "'");
    }


    return $query;
  }
}