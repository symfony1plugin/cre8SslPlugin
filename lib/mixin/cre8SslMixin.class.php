<?php 

class cre8SslMixin
{
  
  /**
   * Returns true if the action must always be called in SSL.
   *
   * @param  sfAction A sfAction instance
   *
   * @return Boolean  true if the action must always be called in SSL, false otherwise
   */
  public function sslRequired(sfAction $action)
  {
    $security = $action->getSecurityConfiguration();
    
    $retVal = false;
    
    $actionName = $action->getActionName();

    if (isset($security[$actionName]['require_ssl']))
    {
      $retVal = $security[$actionName]['require_ssl'];
    }

    if (isset($security['all']['require_ssl']))
    {
      $retVal = $security['all']['require_ssl'];
    }
    /*
    echo '<pre>';
    print_r(array(
      'securityConfig' => $security,
      'returnValue' => $retVal
    ));
    echo '</pre>';
    die('-------- TEST --------');
    */
    
    return $retVal;
  }

  /**
   * Returns true if the action can be called in SSL.
   *
   * @param  sfAction A sfAction instance
   *
   * @return Boolean  true if the action can be called in SSL, false otherwise
   */
  public function sslAllowed(sfAction $action)
  {
    $security = $action->getSecurityConfiguration();
    $actionName = $action->getActionName();

    // If ssl is required, then we can assume they also want to allow it
    if ($this->sslRequired($action))
    {
      return true;
    }

    if (isset($security[$actionName]['allow_ssl']))
    {
      return $security[$actionName]['allow_ssl'];
    }

    if (isset($security['all']['allow_ssl']))
    {
      return $security['all']['allow_ssl'];
    }

    return false;
  }

  /**
   * Returns the SSL URL for the given action.
   *
   * @param  sfAction A sfAction instance
   *
   * @return Boolean  The fully qualified SSL URL for the given action
   */
  public function getSslUrl(sfAction $action)
  {
    
    $security = $action->getSecurityConfiguration();
    $actionName = $action->getActionName();

    if (isset($security[$actionName]['ssl_domain']))
    {
      return $security[$actionName]['ssl_domain'].$action->getRequest()->getScriptName().$action->getRequest()->getPathInfo();
    }
    else if (isset($security['all']['ssl_domain']))
    {
      return $security['all']['ssl_domain'].$action->getRequest()->getScriptName().$action->getRequest()->getPathInfo();
    }
    else
    {
      return substr_replace($action->getRequest()->getUri(), 'https', 0, 4);
    }
  }

  /**
   * Returns the non SSL URL for the given action.
   *
   * @param  sfAction A sfAction instance
   *
   * @return Boolean  The fully qualified non SSL URL for the given action
   */
  public function getNonSslUrl(sfAction $action)
  {
    $security = $action->getSecurityConfiguration();
    $actionName = $action->getActionName();

    if (isset($security[$actionName]['non_ssl_domain']))
    {
      return $security[$actionName]['non_ssl_domain'].$action->getRequest()->getScriptName().$action->getRequest()->getPathInfo();
    }
    else if (isset($security['all']['non_ssl_domain']))
    {
      return $security['all']['non_ssl_domain'].$action->getRequest()->getScriptName().$action->getRequest()->getPathInfo();
    }
    else
    {
      return substr_replace($action->getRequest()->getUri(), 'http', 0, 5);
    }
  }
}