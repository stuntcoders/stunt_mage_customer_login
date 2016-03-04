<?php

require_once(Mage::getBaseDir('lib') . '/OAuth2/Client.php');
require_once(Mage::getBaseDir('lib') . '/OAuth2/GrantType/IGrantType.php');
require_once(Mage::getBaseDir('lib') . '/OAuth2/GrantType/AuthorizationCode.php');

class Stuntcoders_CustomerLogin_Helper_Linkedin extends Mage_Core_Helper_Abstract
{
    const AUTH_URL = 'https://www.linkedin.com/uas/oauth2/authorization';
    const TOKEN_URL = 'https://www.linkedin.com/uas/oauth2/accessToken';
    const USER_URL = 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address)';

    const SYSTEM_CONFIG_LINKEDIN_APPLICATION_ID = 'stuntcoders_customerlogin/linkedin/application_id';
    const SYSTEM_CONFIG_LINKEDIN_SHARED_SECRET = 'stuntcoders_customerlogin/linkedin/shared_secret';
    const SYSTEM_CONFIG_LINKEDIN_ENABLE = 'stuntcoders_customerlogin/linkedin/enable';

    public function getAuthUrl()
    {
        return $this->_getOAuthClient()->getAuthenticationUrl(
            self::AUTH_URL,
            $this->getCallbackUrl(),
            array(
                'scope' => 'r_basicprofile r_emailaddress',
                'state' => Mage::helper('core')->getRandomString(16)
            )
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
        $client->setAccessTokenParamName('oauth2_access_token');
        $client->setAccessToken($accessToken);

        return $client->fetch(self::USER_URL, array('format' => 'json'));
    }

    public function getApplicationId()
    {
        return Mage::getStoreConfig(self::SYSTEM_CONFIG_LINKEDIN_APPLICATION_ID);
    }

    public function getSharedSecret()
    {
        return Mage::getStoreConfig(self::SYSTEM_CONFIG_LINKEDIN_SHARED_SECRET);
    }

    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::SYSTEM_CONFIG_LINKEDIN_ENABLE);
    }

    public function getCallbackUrl()
    {
        return Mage::getUrl('stuntcoders_customerlogin/callback/linkedin');
    }

    protected function _getOAuthClient()
    {
        return new OAuth2\Client($this->getApplicationId(), $this->getSharedSecret());
    }
}