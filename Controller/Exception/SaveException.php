<?php

/*
 *  See jappieklooster.nl/license for more information about the licensing
 */

/**
 * Description of SaveException
 *
 * @author jappie
 */
class SaveException extends CakeException {
    protected $_messageTemplate = 'Could not save %s ';
    public function __construct($message, $code = 500){
        if(is_array($message)){
            $size = count($message);
            $this->_messageTemplate .= '(';
            for($i = 1; $i < $size; $i++){
                $this->_messageTemplate .= '%s ,';
            }
            $this->_messageTemplate = 
                substr(
                    $this->_messageTemplate,
                    0,
                    strlen($this->_messageTemplate)-2
                ).')';
        }
        parent::__construct($message, $code);
    }
}

?>
