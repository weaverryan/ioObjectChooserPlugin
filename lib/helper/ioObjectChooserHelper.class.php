<?php

class ioObjectChooserHelper
{
  // the model we are relating to
  protected $form_object = null;
  // name of the related object(s)
  protected $relation_name = null;
  // the field name of the widget we are helping (i.e. string of 'form_name[widget_name]' )
  protected $field_name = null;
  // submitted values of the widget
  protected $widget_values = array();
  // the name of the related object's model
  protected $related_object_model = null;
  
  /**
   * @param the form's object
   * @param the name of the relation from the form's object to whatever we are relating to
   * @param the name of the widget in html land (i.e. string of 'form_name[widget_name]' )
   */
  public function __construct($options, $widget_values = array())
  {
    if (isset($options['related_object_model']))
    {
      $this->related_object_model = $options['related_object_model'];
    }
    
    $this->form_object = $options['form_object'];
    $this->relation_name = $options['relation_name'];
    $this->field_name = $options['field_name'];
    $this->widget_values = $widget_values ? $widget_values : array();
  }
  
  public function getButton()
  {
    sfApplicationConfiguration::getActive()->loadHelpers(array('Url'));
    $url = url_for('io_object_chooser_index', array('model'=>$this->getModel()));
    $result = '<div class="io_object_chooser_button"><a href="'.$url.'">Choose '.$this->getLabel().'</a></div>';
    return $result;
  }
  
  public function getResponseDiv()
  {
    $result = '<div class="io_object_chooser_response" style="display: none"></div>';
    return $result;
  }
  
  public function getHtml($input_tag_html = null)
  {
    $result = '<div class="'.$this->getWrapperClass().'" rel="'.$this->field_name.'">';
    $result .= $this->getButton();
    $result .= $this->getResponseDiv();
    $result .= $this->getSelectionHolder($input_tag_html);
    $result .= $this->getSelectionPreviewDiv();
    $result .= '</div>';
    return $result;
  }
  
  public function getModel()
  {
    if ($this->related_object_model)
    {
      return $this->related_object_model;
    }
    
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
  
  public function getSelectionPreviewDiv()
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