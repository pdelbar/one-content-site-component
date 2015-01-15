<?php
// Check to ensure this file is included in Joomla!
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldScheme extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Scheme';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$doc =& JFactory::getDocument();

		// find out which schemes are defined
		$schemes = One_Repository::getSchemeNames();
		sort($schemes);
		$options = array();
		foreach($schemes as $scheme)
		{
			$currScheme = One_Repository::getScheme( $scheme );
			if(!isset($currScheme->information['internal']) || isset($currScheme->information['menuonly']))
			{
				$options[] = array("id" => $scheme, "title" => $currScheme->getTitle());
			}
		}

		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('Select Scheme').' -', 'id', 'title'));

		$js = "
		function updateAttributes(scheme, value) {
			$('jformorder').innerHTML = '<option value=\'\'>loading...</option>';
			if ($('jform_params_orderdirection0').checked)
			{
				var order = 'asc';
			} else {
				var order = 'desc';
			}

			var url = 'index.php?option=com_one&task=show&view=attributes&tmpl=blank&scheme='+scheme.replace( /:(.)*$/g, '' )+'&order='+order;
			if (value) url = url + '&selected=' + value;

			var req = new Request({
						url: url,
			            method: 'get',
			            onSuccess: function(responseText) {
			            	$('jformorder').innerHTML = responseText;
		            	}
			        }).send();
		}

		function updateContents(scheme, value) {
			$('jformid').innerHTML = '<option value=\'\'> - - - </option>';

			var url = 'index.php?option=com_one&task=show&view=keyvalues&tmpl=blank&scheme='+scheme.replace( /:(.)*$/g, '' );
			if (value) url = url + '&selected=' + value;

			var req = new Request({
						url: url,
			            method: 'get',
			            onSuccess: function(responseText) {
			            	$('jformid').innerHTML = responseText;
		            	}
			        }).send();
		}

		function updateFilters(scheme, value) {
			$('jformquery').innerHTML = '<option value=\'\'> - - - </option>';

			var url = 'index.php?option=com_one&task=show&view=filters&tmpl=blank&scheme='+scheme.replace( /:(.)*$/g, '' );
			if (value) url = url + '&selected=' + value;

			var req = new Request({
						url: url,
			            method: 'get',
			            onSuccess: function(responseText) {
			            	$('jformquery').innerHTML = responseText;
		            	}
			        }).send();
		}";
		$doc->addScriptDeclaration($js);

		return JHTML::_('select.genericlist',  $options, ''.$this->formControl.'['.$this->group.']['.$this->fieldname.']', 'class="inputbox" onchange="updateAttributes(this.value); updateContents(this.value); updateFilters(this.value);"', 'id', 'title', $this->value, $this->formControl.$this->fieldname );
	}
}
