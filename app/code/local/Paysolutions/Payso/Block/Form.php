<?php


class Paysolutions_Payso_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        $this->setTemplate('payso/form.phtml');
        parent::_construct();
    }//end function _construct
}//end class Paysolutions_Payso_Block_Form
