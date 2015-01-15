<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class TableOneconfig extends JTable
{
  /**
   * Constructor
   *
   * @param object Database connector object
   */
  function __construct( &$db ) {
    parent::__construct('#__one_config', 'id', $db);
  }
}