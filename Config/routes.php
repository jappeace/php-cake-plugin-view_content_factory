<?php

/**
 * Shorten path to cms views, other actions are not neccisary since they are not shown to general public.
 * Breaks convention intentionaly so that the views controller option is still available
 */
	Router::connect('/view/*', array('plugin' => 'view_content_factory', 'controller' => 'sheets', 'action' => 'view'));

?>
