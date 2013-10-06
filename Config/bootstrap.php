<?php

App::build(
    array(
	// cakephp has no conventions about exeptions. so I defined a package for them
	'Controller/Exception' => array('%s' . 'Controller' . DS . 'Exception' . DS),
	// or for interfaces. I did not realy need it but it just works better (no fatal exception but my own).
	'Model/Interface' => array('%s' . 'Model' . DS . 'Interface' . DS)
    ), 
    App::REGISTER
);
