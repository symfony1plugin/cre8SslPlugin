<?php

/**
 * cre8SslPlugin configuration.
 * 
 * @package     cre8SslPlugin
 * @subpackage  config
 * @author      Bogumil Wrona <b.wrona@cre8newmedia.com>
 * @version     SVN: $Id: PluginConfiguration.class.php 12675 2008-11-06 08:07:42Z Kris.Wallsmith $
 */
class cre8SslPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $listener = array('cre8SslListener', 'observe');
    $this->dispatcher->connect('component.method_not_found', $listener);
      
    $this->dispatcher->connect('request.method_not_found', $listener);
    $this->dispatcher->connect('response.method_not_found', $listener);
    $this->dispatcher->connect('user.method_not_found', $listener);
  }
}
