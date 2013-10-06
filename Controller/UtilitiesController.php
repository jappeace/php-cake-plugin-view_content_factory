<?php

App::uses('AppController', 'Controller');

/**
 * Allows extra data to be served to system admin. Like printing out a non minified javascript.
 */
class UtilitiesController extends ViewContentFactoryAppController {
    public $uses = array();
    
    public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->deny();
    }
    
    public function renderJavascript(){
    }
}
?>
