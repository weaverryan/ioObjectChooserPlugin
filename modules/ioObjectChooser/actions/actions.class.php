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
  public function executeIndex(sfWebRequest $request)
  {
    $this->model = $request->getParameter('model');
    $this->page = $request->getParameter('page', 1);
    $this->per_page = $this->getPerPage($request);
    
    $object_q = Doctrine_Query::create()->from($this->model.' o');
    
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
