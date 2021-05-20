<?php
class Ccc_ShippingMethod_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'shippingmethod';

    public function collectRates(
        Mage_Shipping_Model_Rate_Request $request
    )
    {
        $result = Mage::getModel('shipping/rate_result');
        /* @var $result Mage_Shipping_Model_Rate_Result */

        //$result->append($this->_getStandardShippingRate());
        
        $result->append($this->_getExpressShippingRate());

        
        // if ($request->getFreeShipping()) {
        //     /**
        //      *  If the request has the free shipping flag,
        //      *  append a free shipping rate to the result.
        //      */
        //     $freeShippingRate = $this->_getFreeShippingRate();
        //     $result->append($freeShippingRate);
        // }

        return $result;
    }

    // protected function _getStandardShippingRate()
    // {
    //     $rate = Mage::getModel('shipping/rate_result_method');
    //     /* @var $rate Mage_Shipping_Model_Rate_Result_Method */

    //     $rate->setCarrier($this->_code);
    //     /**
    //      * getConfigData(config_key) returns the configuration value for the
    //      * carriers/[carrier_code]/[config_key]
    //      */
    //     $rate->setCarrierTitle($this->getConfigData('title'));

    //     $rate->setMethod('standand');
    //     $rate->setMethodTitle('Standard');

    //     $rate->setPrice(4.99);
    //     $rate->setCost(0);

    //     return $rate;
    // }

    
    protected function _getExpressShippingRate()
    {
        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('express');
        $rate->setMethodTitle('Express (Next day)');
        $rate->setPrice(12.99);
        $rate->setCost(0);
        return $rate;
    }



    // protected function _getFreeShippingRate()
    // {
    //     $rate = Mage::getModel('shipping/rate_result_method');
    //     /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
    //     $rate->setCarrier($this->_code);
    //     $rate->setCarrierTitle($this->getConfigData('title'));
    //     $rate->setMethod('free_shipping');
    //     $rate->setMethodTitle('Free Shipping (3 - 5 days)');
    //     $rate->setPrice(0);
    //     $rate->setCost(0);
    //     return $rate;
    // }

    public function getAllowedMethods()
    {
        return array(
            //'standard' => 'Standard',
            'express' => 'Express',
            //'free_shipping' => 'Free Shipping',
        );
    }

}