<?php

class Paysolutions_Payso_Model_Payso_Session extends Mage_Core_Model_Session_Abstract
{
    public function __construct()
    {
        $this->init('payso');
    }//end function __construct
}//end class Paysolutions_Payso_Model_Payso_Session
