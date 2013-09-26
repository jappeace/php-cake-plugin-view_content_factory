<?php
App::uses('AppModel', 'Model');
/**
 * SheetStructure Model
 *
 * @property Sheet $Sheet
 * @property Structure $Structure
 */
class SheetStructure extends ViewContentFactoryAppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'sheet_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'structure_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Sheet' => array(
			'className' => 'Sheet',
			'foreignKey' => 'sheet_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Structure' => array(
			'className' => 'Structure',
			'foreignKey' => 'structure_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
        /**
         * parses the data from the form (wich is order prety non convetional) and inserts it into the db
         * @param type $formData
         * @param type $id
         * @throws SaveException
         */
        public function parse($formData, $id){
            
            foreach($formData as $varname => $value){
                $this->create();
                $this->Structure->create();
                
                // make target
                if(!$this->Structure->save(
                    array(
                        'name' => $varname,
                        'value' => (is_array($value)) ? null: $value,
                        'parent_id' => null
                    )
                )){
                    
                    App::uses('SaveException', 'Controller/Exception');
                    throw new SaveException(
                        array(
                            'content' => 'structure', 
                            'name' => $varname, 
                            'value' => (is_array($value)) ? 'M.T.': $value,
                        )
                    );
                }
                
                // fix relation to sheet
                if(!$this->save(
                    array(
                        'structure_id' => $this->Structure->id,
                        'sheet_id' => $id
                    )
                )){
                    App::uses('SaveException', 'Controller/Exception');
                    throw new SaveException(
                        array(
                            'content' => 'structure relation with sheet', 
                            'structure_id' => $this->Structure->id, 
                            'sheet_id' => $id
                        )
                    );
                }
                
                // make children
                if(is_array($value)){
                    $this->Structure->makeChild($value, $this->Structure->id);
                }
                
            }
        }
}
