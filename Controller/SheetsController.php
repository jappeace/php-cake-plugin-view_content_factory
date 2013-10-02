<?php

App::uses('AppController', 'Controller');

/**
 * Sheets Controller
 *
 * @property Sheet $Sheet
 * @property PaginatorComponent $Paginator
 */
class SheetsController extends ViewContentFactoryAppController {
    public $helpers = array('Js' => array('Jquery'), 'ViewContentFactory.Structure');
    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');
    
    public function beforeFilter() {

	/**
	 * Only allows view actions open for public use. Don't forget to define your own authcomponent
	 * in the your appcontroller class ie:
	     public $components = array(
        'Auth' => array(
            'loginAction' => array(
		'plugin' => false,
                'controller' => 'people',
                'action' => 'login'
            ),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email'),
                    'userModel' => 'Person'
                )
            ),
            'authorize' => 'Controller'
        ));
	 */
        parent::beforeFilter();
        $this->Auth->allow('view');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Sheet->recursive = 0;
        $this->set('sheets', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $name
     * @return void
     */
    public function view($name = null) {
        $this->set('path', $this->Sheet->Template->getPath());
        $this->render($this->Sheet->Template->getViewdir(). DS . 
            // interpet function returns the view name
            $this->Sheet->interpet($name, 
                function($key, $value){
                    $this->set($key, $value);
                }
            )
        );
    }

    /**
     * add method parses the generated form, also handles form generation trough the template model
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            if ($this->Sheet->parse($this->request->data)) {
                
                $this->Session->setFlash(__('The sheet has been saved.'));
                // rederict to the new page (which does not work yet)
                return $this->redirect(
                    array(
                        'action' => 'view', 
                        $this->request->data['Sheet']['name']
                    )
                );

            } else {
                $this->Session->setFlash(__('The sheet could not be saved. Please, try again.'));
            }
        }
        $this->set('views', $this->Sheet->Template->parseViews());
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $name
     * @return void
     */
    public function edit($name = null) {
        
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Sheet->id = $this->Sheet->getIdBy($name);
            if($this->Sheet->exists()){
                $this->Sheet->delete();
            }
            if ($this->Sheet->parse($this->request->data)) {
                $this->Session->setFlash(__('The sheet has been saved.'));
                return $this->redirect(array('action' => 'view', $name));
            } else {
                $this->Session->setFlash(__('The sheet could not be saved. Please, try again.'));
            }
            
        } else {
            $vars = array();
            $this->request->data['Sheet']['view_name'] = $this->Sheet->interpet($name, 
                function($key, $value) use (&$vars){
                    $vars[$key] = $value;
                }
            );
            $this->request->data['Sheet']['name'] = $name;
            $this->set('values', $vars);
            $this->set('template', 
                array(
                    $this->request->data['Sheet']['view_name'] => 
                        $this->Sheet->Template->parseView(
                            $this->request->data['Sheet']['view_name'] . '.ctp'
                        )
                )
            );
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($name = null) {
        $this->Sheet->id = $this->Sheet->getIdBy($name);
        if (!$this->Sheet->exists()) {
            throw new NotFoundException(__('Invalid sheet'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Sheet->delete()) {
            $this->Session->setFlash(__('The sheet has been deleted.'));
        } else {
            $this->Session->setFlash(__('The sheet could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
    

}
