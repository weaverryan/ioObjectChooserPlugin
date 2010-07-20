<?php

class ioObjectChooserHelper
{
  protected $widget = null;
  
  protected $dom_id = null;
  
  public function __construct(sfWidgetFormInput $arg1)
  {
    $this->widget = $arg1;
  }
  
  public function getButton()
  {
    $model = $this->getModel();
    $result = '<div class="io_object_chooser_button"><a href="#">Choose '.$model.'</a></div>';
    require_once(sfConfig::get('sf_symfony_lib_dir').'/helper/UrlHelper.php');
// get active -> load helpers
    $url = url_for('io_object_chooser_index', array('model'=>$this->getModel()));
    $result .= '<script type="text/javascript">';
    $result .= <<<EOF
      jQuery(document).ready(function () {
          
          jQuery('#$this->dom_id .io_object_chooser_button a').click(function () {
            
            var popup = jQuery('#$this->dom_id div.io_object_chooser_popup');
            
            popup.toggle();
            
            return load_io_object_chooser_popup('$url');
          });
          
          jQuery('#$this->dom_id .io_object_chooser_pagination a').live('click', function () {
            var url = $(this).attr('href');
            return load_io_object_chooser_popup(url);
          });
          
          jQuery('#$this->dom_id .io_object_chooser_selection a').live('click',function () {
            var object_id = $(this).parent().attr('rel');
            return io_object_chooser_choose(object_id);
          });
          
        });
      
      function io_object_chooser_choose(object_id)
      {
        jQuery('#$this->dom_id input').val(object_id);
        var popup = jQuery('#$this->dom_id div.io_object_chooser_popup');
        popup.hide();
        return false;
      }
      
      function load_io_object_chooser_popup (url) {
        var popup = jQuery('#$this->dom_id div.io_object_chooser_popup');
        jQuery.ajax({
          url: url,
          success: function (data, textStatus, XMLHttpRequest) {
            popup.html(data);
          }
        });
      
        return false;
      }
EOF;
    $result .= '</script>';
    return $result;
  }
  
  public function getPopup()
  {
    $result = '<div class="io_object_chooser_popup" style="display: none"></div>';
    return $result;
  }
  
  public function getHtml($input_tag_html)
  {
    $this->dom_id = 'io_chooser_'.rand();
    $result = '<div id="'.$this->dom_id.'" class="io_object_chooser_wrapper">';
    $result .= $input_tag_html;
    $result .= $this->getButton();
    $result .= $this->getPopup();
    $result .= '</div>';
    return $result;
  }
  
  public function getModel()
  {
    return $this->widget->getOption('model');
  }
}