<?php

/**
 * @see ioObjectChooserWidget
 */
class ioObjectChooserManyWidget extends ioObjectChooserWidget
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('form_object');
    $this->addRequiredOption('field_relation');
    
    parent::configure($options, $attributes);
  }

  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $helper = $this->getHelper($name);
    
    $result = $helper->getHtml();
    
    return $result;
  }
  
  public function getHelper($name)
  {
    return new ioObjectChooserManyHelper($this, $name, $this->getOption('form_object'), $this->getOption('field_relation'));
  }
}
