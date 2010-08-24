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
    
    $options['related_object_model'] = $this->getOption('related_object_model');
    $options['field_name'] = $this->getOption('field_name');
    $options['form_object'] = $this->getOption('form_object');
    $options['relation_name'] = $this->getOption('relation_name');
    
    return new ioObjectChooserManyHelper($options, $values);
  }
}
