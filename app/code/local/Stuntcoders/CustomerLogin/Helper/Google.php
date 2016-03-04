<?php

require_once(Mage::getBaseDir('lib') . '/OAuth2/Client.php');
require_once(Mage::getBaseDir('lib') . '/OAuth2/GrantType/IGrantType.php');
require_once(Mage::getBaseDir('lib') . '/OAuth2/GrantType/AuthorizationCode.php');

class Stuntcoders_CustomerLogin_Helper_Google extends Mage_Core_Helper_Abstract
{
    const AUTH_URL = 'https://accounts.google.com/o/oauth2/auth';
    const TOKEN_URL = 'https://accounts.google.com/o/oauth2/token';
    const USER_URL = 'https://www.googleapis.com/oauth2/v2/userinfo';

    const SYSTEM_CONFIG_GOOGLE_APPLICATION_ID = 'stuntcoders_customerlogin/google/application_id';
    const SYSTEM_CONFIG_GOOGLE_SHARED_SECRET = 'stuntcoders_customerlogin/google/shared_secret';
    const SYSTEM_CONFIG_GOOGLE_ENABLE = 'stuntcoders_customerlogin/google/enable';

    public function getAuthUrl()
    {
        return $this->_getOAuthClient()->getAuthenticationUrl(
            self::AUTH_URL,
            $this->getCallbackUrl(),
            array('scope' => 'email profile')
        );
    }

    public function getAccessToken($code)
    {
        $client = $this->_getOAuthClient();
        $response = $client->getAccessToken(self::TOKEN_URL, 'authorization_code',  array (
            'code' => $code,
            'redirect_uri' => $this->getCallbackUrl()
        ));

        return $response ['result'];
    }

    public function getUserInfo($accessToken)
    {
        $client = $this->_getOAuthClient();
        $client->setAccessToken($accessToken);

        return $client->fetch(self::USER_URL);
    }

    public function getApplicationId()
    {
        return Mage::getStoreConfig(self::SYSTEM_CONFIG_GOOGLE_APPLICATION_ID);
    }

    public function getSharedSecret()
    {
        return Mage::getStoreConfig(self::SYSTEM_CONFIG_GOOGLE_SHARED_SECRET);
    }

    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::SYSTEM_CONFIG_GOOGLE_ENABLE);
    }

    public function getCallbackUrl()
    {
        return Mage::getUrl('stuntcoders_customerlogin/callback/google');
    }

    protected function _getOAuthClient()
    {
        return new OAuth2\Client($this->getApplicationId(), $this->getSharedSecret());
    }
}