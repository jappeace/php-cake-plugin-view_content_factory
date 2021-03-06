<?php
App::uses('AppModel', 'Model');
/**
 * Content Model
 *
 */
class Content extends ViewContentFactoryAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'value' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);
}
