<?php
 
class Ccc_PaymentMethod_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
 
    protected $_code = 'cod';

    // protected $_formBlockType = 'paymentmethod/form_paymentmethod';
    // protected $_infoBlockType = 'paymentmethod/info_paymentmethod';
 
    // protected $_isInitializeNeeded      = true;
    // protected $_canUseInternal          = false;
    // protected $_canUseForMultishipping  = false;
 
/**
* Return Order place redirect url
*
* @return string
*/
    // public function getOrderPlaceRedirectUrl()
    // {
    //     //when you click on place order you will be redirected on this url, if you don't want this action remove this method
    //     return Mage::getUrl('customcard/standard/redirect', array('_secure' => true));
    // }

// public function getInstructions()
//     {
//         return trim($this->getConfigData('instructions'));
//     }

// public function assignData($data)
//     {
//         if (!($data instanceof Varien_Object)) {
//             $data = new Varien_Object($data);
//         }
//         $info = $this->getInfoInstance();
//         $info->setCheckNo($data->getCheckNo())
//         ->setCheckDate($data->getCheckDate());
//         return $this;
//     }
 
 
//     public function validate()
//     {
//         parent::validate();
 
//         $info = $this->getInfoInstance();
 
//         $no = $info->getCheckNo();
//         $date = $info->getCheckDate();
//         if(empty($no) || empty($date)){
//             $errorCode = 'invalid_data';
//             $errorMsg = $this->_getHelper()->__('Check No and Date are required fields');
//         }
 
//         if($errorMsg){
//             Mage::throwException($errorMsg);
//         }
//         return $this;
//     }
 
}

?>