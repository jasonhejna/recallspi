<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * HTML QuickForm Select with Other Option
 * 
 * NOTE: This file must be included *after* HTML_QuickForm.php
 */

require_once 'HTML/QuickForm/select.php';

/**
* Replace PHP_EOL constant
*
*  category    PHP
*  package     PHP_Compat
* @link        http://php.net/reserved.constants.core
* @author      Aidan Lister <aidan@php.net>
* @since       PHP 5.0.2
*/
if (!defined('PHP_EOL')) {
    switch (strtoupper(substr(PHP_OS, 0, 3))) {
        // Windows
        case 'WIN':
            define('PHP_EOL', "\r\n");
            break;

        // Mac
        case 'DAR':
            define('PHP_EOL', "\r");
            break;

        // Unix
        default:
            define('PHP_EOL', "\n");
    }
}


/**
 * HTML QuickForm Select with Other Option
 *
 * HTML_QuickForm plugin that adds another 'other' option by means of a text
 * field.
 *
 * @category HTML
 * @package HTML_QuickForm_selectother
 * @author David Sanders
 * @link http://pear.glassbox.com.au/
 */
class HTML_QuickForm_selectother extends HTML_QuickForm_select
{
    /**
     * Text for the Other option.
     *
     * @var string
     * @access public
     */
    var $otherText = 'Other';

    /**
     * Text label to go in front of other text field.
     *
     * @var string
     * @access public
     */
    var $otherMsg = 'If other please specify:';

    /**
     * Delimiter between elements.  Use br to go vertical, or nbsp to go horizontal.
     * 
     * @var bool
     * @access public
     */
    var $delimiter = '<br />';

    /**
     * Other value storage.
     *
     * @var string
     * @access private
     */
    var $_otherValue;
  

    /**
     * Render the HTML_QuickForm element.
     *
     * @access public
     * @return html string
     */
    function toHtml()
    {
        if ($this->_flagFrozen)
        {
            return $this->getFrozenHtml();
        }
        else
        {
            return $this->_getElements('html');
        }
    }


