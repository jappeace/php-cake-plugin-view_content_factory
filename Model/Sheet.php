<?php
App::uses('AppModel', 'Model');
/**
 * Sheet Model
 *
 * @property SheetContent $SheetContent
 * @property SheetStructure $SheetStructure
 */
class Sheet extends AppModel {

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
		'view_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'SheetContent' => array(
			'className' => 'SheetContent',
			'foreignKey' => 'sheet_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'SheetStructure' => array(
			'className' => 'SheetStructure',
			'foreignKey' => 'sheet_id',
			'dependent' => true,
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
        public $hasOne = 'Template';
        
        /**
         * interpets the data from the database by selecting a sheet by name and then call the
         * the calback with as parameters the data of the other models.
         * @param type $name
         * @param type $callback
         * @return type
         * @throws NotFoundException
         */
        public function interpet($name, $callback){
            $d = $this->findByName($name);
            if (!$d) {
                throw new NotFoundException(__('Invalid sheet'));
            }
            foreach($d['SheetContent'] as $contentPointer){
                $content = $this->SheetContent->Content->findById($contentPointer['content_id']);
                $callback($content['Content']['name'], $content['Content']['value']);
            }
            foreach($d['SheetStructure'] as $structurePointer){
                $struct = $this->SheetStructure->Structure;
                $parent = $struct->read(array('lft','rght'), $structurePointer['structure_id']);
                $structure = $struct->find('threaded',array(
                    'fields' => array(
                        'name', 
                        'value', 
                        'parent_id'
                    ),
                    'conditions' => array(
                        'Structure.lft >=' => $parent['Structure']['lft'],
                        'Structure.rght <=' => $parent['Structure']['rght'] 
                    )
                ));
                foreach($structure as $s){
                    if($s['Structure']['id'] == $structurePointer['structure_id']){ // did not work with conditions because it filtered out the childeren
                        $result = $struct->viewPrepare($s);
                        foreach($result as $key => $value){
                            $callback($key, $value);
                        }
                    }
                }

            }
            return $d['Sheet']['view_name'];
        }
        
        /**
         * parses the raw form data and adds it to the apropiate tables.
         * mainly by calling the parse functions of other models
         * @param type $data
         * @return boolean succes
         */
        public function parse($data){
            $this->create();
                       
            if ($this->save($data)) {
                
                // fix integrety
                $this->SheetContent->parse(
                    // value of key is dependond on which views are fetched and put into the form
                    $data[$data['Sheet']['view_name']]['Contents'], 
                    $this->id
                );
                $this->SheetStructure->parse(
                    $data[$data['Sheet']['view_name']]['Structures'], 
                    $this->id
                );
                return true;
            }
            return false;
        }
        
        /**
         * the entire cms works by a unique name instead of id to make it more
         * user friendly. However cakephp prefers an id int field. this function
         * bridges that gap.
         * get the id by name.
         * @param type $name
         * @return type
         */
        public function getIdBy($name){
            return $this->findByName($name)['Sheet']['id'];
        }

}
