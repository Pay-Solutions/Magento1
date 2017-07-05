<?php
/*
*Paysolutions Payso Controller
*By: Paysolutions
*/

class Paysolutions_Payso_PaysoController extends Mage_Core_Controller_Front_Action {

	public function redirectAction() {
        $session = Mage::getSingleton('checkout/session');
		$session->setPaysoQuoteId($session->getQuoteId());
        $this->getResponse()->setBody($this->getLayout()->createBlock('payso/redirect')->toHtml());
        $session->unsQuoteId();

	}
	
	public function cancelAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getPaysoQuoteId(true));
        
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId()) {
                $order->cancel()->save();
            }
        }
        $this->_redirect('checkout/cart');
     }

    public function successAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getPaysoQuoteId(true));
        
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
		
        $order = Mage::getModel('sales/order');
        $order->load(Mage::getSingleton('checkout/session')->getLastOrderId());
    
	    $order->save();
        
        if($order->getId()){
            $order->sendNewOrderEmail();
        }

        Mage::getSingleton('checkout/session')->unsQuoteId();
		
    	
        $this->_redirect('checkout/onepage/success');
    }
    
    public function failureAction()
    {
    	$session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($session->getPaysoQuoteId(true));
        
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId()) {
                $order->cancel()->save();
            }
        }
        $this->_redirect('checkout/onepage/failure');
    }
}