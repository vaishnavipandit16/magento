<?php
class Ccc_Vendor_Block_Account_Logout extends Mage_Core_Block_Template{

    public function getNewUrl(){
        $url = $this->helper('vendor')->getLoginUrl();
        return $url;
    }
}
?>