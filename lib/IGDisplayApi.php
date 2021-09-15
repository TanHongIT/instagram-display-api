<?php
require_once('../config/config.php');

class IGDisplayApi
{
    private $appId = INSTAGRAM_APP_ID;
    private $appSecret = INSTAGRAM_APP_SECRET;
    private $redirectUrl = INSTAGRAM_APP_REDIRECT_URI;
    private $getCode = '';
    private $apiBaseUrl = 'https://api.instagram.com/';
    private $graphBaseUrl = 'https://graph.instagram.com/';

    public $authorizationUrl = '';

    function __construct($params)
    {
        // save instagram code
        $this->getCode = $params['get_code'];

        // get authorization url
        $this->setAuthorizationUrl();
    }

    private function setAuthorizationUrl()
    {
        $getVars = array(
            'app_id' => $this->appId,
            'redirect_uri' => $this->redirectUrl,
            'scope' => 'user_profile,user_media',
            'response_type' => 'code'
        );

        // create url
        $this->authorizationUrl = $this->apiBaseUrl . 'oauth/authorize?' . http_build_query($getVars);
    }
}
