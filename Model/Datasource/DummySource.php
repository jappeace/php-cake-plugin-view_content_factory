<?php

/*
 *  See jappieklooster.nl/license for more information about the licensing
 */

/**
 * Description of DummySource
 * Fixes connection troubles for template
 * @author jappie
 */
class DummySource extends DataSource {

    function connect() {
        $this->connected = true;
        return $this->connected;
    }

    function disconnect() {
        $this->connected = false;
        return !$this->connected;
    }

    function isConnected() {
        return true;
    }

}

?>
