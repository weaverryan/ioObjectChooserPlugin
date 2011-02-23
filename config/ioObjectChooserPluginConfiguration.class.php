<?php

/**
 * Plugin configuration class for snPageElementPlugin
 *
 * @author Ryan Weaver <ryan.weaver@iostudio.com>
 */
class ioObjectChooserPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Bootstrap the plugin.
   */
  public function initialize()
  {
  }
  
  protected $renderer = null;
  
  public function getObjectRenderer()
  {
    if (!$this->renderer)
    {
      $config = sfConfig::get('app_io_object_chooser_render');
      $app_config = $this->configuration;
      $this->renderer = new ioObjectRenderer($app_config, $config);
    }

    return $this->renderer;
  }
}