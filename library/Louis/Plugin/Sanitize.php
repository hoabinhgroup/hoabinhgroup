<?php
class Louis_Plugin_Sanitize extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopShutdown()
    {
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );
        $replace = array(
            '>',
            '<',
            '\\1'
        );
        $body = preg_replace($search, $replace, $this->getResponse()->getBody());

        $this->getResponse()->setBody($body);

        unset($body);
    }
}