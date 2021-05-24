<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Vendor
 * @copyright  Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Vendor module observer
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ccc_Vendor_Model_Observer
{
    /**
     * VAT ID validation processed flag code
     */
    const VIV_PROCESSED_FLAG = 'viv_after_address_save_processed';

    /**
     * VAT ID validation currently saved address flag
     */
    const VIV_CURRENTLY_SAVED_ADDRESS = 'currently_saved_address';

    /**
     * Check whether specified billing address is default for its vendor
     *
     * @param Mage_Vendor_Model_Address $address
     * @return bool
     */
    protected function _isDefaultBilling($address)
    {
        return ($address->getId() && $address->getId() == $address->getVendor()->getDefaultBilling())
            || $address->getIsPrimaryBilling() || $address->getIsDefaultBilling();
    }

    /**
     * Check whether specified shipping address is default for its vendor
     *
     * @param Mage_Vendor_Model_Address $address
     * @return bool
     */
    protected function _isDefaultShipping($address)
    {
        return ($address->getId() && $address->getId() == $address->getVendor()->getDefaultShipping())
            || $address->getIsPrimaryShipping() || $address->getIsDefaultShipping();
    }

    /**
     * Check whether specified address should be processed in after_save event handler
     *
     * @param Mage_Vendor_Model_Address $address
     * @return bool
     */
    // protected function _canProcessAddress($address)
    // {
    //     if ($address->getForceProcess()) {
    //         return true;
    //     }

    //     if (Mage::registry(self::VIV_CURRENTLY_SAVED_ADDRESS) != $address->getId()) {
    //         return false;
    //     }

    //     $configAddressType = Mage::helper('vendor/address')->getTaxCalculationAddressType();
    //     if ($configAddressType == Ccc_Vendor_Model_Address_Abstract::TYPE_SHIPPING) {
    //         return $this->_isDefaultShipping($address);
    //     }
    //     return $this->_isDefaultBilling($address);
    // }

    /**
     * Before load layout event handler
     *
     * @param Varien_Event_Observer $observer
     */
    public function beforeLoadLayout($observer)
    {
        $loggedIn = Mage::getSingleton('vendor/session')->isLoggedIn();

        $observer->getEvent()->getLayout()->getUpdate()
            ->addHandle('vendor_logged_' . ($loggedIn ? 'in' : 'out'));
    }

    /**
     * Address before save event handler
     *
     * @param Varien_Event_Observer $observer
     */
    public function beforeAddressSave($observer)
    {
        if (Mage::registry(self::VIV_CURRENTLY_SAVED_ADDRESS)) {
            Mage::unregister(self::VIV_CURRENTLY_SAVED_ADDRESS);
        }

        /** @var $vendorAddress Mage_Vendor_Model_Address */
        $vendorAddress = $observer->getVendorAddress();
        if ($vendorAddress->getId()) {
            Mage::register(self::VIV_CURRENTLY_SAVED_ADDRESS, $vendorAddress->getId());
        } else {
            $configAddressType = Mage::helper('vendor/address')->getTaxCalculationAddressType();

            $forceProcess = ($configAddressType == Mage_Vendor_Model_Address_Abstract::TYPE_SHIPPING)
                ? $vendorAddress->getIsDefaultShipping() : $vendorAddress->getIsDefaultBilling();

            if ($forceProcess) {
                $vendorAddress->setForceProcess(true);
            } else {
                Mage::register(self::VIV_CURRENTLY_SAVED_ADDRESS, 'new_address');
            }
        }
    }

    /**
     * Address after save event handler
     *
     * @param Varien_Event_Observer $observer
     */
    public function afterAddressSave($observer)
    {
        /** @var $vendorAddress Mage_Vendor_Model_Address */
        $vendorAddress = $observer->getVendorAddress();
        $vendor = $vendorAddress->getVendor();

        $store = Mage::app()->getStore()->isAdmin() ? $vendor->getStore() : null;
        if (
            !Mage::helper('vendor/address')->isVatValidationEnabled($store)
            || Mage::registry(self::VIV_PROCESSED_FLAG)
            || !$this->_canProcessAddress($vendorAddress)
        ) {
            return;
        }

        try {
            Mage::register(self::VIV_PROCESSED_FLAG, true);

            /** @var $vendorHelper Mage_Vendor_Helper_Data */
            $vendorHelper = Mage::helper('vendor');

            if (
                $vendorAddress->getVatId() == ''
                || !Mage::helper('core')->isCountryInEU($vendorAddress->getCountry())
            ) {
                $defaultGroupId = $vendorHelper->getDefaultVendorGroupId($vendor->getStore());

                if (!$vendor->getDisableAutoGroupChange() && $vendor->getGroupId() != $defaultGroupId) {
                    $vendor->setGroupId($defaultGroupId);
                    $vendor->save();
                }
            } else {

                $result = $vendorHelper->checkVatNumber(
                    $vendorAddress->getCountryId(),
                    $vendorAddress->getVatId()
                );

                $newGroupId = $vendorHelper->getVendorGroupIdBasedOnVatNumber(
                    $vendorAddress->getCountryId(),
                    $result,
                    $vendor->getStore()
                );

                if (!$vendor->getDisableAutoGroupChange() && $vendor->getGroupId() != $newGroupId) {
                    $vendor->setGroupId($newGroupId);
                    $vendor->save();
                }

                if (!Mage::app()->getStore()->isAdmin()) {
                    $validationMessage = Mage::helper('vendor')->getVatValidationUserMessage(
                        $vendorAddress,
                        $vendor->getDisableAutoGroupChange(),
                        $result
                    );

                    if (!$validationMessage->getIsError()) {
                        Mage::getSingleton('vendor/session')->addSuccess($validationMessage->getMessage());
                    } else {
                        Mage::getSingleton('vendor/session')->addError($validationMessage->getMessage());
                    }
                }
            }
        } catch (Exception $e) {
            Mage::register(self::VIV_PROCESSED_FLAG, false, true);
        }
    }

    /**
     * Revert emulated vendor group_id
     *
     * @param Varien_Event_Observer $observer
     */
    public function quoteSubmitAfter($observer)
    {
        /** @var $vendor Mage_Vendor_Model_Vendor */
        $vendor = $observer->getQuote()->getVendor();

        if (!Mage::helper('vendor/address')->isVatValidationEnabled($vendor->getStore())) {
            return;
        }

        if (!$vendor->getId()) {
            return;
        }

        $vendor->setGroupId(
            $vendor->getOrigData('group_id')
        );
        $vendor->save();
    }

    /**
     * Clear vendor flow password table
     *
     */
    public function deleteVendorFlowPassword()
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('write');
        $condition  = array('requested_date < ?' => Mage::getModel('core/date')->date(null, '-1 day'));
        $connection->delete($connection->getTableName('vendor_flowpassword'), $condition);
    }
}