<?php

class ioObjectRenderer
{
  protected $config;
  
  public function __construct(sfApplicationConfiguration $app_configuration, array $config)
  {
    $this->app_configuration = $app_configuration;
    $this->config = $config;
  }

  public function render($obj)
  {
    $model = get_class($obj);

    if ($this->getConfig($model, 'show_method'))
    {
      $method = $this->getConfig($model, 'show_method');

      $html = $this->object->$method();
    }
    else if ($this->getConfig($model, 'show_partial'))
    {
      $partial = $this->getConfig($model, 'show_partial');

      $this->app_configuration->loadHelpers(array('Partial'));

      $html = get_partial($partial, array('obj'=>$obj));
    }
    else
    {
      $html = (string) $obj;
    }
    
    return $html;
  }

  protected function getConfig($model, $key)
  {
    if (isset($this->config[$model]) && isset($this->config[$model][$key]))
    {
      return $this->config[$model][$key];
    }
    else
    {
      return false;
    }
  }
}