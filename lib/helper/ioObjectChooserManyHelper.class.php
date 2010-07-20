<?php


/**
 * @see ioObjectChooserHelper
 */
class ioObjectChooserManyHelper extends ioObjectChooserHelper
{
  protected $field_relation;
  protected $form_object;
  
  /**
   * @param arg3 form_object
   * @param arg4 field_relation
   */
  public function __construct($arg1, $arg2, $arg3, $arg4)
  {
    $this->form_object = $arg3;
    $this->field_relation = $arg4;
    return parent::__construct($arg1, $arg2);
  }
  
  public function getLabel()
  {
    return $this->getModel().'s';
  }
  
  public function getSelectionHolder($default = null)
  {
    $relation = $this->field_relation;
    $object = $this->form_object;
    
    foreach ($object->$relation as $related_object)
    {
      $default .= sprintf('<input type="hidden" name="%s[]" value="%s">', $this->name, $related_object->id);
    }
    
    $result = '<div class="io_object_chooser_holder">'.$default.'</div>';
    return $result;
  }
  
  public function getWrapperClass()
  {
    return 'io_object_chooser_wrapper choose_many';
  }
}