<?php


class Paysolutions_Payso_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $payso = Mage::getModel('payso/payso');

        $form = new Varien_Data_Form();
        $form->setAction($payso->getUrl())
            ->setId('payso_checkout')
            ->setName('payso_checkout')
            ->setMethod('post')
            ->setUseContainer(true);
        foreach ($payso->getCheckoutFormFields() as $field=>$value) {
            $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }
        $html = '<html><body>';
        $html.= $this->__('You will be redirected to Paysolutions in a few seconds.');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("payso_checkout").submit();</script>';
        $html.= '</body></html>';

        return $html;
    }//end function _toHtml
}//end class Paysolutions_Payso_Block_Redirect
