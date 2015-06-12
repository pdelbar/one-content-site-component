<?php
class OneDispatcherJoomla extends One_Dispatcher
{
	private $parseContentPlugins = false;

  /**
   * Decode the options and decide what needs to be done.
   *
   * The precedence has changed in this version of one|content. Now, we take the options as precedence, and
   * overlay them over the menu item we are on.
   *
   * @param array $options
   */
  public function __construct(array $options = array())
	{
        $app        = JFactory::getApplication();

		$defaults = array(
			'id' => null,
			'scheme' => null,
			'task' => null,
			'oview' => null,
			'parseContentPlugins' => false,
		);

		// get menu parameters
		$menu_options = array();
		if(($menu = $app->getMenu()->getActive()) &&  $menu->component == 'com_one')
		{
			$menu_options = array_merge($menu->params->toArray(), $menu->query);

			// if the scheme in the request differs from the one specified in the menu, the menu options become invalid
			if(array_key_exists('scheme', $options) && $menu_options['scheme'] != $options['scheme']) {
				$menu_options = array();
			}

			if (isset($menu_options['extraParameters']))
			{
				$extras = parse_ini_string($menu_options['extraParameters']);
				unset($menu_options['extraParameters']);
				$menu_options = array_merge($menu_options, $extras);

				foreach($extras as $key =>$value) {
					JRequest::setVar($key, $value);
				}
			}
		}

		$options = array_merge($defaults, $menu_options, $options);

		// convert order to one format (foo+ or foo-)
		if(array_key_exists('order', $options)) {
			$options['order'] = $options['order'].($options['orderdirection']=='asc' ? '+' : '-');
		}

		$this->parseContentPlugins = (bool) $options['parseContentPlugins'];

		if(!isset($options['noflow'])) {
			$options['noflow'] = true;
		}
		else {
			$options['noflow'] = (bool) $options['noflow'];
		}

		parent::__construct($options);
	}

	public function dispatch()
	{
    if ($this->_view == 'rest') {
      return $this->dispatchRest();
    }

		try
		{
			$scheme           = $this->_scheme;
			$this->controller = One_Repository::getController($scheme, $this->_options);
			$content          = $this->controller->execute($this->_task, $this->_options);

			if(is_null($this->controller->getRedirect()))
			{
				if ($this->parseContentPlugins && $this->task <> 'edit') // If we don't do an edit, prepare the content
				{
					$dummy       = new stdClass();
					$dummy->text = $content;

					JPluginHelper::importPlugin('content');
					$dispatcher = JDispatcher::getInstance();
					$params 	= JFactory::getApplication()->getParams();

				  $dispatcher->trigger('onContentPrepare', array( 'com_one.default', &$dummy, &$params, 0));
					$content = $dummy->text;
				}

				echo $content;
			}
			else
			{
				$this->setRedirect($this->controller->getRedirect());
				$this->redirect();
			}
		}
		catch(Exception $e)
		{
			if(One_Config::getInstance()->exitOnError() === false) {
				echo $e->getMessage();
			}
			else {
				throw new Exception($e);
			}
		}
	}


  public function dispatchRest()
  {
    try
    {
      $service = new One_Service_Rest();
      $service->run();
    }
    catch(Exception $e)
    {
      if(One_Config::getInstance()->exitOnError() === false) {
        echo $e->getMessage();
      }
      else {
        throw new Exception($e);
      }
    }
  }


  public function setRedirect(array $options)
	{
		if(is_null($options))
			$this->_redirect = NULL;
		else if(count($options) == 0 || !is_array($options))
			$this->_redirect = JURI::base();
		else
		{
			if(isset($options['rawURL']))
				$this->_redirect = $options['rawURL'];
			else
			{
				$gotoOptions = array();
				$gotoOptions[] = (isset($options['task']) && trim($options['task']) != '') ?  'task='.$options['task'] : 'task=list';

				if(isset($options['scheme']) && trim($options['scheme']) != '')
					$gotoOptions[] = 'scheme='.$options['scheme'];
				else
					throw new One_Exception('Redirect must contain a scheme');

				unset($options['task']);
				unset($options['scheme']);

				foreach($options as $option => $value)
				{
					$gotoOptions[] = $option.'='.$value;
				}

				if(isset($options['Itemid']) && intval($options['Itemid']) > 0)
					$gotoOptions[] = 'Itemid='.intval($options['Itemid']);
				else
					$gotoOptions[] = 'Itemid='.JRequest::getInt('Itemid', 0);

				$this->_redirect = JRoute::_('index.php?option=com_one&'.implode('&', $gotoOptions), false);
			}
		}
	}

	public function redirect()
	{
		if(!is_null($this->_redirect))
		{
			if(headers_sent())
				echo '<script> document.location = "'.$this->_redirect.'";</script>';
			else
				header('Location: '.$this->_redirect);
			exit;
		}
	}
}

// needed for PHP-versions prior to 5.3.0
if(!function_exists('parse_ini_string'))
{
  function parse_ini_string($ini, $process_sections = false, $scanner_mode = null)
  {
    # Generate a temporary file.
    $tempname = tempnam('/tmp', 'ini');
    $fp = fopen($tempname, 'w');
    fwrite($fp, $ini);
    $ini = parse_ini_file($tempname, !empty($process_sections));
    fclose($fp);
    @unlink($tempname);
    return $ini;
  }
}