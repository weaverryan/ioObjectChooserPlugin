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
  // a generic "show" action that will show any model/id combo
  public function executeShow(sfWebRequest $request)
  {
    $this->id = $request->getParameter('id');
    $this->model = $request->getParameter('model');
    $this->object = Doctrine_Query::create()->from($this->model.' o')->where('o.id = ?', $this->id)->fetchOne();
    
    $this->forward404Unless($this->object, 'No object by that ID found for that model.');
  }
  
  /**
   * a generic "index" action -- can be provided a set of filter params to
   * perform a search.  provides sticky pagination and filtering.
   */
  public function executeIndex(sfWebRequest $request)
  {
    // setup the action from the request parameters
    $this->model = $request->getParameter('model');
    $this->page = $request->getParameter('page', 1);
    $this->per_page = $this->getPerPage($request);
    
    // get a basic object query set up to show the user some objects
    $object_q = Doctrine_Query::create()->from($this->model.' o');
    
    // grab the user's merged configuration from app.yml
    $config = sfConfig::get('app_io_object_chooser_filter');
    
    // check if the filtering should be enabled (configured by plugin user in app.yml)
    $this->filter_enabled =
      $config['default']['enable'] && !isset($config[$this->model]['enable'])
        ||
      isset($config[$this->model]['enable']) && $config[$this->model]['enable']
    ;
    
    if ($this->filter_enabled)
    {
      // hide the filter form in the view by default (shown by javascript button)
      $this->hide_search_box = true;
      
      // get the filter object based on the model
      $this->filter = $this->getFormFilter($this->model);
      
      // get the name of the filter form
      $filter_name = $this->filter->getName();
      
      // look up the user's filter values (from last time, or a blank array at first)
      $this->filter_values = $this->getFilterValues($request, $this->model, $filter_name);
      
      // check if the values array is empty
      if (!$this->filterValuesIsEmpty($this->filter_values))
      {
        // show the search box since we have search values
        $this->hide_search_box = false;
        
        // bind the search values to the filter form
        $this->filter->bind($this->filter_values);
        
        // reset the object query to reflect the search
        $object_q = $this->filter->getQuery();
      }
    }
    
    // set up the pager based on the model, limit, search query, and page number
    $this->pager = new sfDoctrinePager($this->model, $this->per_page);
    $this->pager->setQuery($object_q);
    $this->pager->setPage($this->page);
    $this->pager->setMaxPerPage($this->per_page);
    $this->pager->init();
  }
  
  public function executeNew(sfWebRequest $request)
  {
    $this->model = $request->getParameter('model');
    
    $config = sfConfig::get('app_io_object_chooser_add_new');
    
    if (!isset($config[$this->model]) || !$config[$this->model]['module'] || !$config[$this->model]['action'])
    {
      $this->renderText('Please configure app.yml to provide a module and action under the "add_new" key.');
      return sfView::NONE;
    }
    
    $module = $config[$this->model]['module'];
    $action = $config[$this->model]['action'];
    
    $this->forward($module,$action);
  }
  
  // check if the values array is empty (not a simple empty() call since 
  // filter value arrays are stored with complex keys like "text" for the
  // actual value, and even blank forms make arrays with indexes set and empty
  // strings as values)
  protected function filterValuesIsEmpty($values = array())
  {
    if ($values && !empty($values))
    {
      foreach ($values as $value)
      {
        if (is_array($value) && isset($value['text']) && $value['text'])
        {
          return false;
        }
      }
    }
    
    return true;
  }
  
  // get the per-page limit based on the user's previous decisions
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
  
  // get the filter values array if it's defined, return a blank array if it's not defined
  protected function getFilterValues($request, $model, $filter_name)
  {
    $default_filter_values = $this->getUser()->getAttribute('io_object_chooser_filter', array(), $model);
    $filter_values = $request->getParameter($filter_name, $default_filter_values);
    $this->getUser()->setAttribute('io_object_chooser_filter', $filter_values, $model);
    return $filter_values;
  }
  
  // make a new form filter based on the model name, using fields configured
  // in app.yml (optional)
  protected function getFormFilter($model, $config = null)
  {
    $filter_class = $model.'FormFilter';
    $filter = new $filter_class();
    
    $filter->disableLocalCSRFProtection();
    
    if (!$config)
    {
      $config = sfConfig::get('app_io_object_chooser_filter');
    }
    
    if (isset($config[$model]['fields']) && is_array($config[$model]['fields']))
    {
      $filter->useFields($config[$model]['fields']);
    }
    
    return $filter;
  }
}
