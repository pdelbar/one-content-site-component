<?php
// Check to ensure this file is included in Joomla!
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldId extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Id';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		$html = "<select id='".$this->formControl."id' name='jform[".$this->group."][id]'><option value=''>loading...</option></select>";

		$document =& JFactory::getDocument();
		$js = "
		  window.addEvent('domready', function(){
		    updateContents($('jformscheme').value, '".$this->value."');
		  });
		";
		$document->addScriptDeclaration($js);

		return $html;
	}
}
