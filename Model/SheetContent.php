<?php
App::uses('AppModel', 'Model');
/**
 * SheetContent Model
 *
 * @property Sheet $Sheet
 * @property Content $Content
 */
class SheetContent extends ViewContentFactoryAppModel implements Iinterpetable {

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
		'content_id' => array(
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
		    'className' => 'ViewContentFactory.Sheet'
		),
	    	'Content' => array(
		    'className' => 'ViewContentFactory.Content'
		)
	);
        /**
         * parses the data form a form and saves it in to the proper table
         * also lays the relations
         * @param type $formData
         */
        public function parse($formData, $id){
            
            foreach($formData as $content){
               foreach($content as $key => $value){
                   
                   $this->create();
                   $this->Content->create();
                   
                   if(!$this->Content->save(
                       array(
                           'name' => $key,
                           'value' => $value
                       )
                   )){
                       App::uses('SaveException', 'Controller/Exception');
                       throw new SaveException(array('content' => 'Content'));
                   }
                   
                   if(!$this->save(
                       array(
                           'sheet_id' => $id,
                           'content_id' => $this->Content->id
                       )
                   )){
                       App::uses('SaveException', 'Controller/Exception');
                       throw new SaveException(array('content' => 'SheetContent'));
                   }                    

               }
           }  
           
        }

    public function interpet($data, $callback) {
	$data = $data['SheetContent'];
	foreach($data as $contentPointer){
	    $content = $this->Content->findById($contentPointer['content_id']);
	    $callback($content['Content']['name'], $content['Content']['value']);
	}
    }
	
	
}
