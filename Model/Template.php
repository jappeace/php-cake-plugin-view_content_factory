<?php
App::uses('AppModel', 'Model');

/**
 * This model represents the views that can be used as templates to create pages.
 * Basicly it reads files and creates the apropiate structures for the files
 *
 */
class Template extends ViewContentFactoryAppModel {

    const MARKER_CONTENT = '!content!';
    const MARKER_STRUCT = '!struct!';

    const LFT_BOUND = '@#';
    const RGHT_BOUND = '#@';
    
    public $useTable = false;
    public $useDbConfig = 'dummy';
    
    public $belongsTo = array('Sheet' => array(
			'className' => 'ViewContentFactory.Sheet'
			));

    /**
     * this function finds the view in the view/sheets/structs folder then it looks what is in side
     * of them and decides which names are used for which variable fetch requests.
     * 
     * I decedied to use this because it is to most minimalistic and coherent solution I could think of
     * 
     * keeping track of these files trough a database could kill very easaly its integrety and the
     * file system is way faster anyways.
     */
    public function parseViews() {
        //load classes
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
	
	// merge templates from the plugin and the users and return them.
        return 
	    array_merge(
		$this->parse($this->getPath()), 
		$this->parse($this->getPluginPath())
	    );
    }
    private function parse($path){
        $result = array();
        $dir = new Folder($path);
        $files = $dir->find('.*\.ctp');
        foreach ($files as $file) {
            
            $result[
                (new File($dir->pwd() . DS . $file))->name()
            ] = 
                $this->parseView($file, $dir->pwd());
        }

        return $result;	
    }
    /**
     * alows parsing of a single file instead of a whole directory
     * @param type $name
     * @param type $path
     * @return type
     */
    public function parseView($name, $path = null){
        App::uses('File', 'Utility');
        
        $path = ($path === null)? $this->getPath() : $path;
        $file = new File($path . DS . $name);
        $contents = $file->read();

        $candidates = $this->parseString($contents);
        $structures = $contens = array();

        foreach ($candidates as $candidate) {

            /* only the fetch requests with the these characters as default are allowed.
             * this pretty much ensures that an view autor has to know what he's doing when wanting
             * to create a cms view, or at least do some reseach
             */
            $size = 0;
            if ($size = strpos($candidate, Template::MARKER_STRUCT)) {
                $structures[$this->parseName($candidate)] = $this->parseStruct($candidate);
            } elseif ($size = strpos($candidate, Template::MARKER_CONTENT)) {
                $contens[] = $this->parseName($candidate);
            }
        }
        
        $file->close(); 
        return array( 'contents' => $contens, 'structures' => $structures);
    }
    private function parseString($target) {
        $offset = 0;
        $result = array();
        //strip white spaces, they are irrelevant
        $target = preg_replace('/\s+/', '', $target);
        
        $lftBoundSize = strlen(Template::LFT_BOUND);
        $rghtBoundSize = strlen(Template::RGHT_BOUND);
        while ($postion = stripos($target, Template::LFT_BOUND, $offset)) {

            $end = stripos($target, Template::RGHT_BOUND, $postion);
            if (!$end) {
                break;
            }
            $string = substr(
		$target, 
		$postion + $lftBoundSize, 
		$end + 2 -$postion - $lftBoundSize - $rghtBoundSize
	    );
            $result[] = $string;
            $offset = $end;
        }
        return $result;
    }

    private function parseName($string) {
        $position = 0;
        if(!$position = stripos($string, '(')){
            // if not a ( of a struct then the name ends with a ! of a content
            $position = stripos($string, '!'); 
        }
        $name = substr($string, 0, $position);
        return $name;
    }
    
    /**
     * preperas string and script for the recursive parse
     * @param type $struct
     * @return type
     */
    private function parseStruct($struct){        
        // used by recursive parsed, but no need to call it every cycle
        App::uses('String', 'Utility'); 
        return $this->recursiveParseStruct(
                    substr(
                        $struct, 
                        stripos(
                            $struct, 
                            '('
                        )+1,
                        strripos(
                            $struct, 
                            Template::MARKER_STRUCT
                        )
                        -strlen(Template::MARKER_STRUCT) +1
                    )
                );
    }
    
    private function recursiveParseStruct($remainder){
        $array = String::tokenize($remainder);
        $result = array();
        $size = count($array);
        for($i = 0; $i < $size; $i ++){
            if($childStart = stripos($array[$i], '(')){
                
                // stripos found a sub array, recurse in to it
                $result[substr($array[$i], 0, $childStart)] = 
                    $this->recursiveParseStruct(
                        substr(
                            $array[$i], 
                            $childStart+1, 
                            strripos(
                                $array[$i], 
                                ')'
                            )
                        )
                    );
            }else{
                // some anoying chars that kept popping up...
                $array[$i] = str_replace(')', '', $array[$i]);
                $array[$i] = str_replace('!', '', $array[$i]);
                // kill all double elements
                $result[$array[$i]] = $array[$i];
            }
        }
        return $result;
    }
    
    /**
     * gets the path to the view templates
     * @return string
     */
    public function getPath(){
        return $this->pathCake().$this->pathView();
    }
    /**
     * get the path to the plugin folder's view
     * @return type
     */
    public function getPluginPath(){
        return $this->pathCake().DS.'Plugin' .DS . $this->plugin .$this->pathView();
    }
    /**
     * to the cake root dir
     * @return type
     */
    private function pathCake(){
	return ROOT. DS . APP_DIR;
    }
    /**
     * to the final view
     * @return type
     */
    private function pathView(){
	return DS . 'View'.DS. Inflector::pluralize($this->alias);
    }
}
