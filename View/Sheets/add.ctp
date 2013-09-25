<?php
$this->extend('/Sheets/Structs/article-nav');
$this->start('article');
echo $this->element('form/sheet', array('views' => $views));
$this->end();
$this->assign('nav', $this->element('nav/acount'));