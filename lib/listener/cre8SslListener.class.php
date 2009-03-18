<?php 

class cre8SslListener
{
  /**
   * Get the current tracker object.
   * 
   * @param   sfEvent $event
   * 
   * @return  bool
   */
  public static function observe(sfEvent $event)
  {
    $cre8SslMixinObj = new cre8SslMixin();
    if (!method_exists($cre8SslMixinObj, $method = $event['method']))
    {
      return false;
    }
//    die('method: ' . $method);

    $event->setReturnValue(call_user_func(array($cre8SslMixinObj, $method), $event->getSubject()));
    
    return true;
    
  }
  
}