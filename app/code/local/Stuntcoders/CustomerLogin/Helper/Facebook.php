<?php

require_once(Mage::getBaseDir('lib') . '/OAuth2/Client.php');
require_once(Mage::getBaseDir('lib') . '/OAuth2/GrantType/IGrantType.php');
require_once(Mage::getBaseDir('lib') . '/OAuth2/GrantType/AuthorizationCode.php');

class Stuntcoders_CustomerLogin_Helper_Facebook extends Mage_Core_Helper_Abstract
{
    const AUTH_URL = 'https://www.facebook.com/dialog/oauth';
    const TOKEN_URL = 'https://graph.facebook.com/v2.4/oauth/access_token';
    const USER_URL = 'https://graph.facebook.com/me';

    const SYSTEM_CONFIG_FACEBOOK_APPLICATION_ID = 'stuntcoders_customerlogin/facebook/application_id';
    const SYSTEM_CONFIG_FACEBOOK_SHARED_SECRET = 'stuntcoders_customerlogin/facebook/shared_secret';
    const SYSTEM_CONFIG_FACEBOOK_ENABLE = 'stuntcoders_customerlogin/facebook/enable';

    public function getAuthUrl()
    {
        return $this->_getOAuthClient()->getAuthenticationUrl(
            self::AUTH_URL,
            $this->getCallbackUrl(),
            array('scope' => 'public_profile email')
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

        return $client->fetch(self::USER_URL, array('fields' => 'id,first_name,last_name,email'));
    }

    public function getApplicationId()
    {
        return Mage::getStoreConfig(self::SYSTEM_CONFIG_FACEBOOK_APPLICATION_ID);
    }

    public function getSharedSecret()
    {
        return Mage::getStoreConfig(self::SYSTEM_CONFIG_FACEBOOK_SHARED_SECRET);
    }

    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::SYSTEM_CONFIG_FACEBOOK_ENABLE);
    }

    public function getCallbackUrl()
    {
        return Mage::getUrl('stuntcoders_customerlogin/callback/facebook');
    }

    protected function _getOAuthClient()
    {
        return new OAuth2\Client($this->getApplicationId(), $this->getSharedSecret());
    }
}