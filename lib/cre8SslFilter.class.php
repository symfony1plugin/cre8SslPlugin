<?php

class cre8SslFilter extends sfFilter 
{
  public function execute($filterChain)
  {
    $prefix   = 'app_cre8_ssl_plugin_';
    $context  = $this->getContext();
    $user     = $this->context->getUser();
    $request  = $this->context->getRequest();
    $response = $this->context->getResponse();
    
    if ($this->isFirstCall())
    {
      // only redirect if not posting and we actually have an http(s) request
      if ($request->getMethod() != sfRequest::POST && substr($request->getUri(), 0, 4) == 'http')
      {
        $controller = $context->getController();
         // get the current action instance
        $actionEntry    = $controller->getActionStack()->getLastEntry();
        $actionInstance = $actionEntry->getActionInstance();
        /*
        echo '<pre>';
        print_r(array(
          'actionInstance_sslRequired' => $actionInstance->sslRequired()
        ));
        echo '</pre>';
        die('-------- TEST --------');
        */
        
        // request is SSL secured
        if ($request->isSecure())
        {
          // but SSL is not allowed
          if (!$actionInstance->sslAllowed() && $this->redirectToHttp())
          {
            $controller->redirect($actionInstance->getNonSslUrl());
            exit();
          }
        }
        // request is not SSL secured, but SSL is required
        elseif ($actionInstance->sslRequired() && $this->redirectToHttps() && $context->getConfiguration()->getEnvironment() == 'prod')
        {
          $controller->redirect($actionInstance->getSslUrl());
          exit();
        }
      }
    }
    $filterChain->execute();
  }
  
  protected function redirectToHttps()
  {
    return true;
  }

  protected function redirectToHttp()
  {
    return true;
  }
  
}