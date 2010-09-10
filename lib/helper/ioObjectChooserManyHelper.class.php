<?php


/**
 * @see ioObjectChooserHelper
 */
class ioObjectChooserManyHelper extends ioObjectChooserHelper
{
  public function getLabel()
  {
    return $this->related_object_model.'s';
  }
  
  public function getSelectionHolder($default = null)
  {
    // keep track of id's of objects added by the user so that we don't add
    // them twice in the two loops below
    $related_object_ids = array();
    
    foreach ($this->widget_values as $value)
    {
      if (!in_array($value, $related_object_ids))
      {
        $default .= sprintf('<input type="hidden" name="%s[]" value="%s">', $this->field_name, $value);
      }
    }
    
    $result = '<div class="io_object_chooser_holder">'.$default.'</div>';
    return $result;
  }
  
  public function getWrapperClass()
  {
    return 'io_object_chooser_wrapper choose_many';
  }
}
