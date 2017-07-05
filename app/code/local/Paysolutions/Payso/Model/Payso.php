<?php
class Paysolutions_Payso_Model_Payso extends Mage_Payment_Model_Method_Abstract {
	
	const CGI_URL = 'https://www.thaiepay.com/epaylink/payment.aspx';
   // const CGI_URL_TEST = 'https://demo.paysbuy.com/paynow.aspx';
	
	protected $_code = 'payso';
	protected $_formBlockType = 'payso/form';	
	protected $_allowCurrencyCode = array('THB','AUD','GBP','EUR','HKD','JPY','NZD','SGD','CHF','USD');
	
    public function getUrl()
    {
    	$url = $this->getConfigData('cgi_url');
    	
    	if($url == '0')
    	{
    		$url = self::CGI_URL;
    	}
		else if($url == '1')
		{
			$url = self::CGI_URL_TEST;
		}
    	
    	return $url;
    }//end function getUrl
	
	public function getSession()
    {
        return Mage::getSingleton('payso/payso_session');
    }//end function getSession
	
	public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }//end function getCheckout
	
	public function getQuote()
    {
        return $this->getCheckout()->getQuote();
    }//end function getQuote
	
	public function getCheckoutFormFields()
	{
		$order = Mage::getSingleton('sales/order');
		$order->loadByIncrementId($this->getCheckout()->getLastRealOrderId());
		
		$currency_code = $order->getBaseCurrencyCode();
		
		$grandTotalAmount = sprintf('%.2f', $order->getGrandTotal());
		
		switch($currency_code){
		case 'THB':
			$cur = 764;
			break;
		case 'AUD':
			$cur = 036;
			break;		
		case 'GBP':
			$cur = 826;
			break;	
		case 'EUR':
			$cur = 978;
			break;		
		case 'HKD':
			$cur = 344;
			break;		
		case 'JPY':
			$cur = 392;
			break;		
		case 'NZD':
			$cur = 554;
			break;
		case 'SGD':
			$cur = 702;
			break;	
		case 'CHF':
			$cur = 756;
			break;	
		case 'USD':
			$cur = 840;
			break;	
		default:
			$cur = 764;
		}	 
		
		$orderId = $order->getIncrementId();
		$item_names = array();
		$items = $order->getItemsCollection();
		foreach ($items as $item){
			$item_name = $item->getName();
			$Email = $item->getEmail();
 		  	$qty = number_format($item->getQtyOrdered(), 0, '.', ' ');
			$item_names[] = $item_name . ' x ' . $qty;
		}	
		$paysolutions_args['item_name'] 	= sprintf( __('Order %s '), $orderId ) . " - " . implode(', ', $item_names);
		$orderReferenceValue = $this->getCheckout()->getLastRealOrderId();
		$merchantId = $this->getConfigData('merchant_id');
		$postbackground = $this->getConfigData('postbackground');
		$payso = 'payso';
		
		$fields = array(
			'payso'			            => $payso,
			'merchantid'				=> $merchantId,
			'amt'						=> $grandTotalAmount, 
			'total'						=> $grandTotalAmount, 
			'customeremail'				=> $paysolutions_args['Email'],
			'currencyCode'				=> $cur,
			'productdetail'    		    => $paysolutions_args['item_name'],
			'refno'						=> $orderReferenceValue,
			'inv'						=> $orderReferenceValue,
			'opt_fix_redirect'			=> '1',
			'postURL'					=> Mage::getUrl('payso/payso/success'),
			'reqURL'					=> $postbackground,
		);

		$filtered_fields = array();
        foreach ($fields as $k=>$v) {
            $value = str_replace("&","and",$v);
            $filtered_fields[$k] =  $value;
        }
        
        return $filtered_fields;
	}//end function getCheckoutFormFields
	
	public function createFormBlock($name)
    {
        $block = $this->getLayout()->createBlock('payso/form', $name)
            ->setMethod('payso')
            ->setPayment($this->getPayment())
            ->setTemplate('payso/form.phtml');

        return $block;
    }//end function createFormBlock
	
	public function validate()
    {
        parent::validate();
        $currency_code = $this->getQuote()->getBaseCurrencyCode();
        if (!in_array($currency_code,$this->_allowCurrencyCode)) {
            Mage::throwException(Mage::helper('payso')->__('Selected currency code ('.$currency_code.') is not compatabile with Paysolutions'));
        }
        return $this;
    }//end function validate
	
	public function onOrderValidate(Mage_Sales_Model_Order_Payment $payment)
    {
       return $this;
    }//end function onOrderValidate

    public function onInvoiceCreate(Mage_Sales_Model_Invoice_Payment $payment)
    {
		
	}//end function onInvoiceCreate
	
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('payso/payso/redirect');
	}//end function getOrderPlaceRedirectUrl
}
?>