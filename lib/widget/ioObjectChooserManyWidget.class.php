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
    return new ioObjectChooserManyHelper($this->getOption('form_object'), $this->getOption('relation_name'), $field_name, $values);
  }
}
