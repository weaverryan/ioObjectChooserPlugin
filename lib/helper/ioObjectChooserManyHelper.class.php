<?php


/**
 * @see ioObjectChooserHelper
 */
class ioObjectChooserManyHelper extends ioObjectChooserHelper
{
  public function getLabel()
  {
    return $this->getModel().'s';
  }
  
  public function getSelectionHolder($default = null)
  {
    $relation = $this->relation_name;
    $object = $this->form_object;
    
    foreach ($object->$relation as $related_object)
    {
      $default .= sprintf('<input type="hidden" name="%s[]" value="%s">', $this->field_name, $related_object->id);
    }
    
    $result = '<div class="io_object_chooser_holder">'.$default.'</div>';
    return $result;
  }
  
  public function getWrapperClass()
  {
    return 'io_object_chooser_wrapper choose_many';
  }
}
