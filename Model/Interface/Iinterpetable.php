<?php

/*
 * For more information about the license see jappieklooster.nl/license
 */

/**
 * defines models that have the ability to interpet data.
 * @author jappie
 */
interface Iinterpetable {
    /**
     * loops trough data and passes it into a calback method.
     * @param type $data
     * @param type $callback
     * @return void
     */
    public function interpet($data, $callback);
}