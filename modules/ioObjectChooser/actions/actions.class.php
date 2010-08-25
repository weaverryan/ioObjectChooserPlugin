<?php

/**
 * ioObjectChooser actions
 *
 * @package    iostudio
 * @subpackage io_portfolio_item
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ioObjectChooserActions extends sfActions
{
  public function executeShow(sfWebRequest $request)
  {
    $this->id = $request->getParameter('id');
    $this->model = $request->getParameter('model');
    $this->object = Doctrine_Query::create()->from($this->model.' o')->where('o.id = ?', $this->id)->fetchOne();
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->model = $request->getParameter('model');
    $this->page = $request->getParameter('page', 1);
    $this->per_page = $this->getPerPage($request);
    
    $object_q = Doctrine_Query::create()->from($this->model.' o');
    
    $config = sfConfig::get('app_io_object_chooser_filter');
    
    $this->filter_enabled =
      $config['default']['enable']
        ||
      (isset($config[$this->model]['enable']) && $config[$this->model]['enable']);
    
    if ($this->filter_enabled)
    {
      $filter_class = $this->model.'FormFilter';
      $this->filter = new $filter_class();
      $default_filter_values = $this->getUser()->getAttribute('io_object_chooser', array(), $this->model);
      $filter_values = $request->getParameter($this->filter->getName(), $default_filter_values);
      $this->getUser()->setAttribute('io_object_chooser', $filter_values, $this->model);
    
      if ($filter_values)
      {
        $this->filter = new $filter_class();
        $this->filter->bind($filter_values);
        $object_q = $this->filter->getQuery();
      }
    }
    
    $this->pager = new sfDoctrinePager($this->model, $this->per_page);
    $this->pager->setQuery($object_q);
    $this->pager->setPage($this->page);
    $this->pager->setMaxPerPage($this->per_page);
    $this->pager->init();
  }
  
  protected function getPerPage(sfWebRequest $request, $default = 3)
  {
    $namespace = 'io_object_chooser_'.$this->model;
    
    $per_page = $request->getParameter('per_page', null);
    
    if ($per_page)
    {
      $this->getUser()->setAttribute('per_page', $per_page, $namespace);
      return $per_page;
    }
    else
    {
      return $this->getUser()->getAttribute('per_page', $default, $namespace);
    }
  }
}
