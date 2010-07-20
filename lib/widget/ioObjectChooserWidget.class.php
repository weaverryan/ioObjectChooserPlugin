<?php

/*
 * ioObjectChooserWidget - basic database object chooser
 * 
 * requires model option
 */
class ioObjectChooserWidget extends sfWidgetFormInput
{
  /**
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    
    parent::configure($options, $attributes);
  }
  
  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $helper = $this->getHelper($name);
    
    $input_tag_html = $this->renderTag('input', array_merge(array('type' => 'hidden', 'name' => $name, 'value' => $value), $attributes));
    
    $result = $helper->getHtml($input_tag_html);
    
    return $result;
  }
  
  public function getHelper($name)
  {
    return new ioObjectChooserHelper($this, $name);
  }
  
  public function getJavascripts()
  {
    return array('0'=>'/ioObjectChooserPlugin/js/io_object_chooser.js');
  }

}
