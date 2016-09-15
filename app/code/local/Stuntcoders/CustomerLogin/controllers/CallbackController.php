<?php

class Stuntcoders_CustomerLogin_CallbackController extends Mage_Core_Controller_Front_Action
{
    public function googleAction()
    {
        $googleOAuthHelper = Mage::helper('stuntcoders_customerlogin/google');
        $code = $this->getRequest()->getParam('code');

        if (!$code) {
            Mage::getSingleton('core/session')
                ->addError($this->__('Login with Google failed. User denied access.'));
            return $this->_redirect('customer/account/login');
        }

        $accessToken = $googleOAuthHelper->getAccessToken($code);
        $userInfo = $googleOAuthHelper->getUserInfo($accessToken['access_token']);

        $email = $userInfo['result']['email'];
        $firstName = $userInfo['result']['given_name'];
        $lastName = $userInfo['result']['family_name'];

        if (empty($email) || empty($firstName) || empty($lastName)) {
            Mage::getSingleton('core/session')
                ->addError($this->__('Login with Google failed. Missing some user information.'));
            return $this->_redirect('customer/account/login');
        }

        Mage::helper('stuntcoders_customerlogin')->authenticateCustomer(
            $email,
            $firstName,
            $lastName
        );

        return $this->_redirect('/');
    }

    public function facebookAction()
    {
        $facebookOAuthHelper = Mage::helper('stuntcoders_customerlogin/facebook');
        $code = $this->getRequest()->getParam('code');

        if (!$code) {
            Mage::getSingleton('core/session')
                ->addError($this->__('Login with Facebook failed. User denied access.'));
            return $this->_redirect('/customer/account/login');
        }

        $accessToken = $facebookOAuthHelper->getAccessToken($code);
        $userInfo = $facebookOAuthHelper->getUserInfo($accessToken['access_token']);

        $email = $userInfo['result']['email'];
        $firstName = $userInfo['result']['first_name'];
        $lastName = $userInfo['result']['last_name'];

        if (empty($email) || empty($firstName) || empty($lastName)) {
            Mage::getSingleton('core/session')
                ->addError($this->__('Login with Facebook failed. Missing some user information.'));
            return $this->_redirect('customer/account/login');
        }

        Mage::helper('stuntcoders_customerlogin')->authenticateCustomer(
            $email,
            $firstName,
            $lastName
        );

        return $this->_redirect('/');
    }

    public function linkedinAction()
    {
        $linkedinOAuthHelper = Mage::helper('stuntcoders_customerlogin/linkedin');
        $code = $this->getRequest()->getParam('code');

        if (!$code) {
            Mage::getSingleton('core/session')
                ->addError($this->__('Login with LinkedIn failed. User denied access.'));
            return $this->_redirect('/customer/account/login');
        }

        $accessToken = $linkedinOAuthHelper->getAccessToken($code);
        $userInfo = $linkedinOAuthHelper->getUserInfo($accessToken['access_token']);

        $email = $userInfo['result']['emailAddress'];
        $firstName = $userInfo['result']['firstName'];
        $lastName = $userInfo['result']['lastName'];

        if (empty($email) || empty($firstName) || empty($lastName)) {
            Mage::getSingleton('core/session')
                ->addError($this->__('Login with LinkedIn failed. Missing some user information'));
            return $this->_redirect('customer/account/login');
        }

        Mage::helper('stuntcoders_customerlogin')->authenticateCustomer(
            $email,
            $firstName,
            $lastName
        );

        return $this->_redirect('/');
    }
}
