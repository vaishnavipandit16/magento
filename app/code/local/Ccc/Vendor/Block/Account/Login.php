<?php
class Ccc_Vendor_Block_Account_Login extends Mage_Core_Block_Template{
    // private $_username = -1;

    // protected function _prepareLayout()
    // {
    //     $this->getLayout()->getBlock('head')->setTitle(Mage::helper('customer')->__('Customer Login'));
    //     return parent::_prepareLayout();
    // }

    /**
     * Retrieve form posting url
     *
     * @return string
     */
    public function getPostActionUrl()
    {
        return $this->helper('vendor')->getLoginPostUrl();
    }

    public function getCreateAccountUrl()
    {
        $url = $this->getData('create_account_url');
        if (is_null($url)) {
            $url = $this->helper('vendor')->getRegisterUrl();
        }
        return $url;
    }

}
?>