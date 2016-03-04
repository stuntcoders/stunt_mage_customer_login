<?php

class Stuntcoders_CustomerLogin_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function authenticateCustomer($email, $firstName, $lastName)
    {
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getWebsite()->getId())
            ->loadByEmail($email);

        if (!$customer->getId()) {
            $customer->setEmail($email)
                ->setFirstname($firstName)
                ->setLastname($lastName)
                ->setPassword($customer->generatePassword())
                ->save();
        }

        $session = $this->_getSession();
        $session->setCustomerAsLoggedIn($customer);
        $session->renewSession();
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
}