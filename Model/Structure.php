<?php
App::uses('AppModel', 'Model');
/**
 * Structure Model
 *
 * @property Structure $ParentStructure
 * @property Structure $ChildStructure
 */
class Structure extends AppModel {

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Tree',
	);

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
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ParentStructure' => array(
			'className' => 'Structure',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ChildStructure' => array(
			'className' => 'Structure',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
        
        public function makeChild($source, $parentId){
            foreach($source as $varname => $value){
                $this->create();
                if(!$this->save(
                    array(
                        'name' => $varname,
                        'value' => (is_array($value)) ? '': $value,
                        'parent_id' => $parentId
                    )
                )){
                    App::uses('SaveException', 'Controller/Exception');
                    throw new SaveException(
                        array(
                            'content' => 'structure', 
                            'name' => $varname, 
                            'value' => (is_array($value)) ? '': $value
                        )
                    );
                }
                if(is_array($value)){
                    $this->makeChild($value, $this->id);
                }
            }
        }
        
        public function viewPrepare($data){
            if($data['children'] === array()){
                return array($data['Structure']['name'] => $data['Structure']['value']);
            }
            $result = array();
            foreach($data['children'] as $child){
                $find = $this->viewPrepare($child);
                foreach($find as $key => $value){
                    $result[$key] = $value;
                }
            }
            return array($data['Structure']['name'] => $result);
        }
}
