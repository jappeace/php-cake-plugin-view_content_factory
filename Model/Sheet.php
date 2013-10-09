<?php
App::uses('AppModel', 'Model');
App::uses('Iinterpetable', 'Model/Interface');
/**
 * Sheet Model
 *
 * @property SheetContent $SheetContent
 * @property SheetStructure $SheetStructure
 */
class Sheet extends ViewContentFactoryAppModel {

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
			'className' => 'ViewContentFactory.SheetContent',
			'dependent' => true
		),
	    	'SheetStructure' => array(
			'className' => 'ViewContentFactory.SheetStructure',
			'dependent' => true
		)
	);
        public $hasOne = array(
		'Template' => array(
		    'className' => 'ViewContentFactory.Template'
		)
	    );
	
	/**
	 * allows runtime changing of the interpet method
	 * @var type 
	 */
        private $interpatables;
	public function __construct($id = false, $table = null, $ds = null) {
	    parent::__construct($id, $table, $ds);
	    $this->interpatables = array($this->SheetContent, $this->SheetStructure);
	}
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
	    
	    foreach($this->interpatables as $interpatable){
		if(!$interpatable instanceof Iinterpetable){
		    throw new CakeException("Could not interped data");
		}
		$interpatable->interpet($d, $callback);
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
	
	public function getNamesByViewAndVar($view, $var){
	    // determin which model to target
	    $names = explode('.', $var);
	    $var = substr($var, stripos($var, '.')+1);
	    
	    $sheets = $this->find('all', 
		array(
		    'conditions' => array(
			'view_name' => $view
		    ),
		    'fields' => array(
			'id',
			'name'
		    ),
		    'recursive' => -1
		)
	    );
	    
	    if($names[0] === 'content'){
		$this->interpatables = array($this->SheetContent);
		$this->interpetAll($sheets);
		
	    }elseif($names[0] === 'struct'){
		$this->interpatables = array($this->SheetStructure);
		$this->interpetAll($sheets);
	    }else{
		// somthing different, exit
		throw new CakeException("Unknown type");
	    }
	    
	    $this->interpatables = array($this->SheetContent, $this->SheetStructure);
	    return $sheets;
	}
	
	/**
	 * interpet all the sheets and adds the result to a data key.
	 * @param type $sheets
	 * @return type $sheets
	 */
	private function interpetAll(&$sheets){
	    $length = count($sheets);
	    for($i = 0; $i < $length; $i++){
		$this->interpet($sheets[$i]['Sheet']['name'], 
		    function($key, $value) use($sheets, $i){
			$sheets[$i]['data'][$key] = $value;
		    }
		);
	    }	    
	    return $sheets;
	}

}
