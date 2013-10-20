<?php
App::uses('AppModel', 'Model');
App::uses('Iinterpetable', 'ViewContentFactory.Model/Interface');
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
	    echo '<pre>'.print_r($data, true) . '</pre>';
			if ($this->save($data)) {
				$keys = array_keys($this->hasMany);
				foreach($keys as $type){
					// shortcut for obvious reasons (its long)
					if(!isset($data[
								$data['Sheet']['view_name']
							][
								Inflector::pluralize(str_replace('Sheet', '', $type))
							])){
					    continue;
					}
					$target = $data[
								$data['Sheet']['view_name']
							][
								Inflector::pluralize(str_replace('Sheet', '', $type))
							];
					if(isset($target['existing'])){
						$this->$type->save(
							array(
								'sheet_id' => $this->id, 
								Inflector::tableize($this->$type->name).'_id' => $target['existing']
							)
						);
					}else{
						$this->$type->parse(
							$target,
							$this->id
						);
					}
				}
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
			$this->interpetAll($sheets, $var);
	    }elseif($names[0] === 'structure'){
			$this->interpatables = array($this->SheetStructure);
			$this->interpetAll($sheets, $var);
	    }else{
			// somthing different, exit
			throw new CakeException("Unknown type");
		}
		$return = array();
		$return["data"] = $sheets;
		$return["type"] = $names[0];
		$return["viewName"] = $view;
		$return["varName"] = $var;

	    $this->interpatables = array($this->SheetContent, $this->SheetStructure);
	    return $return;
	}
	/**
	 * interpet all the sheets and adds the result to a data key.
	 * @param type $sheets
	 * @return type $sheets
	 */
	private function interpetAll(&$sheets, $filter){
	    // editing the sheets while looping trough them is a bad idea. thats why a copy is required
	    $result = $sheets;
	    $length = count($sheets);
	    for($i = 0; $i < $length; $i++){
		$this->interpet($sheets[$i]['Sheet']['name'], 
		    function($key, $value) use(&$result, $i, $filter){
			if($key == $filter){
			    // clean up the nesting
			    $result[$i] = $result[$i]['Sheet'];
			    // data and selected element
			    $result[$i]['key'] = $filter;
			    $result[$i]['value'] = $value;
			}else{
			    unset($result[$i]);
			}
		    }
		);
	    }
	    $sheets = $result;
	    return $sheets;
	}

}