    /**
     * Arrange the select html and other bits either concatenated as a html
     * string or in an array.
     * 
     * @param string format
     * @access private
     * @return array or string
     */
    function _getElements($format = 'html')
    {
        $myName = $this->getName();
        if ($this->getMultiple())
        {
            $myName .= '[]';
        }

        if ($format == 'array')
        {
            $elements = array();
        }
        else
        {
            $preHtml = '';
            $postHtml = '';
            $strHtml = '';
        }


        //
        // js function
        //

        $disable_element_js = <<<EOT
<script type="text/javascript">
//<![CDATA[
function _qf_selectother_otherSelected(e)
{
  if (!e.multiple)
  {
    return e.value == '_qf_other';
  }

  for (var i in e.options)
  {
    o = e.options[i];
    if (o.value == '_qf_other')
    {
      return o.selected;
    }
  }

  return false;
}

function _qf_selectother_disableElement(e,disable)
{
  if (!disable)
  {
    e.disabled = false;
    e.style.backgroundColor = '#ffffff';
  }
  else
  {
    e.disabled = true;
    e.style.backgroundColor = '#cccccc';
  }
}
//]]>
</script>
EOT;

        if (!defined('HTML_QUICKFORM_SELECTOTHER_JS_DISABLE_ELEMENT'))
        {
            if ($format == 'array')
            {
                $elements['_qf_selectother_disableElement'] =& HTML_QuickForm::createElement('static','_qf_selectother_disableElement',null,$disable_element_js);
            }
            else
            {
                $preHtml = $disable_element_js;
            }
            define('HTML_QUICKFORM_SELECTOTHER_JS_DISABLE_ELEMENT',true);
        }
        else
        {
            $preHtml = '';
        }

        //
        // main select
        //

        $other_id = 'qf_' . uniqid('');
        // use focus() and select() so the cursor appears even though select implies focus
        if ($this->getMultiple())
        {
            $intrinsic_js = "_qf_selectother_disableElement(document.getElementById('$other_id'),!_qf_selectother_otherSelected(this));if(_qf_selectother_otherSelected(this)){document.getElementById('$other_id').focus();document.getElementById('$other_id').select();}";
        }
        else
        {
            $intrinsic_js = "_qf_selectother_disableElement(this.form.elements[this.name+'_qf_other'],!_qf_selectother_otherSelected(this));if(_qf_selectother_otherSelected(this)){this.form.elements[this.name+'_qf_other'].focus();this.form.elements[this.name+'_qf_other'].select();}";
        }
        

        $onChangeEvent = $this->getAttribute('onchange');
        if (isset($onChangeEvent) && $onChangeEvent !== '')
        {
            if ($onChangeEvent[strlen($onChangeEvent)] != ';')
            {
                $onChangeEvent .= ';';
            }
            
            $onChangeEvent .= $intrinsic_js;
        }
        else
        {
            $onChangeEvent = $intrinsic_js;
        }
        $this->updateAttributes(array('onchange' => $onChangeEvent));
        
        $this->addOption($this->otherText,'_qf_other');
        
        $select_html = parent::toHtml();
        if ($format == 'array')
        {
            $elements[$myName] =& HTML_QuickForm::createElement('static',$myName,null,$select_html);
        }
        else
        {
            $strHtml .= $select_html;
        }
        

        //
        // create the 'other' text field
        //
        
        if ($this->getMultiple())
        {
            $textName = $myName;
        }
        else
        {
            $textName = $myName . '_qf_other';
        }

        $other_element =& HTML_QuickForm::createElement('text',$textName,null,array('id'=>$other_id));

        // if either the other button is selected, or some text is entered 
        // (meaning _qf_other will also be a value), then set the value of
        // the other value in the text field.
        if (is_array($this->_values) &&
            in_array('_qf_other', $this->_values) &&
            isset($this->_otherValue))
        {
            $other_element->updateAttributes(array('value' => $this->_otherValue));
        }
        // only disable with javascript so is not stuck as disabled if the
        // browser isn't js-equipped 
        else
        {
            $disable_js = <<<EOT
<script type="text/javascript">
//<![CDATA[
_qf_selectother_disableElement(document.getElementById('$other_id'),true);
//]]>
</script>
EOT;

            if ($format == 'array')
            {
                $elements['disable_js'] =& HTML_QuickForm::createElement('static','disable_js',null,$disable_js);
            }
            else
            {
                $postHtml .= $disable_js;
            }
        }

        if ($format == 'array')
        {
            $elements[$textName] =& $other_element;
        }
        else
        {
            $strHtml .= $this->delimiter . PHP_EOL . $this->otherMsg . ' ' . $other_element->toHtml();
        }

        if ($format == 'array')
        {
            return $elements;
        }
        else
        {
            $strHtml = $preHtml . PHP_EOL . $strHtml . PHP_EOL . $postHtml;
            return $strHtml;
        }
    }


    /**
     * Exports the value.
     *
     * If the other value is set, this will be exported if in singular mode
     * and the other option is selected.  Otherwise if in multiple mode
     * the other value is added to the array of values.
     *
     * @param array submitValues values submitted
     * @param bool assoc passed into HTML_QuickForm_select exportValue()
     * @access public
     * @return single value or array of values
     */
    function exportValue(&$submitValues, $assoc = false)
    {
        $myName = $this->getName();

        if ($this->getMultiple())
        {
            // defeats the purpose of using exportValue to return only allowed options
            $an_array = $submitValues[$myName];
            foreach ($an_array as $key => $value)
            {
                if ($value == '')
                {
                    unset($an_array[$key]);
                }
            }
            return $an_array;
        }
        else
        {
           $this->_options[] = array('text' => '',
                                     'attr' => array('value' => '_qf_other'));
           $val = parent::exportValue($submitValues,$assoc);

            if ($val == '_qf_other')
            {
                return $submitValues[$myName.'_qf_other'];
            }
            else
            {
                return $val;
            }
        }
    }


