<?php

class ioObjectChooserHelper
{
  // the model we are relating to
  protected $form_object = null;
  // name of the related object(s)
  protected $relation_name = null;
  // the field name of the widget we are helping (i.e. string of 'form_name[widget_name]' )
  protected $field_name = null;
  
  /**
   * @param the form's object
   * @param the name of the relation from the form's object to whatever we are relating to
   * @param the name of the widget in html land (i.e. string of 'form_name[widget_name]' )
   */
  public function __construct($form_object, $relation_name, $field_name)
  {
    $this->form_object = $form_object;
    $this->relation_name = $relation_name;
    $this->field_name = $field_name;
  }
  
  public function getButton()
  {
    sfApplicationConfiguration::getActive()->loadHelpers(array('Url'));
    $url = url_for('io_object_chooser_index', array('model'=>$this->getModel()));
    $result = '<div class="io_object_chooser_button"><a href="'.$url.'">Choose '.$this->getLabel().'</a></div>';
    return $result;
  }
  
  public function getPopup()
  {
    $result = '<div class="io_object_chooser_popup" style="display: none"></div>';
    return $result;
  }
  
  public function getHtml($input_tag_html = null)
  {

    $result = '<div class="'.$this->getWrapperClass().'" rel="'.$this->field_name.'">';
    $result .= $this->getButton();
    $result .= $this->getPopup();
    $result .= $this->getSelectionHolder($input_tag_html);
    $result .= $this->getSelectionPreviewDiv();
    $result .= '</div>';
    return $result;
  }
  
  public function getModel()
  {
    $object = $this->form_object;
    $relation = $this->relation_name;
    $result = $object->$relation;
    if ($result instanceof Doctrine_Record)
    {
      return get_class($result);
    }
    else
    {
      return get_class($result[0]);
    }
    
  }
  
  public function getLabel()
  {
    return $this->getModel();
  }
  
  public function getSelectionHolder($default = null)
  {
    $result = '<div class="io_object_chooser_holder">'.$default.'</div>';
    return $result;
  }
  
  public function getSelectionPreviewDiv($default = null)
  {
    sfApplicationConfiguration::getActive()->loadHelpers(array('Url'));
    $url = url_for('io_object_chooser_show', array('model'=>$this->getModel()));
    $result = '<div class="io_object_chooser_preview" rel="'.$url.'"><ul></ul></div>';
    return $result;
  }
  
  public function getWrapperClass()
  {
    return 'io_object_chooser_wrapper choose_one';
  }
}