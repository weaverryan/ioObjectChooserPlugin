<?php

/**
 * @see ioObjectChooserWidget
 */
class ioObjectChooserManyWidget extends ioObjectChooserWidget
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $helper = $this->getHelper($name, $value);
    
    $result = $helper->getHtml();
    
    return $result;
  }
  
  public function getHelper($field_name, $values)
  {
    $options = array();
    
    $options['model'] = $this->getOption('model');
    $options['field_name'] = $field_name;
    
    return new ioObjectChooserManyHelper($options, $values);
  }
}
