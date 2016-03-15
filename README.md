# Magento Customer Login Module

With the rise of online shopping stores it can easily get challenging for users to remember their login information for different stores.

With Magento Customer Login Module users can now login to the store with their social media account, thus avoiding the need to create another account everytime they want to shop at a new online store.

Here is the overview of functionalities provided by Magento Customer Login Module:

- Easy login with just one click
- Option to choose from different social media platforms (Facebook, Google, LinkedIn)

Magento Customer Login Module uses OAuth2 library.

## Usage
### Enabling Social Media Login

Navigate to System -> Configuration and choose Customer Login in the left menu. Enable social media you want to use and enter application id and shared secret. To obtain application id and shared secret follow the steps described for each social media platform:

- [Facebook](https://developers.facebook.com/docs/facebook-login/web)
- [Google](https://developers.google.com/identity/protocols/OAuth2#basicsteps)
- [LinkedIn](https://developer.linkedin.com/docs/oauth2?u=0)

When setting up your application, you will have to tell the API your callback URL. Here are URIs for each social media:

- ```/stuntcoders_customerlogin/callback/facebook```
- ```/stuntcoders_customerlogin/callback/google```
- ```/stuntcoders_customerlogin/callback/linkedin```

![Admin Panel](https://s3-eu-west-1.amazonaws.com/stcd/stunt_mage_customer_login/system-config.png )

## Implementation

Login buttons can be added to any template. Here is the example of facebook button in login.phtml:

To check if facebook login is enabled, use ```Mage::helper('stuntcoders_customerlogin/facebook')->isEnabled()```.

Example for the front-end output:

```php
<?php if (Mage::helper('stuntcoders_customerlogin/facebook')->isEnabled()): ?>
    <a href="<?php echo Mage::helper('stuntcoders_customerlogin/facebook')->getAuthUrl(); ?>" class="button button-facebook"><?php echo $this->__('Facebook'); ?></a>
<?php endif; ?>
```

## Example Login Form
![Customer Login Form](https://s3-eu-west-1.amazonaws.com/stcd/stunt_mage_customer_login/login-page.png )

Copyright StuntCoders — [Start Your Online Store Now](http://stuntcoders.com/)