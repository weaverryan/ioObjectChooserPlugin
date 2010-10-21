<?php

class ioObjectChooserHelper
{
  // the field name of the widget we are helping (i.e. string of 'form_name[widget_name]' )
  protected $field_name = null;
  // submitted values of the widget
  protected $widget_values = array();
  // the name of the related object's model
  protected $related_object_model = null;
  // to enable the add_new button
  protected $enable_add_new = false;
  // the state of the sorting ability
  protected $enable_sorting = false;

  /**
   * @param the form's object
   * @param the name of the relation from the form's object to whatever we are relating to
   * @param the name of the widget in html land (i.e. string of 'form_name[widget_name]' )
   */
  public function __construct($options, $widget_values = array())
  {
    $this->related_object_model = $options['model'];
    $this->field_name = $options['field_name'];
    $config = sfConfig::get('app_io_object_chooser_add_new');
    $this->enable_add_new = isset($config[$this->related_object_model]['enable']) ? $config[$this->related_object_model]['enable'] : false;
    $config = sfConfig::get('app_io_object_chooser_with_sorting');
    $this->enable_sorting = isset($config[$this->related_object_model]['enable']) ? $config[$this->related_object_model]['enable'] : false;
    $this->widget_values = $widget_values ? $widget_values : array();
  }
  
  public function getButton()
  {
    sfApplicationConfiguration::getActive()->loadHelpers(array('Url'));
    $url = url_for('io_object_chooser_index', array('model'=>$this->related_object_model));
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
    $serial_class = 'io_object_chooser_'.rand();
    $result = '<div class="'.$this->getWrapperClass().' '. $serial_class .'" rel="'.$this->field_name.'">';
    $result .= $this->getButton();
    $result .= $this->getResponseDiv();
    $result .= $this->getSelectionHolder($input_tag_html);
    $result .= $this->getSelectionPreviewDiv();
    $result .= $this->getInitJavascript($serial_class);
    if ($this->enable_add_new)
    {
      $result .= $this->getAddNewButton();
    }
    $result .= '</div>';
    return $result;
  }
  
  public function getAddNewButton()
  {
    $result = '<div class="io_object_chooser_add_new_button">';
    
    $url = url_for('io_object_chooser_new', array('model'=>$this->related_object_model));
    
    $result .= sprintf('<a href="%s">Add New %s</a>', $url, $this->getLabel());
    
    $result .= '</div>';
    
    return $result;
  }
  
  public function getLabel()
  {
    return $this->related_object_model;
  }
  
  public function getSelectionHolder($default = null)
  {
    $result = '<div class="io_object_chooser_holder">'.$default.'</div>';
    return $result;
  }
  
  public function getSelectionPreviewDiv()
  {
    sfApplicationConfiguration::getActive()->loadHelpers(array('Url'));
    $url = url_for('io_object_chooser_show', array('model'=>$this->related_object_model));
    
    $result = '<div class="io_object_chooser_preview" rel="'.$url.'"><ul></ul></div>';
    return $result;
  }
  
  public function getWrapperClass()
  {
    return 'io_object_chooser_wrapper choose_one';
  }
  
  public function getInitJavascript($serial_class)
  {
    $wrapper_class = $this->getWrapperClass();
    $js = <<<EOF
  <script type="text/javascript">
    jQuery(document).ready( function () {
      var selector = '.io_object_chooser_wrapper.$serial_class';
      var wrapper = $(selector);
      update_object_selection(wrapper);
    });
  </script>
EOF;
    
    return $js;
  }
}