    /**
     * Set the selected options.  If a non-listed option is specified, it
     * will go into the other text field.
     *
     * @param array values to select
     * @access public
     */
    function setSelected($values)
    {
        parent::setSelected($values);

        //
        // if we are including the other text field, then we'll need to do
        // some extra work here.
        //
        if ($this->includeOther)
        {
            foreach ($this->_values as $value)
            {
                // if we are in singular mode and the other button is selected from the
                // submit values then we'll need to record the real other value in _otherValue
                if ($value == '_qf_other')
                {
                    $myName = $this->getName();
                    if ($this->getMultiple())
                    {
                        $myName .= '[]';
                    }
                    
                    $this->_otherValue = @$_REQUEST[$myName.'_qf_other'];
                    
                    // we only need to grasp the other value so we'll return
                    return;
                }
                // otherwise the real other value might be listed in _values
                // from setSelected('junk') or if we're in multiple mode and it was
                // submitted...
                // if we find something not part of the options then we record it in _otherValue
                // and set the _qf_other as part of the values
                else
                {
                    $found = false;
                    foreach ($this->_options as $option)
                    {
                        if ((string) $value == (string) $option['attr']['value'])
                        {
                            $found = true;
                        }
                    }

                    if (!$found)
                    {
                        $this->_values[] = '_qf_other';
                        $this->_otherValue = $value;

                        // we only need to see the first value that isn't part of the options,
                        // so we'll return now
                        return;
                    }
                }
            }
        }
    }


    /**
     * Set the select to include the 'other' textfield.
     * 
     * @param bool include- Whether to include the other textfield 
     * @access public
     */
    function setIncludeOther($include = true)
    {
        $this->includeOther = (bool) $include;
    }

    /**
     * Set the delimiter.
     * 
     * @param string Delimiter to use between the subelements.
     * @access public
     */
    function setDelimiter($delimiter)
    {
        if (!is_string($delimiter))
        {
            $this->delimiter = '<br />';
        }
        else
        {
            $this->delimiter = $delimiter;
        }
    }

    /**
     * Tell this element to act like a group when being accepted.
     * 
     * @param bool is_group 
     * @access public
     */
    function setGroup($is_group = false)
    {
        $this->_type = $is_group ? 'group' : 'select';
    }

   /**
    * Accepts a renderer.  Overload select in case we'd like to see the 
    * checkboxes/radio buttons in a group when renderering with another
    * renderer.
    *
    * @param object     An HTML_QuickForm_Renderer object
    * @param bool       Whether a group is required
    * @param string     An error message associated with a group
    * @access public
    * @return void 
    */
    function accept(&$renderer, $required = false, $error = null)
    {
        // if not asked to act like a group, then pass of to regular accept method
        if ($this->_type != 'group')
        {
            return parent::accept($renderer,$required,$error);
        }
        $this->_separator = null;
        $this->_appendName = null;
        $this->_required = array();



        //$this->_createElementsIfNotExist();
        $renderer->startGroup($this, $required, $error);
        $name = $this->getName();

// --8<--
        $this->_elements = $this->_getElements('array');
// -->8--

        foreach (array_keys($this->_elements) as $key) {
            $element =& $this->_elements[$key];
            
            if ($this->_appendName) {
                $elementName = $element->getName();
                if (isset($elementName)) {
                    $element->setName($name . '['. (strlen($elementName)? $elementName: $key) .']');
                } else {
                    $element->setName($name);
                }
            }

            $required = !$element->isFrozen() && in_array($element->getName(), $this->_required);

            $element->accept($renderer, $required);

            // restore the element's name
            if ($this->_appendName) {
                $element->setName($elementName);
            }
        }
        $renderer->finishGroup($this);
    }
}


if (class_exists('HTML_QuickForm'))
{
    HTML_QuickForm::registerElementType('selectother', 'HTML/QuickForm/selectother.php', 'HTML_QuickForm_selectother');
}
?>
