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
    private $userAccessToken = '';
    private $userAccessTokenExpires = '';

    public $authorizationUrl = '';
    public $hasUserAccessToken = false;

    function __construct($params)
    {
        // save instagram code
        $this->getCode = $params['get_code'];

        // get an access token
        $this->setUserInstagramAccessToken($params);

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

    private function setUserInstagramAccessToken($params)
    {
        if ($params['get_code']) { // try and get an access token
            $userAccessTokenResponse = $this->getUserAccessToken();

            $this->userAccessToken = $userAccessTokenResponse['access_token'];
            $this->hasUserAccessToken = true;

            // get time lived of access token
            $longLivedAccessTokenResponse = $this->getTimeLivedUserAccessToken();
            $this->userAccessToken = $longLivedAccessTokenResponse['access_token'];
            $this->userAccessTokenExpires = $longLivedAccessTokenResponse['expires_in'];
        }
    }

    private function getTimeLivedUserAccessToken(){
        $params = array(
            'endpoint_url' => $this->graphBaseUrl . 'access_token',
            'type' => 'GET',
            'url_params' => array(
                'client_secret' => $this->appSecret,
                'grant_type' => 'ig_exchange_token',
            )
        );

        $response = $this->makeApiCall( $params );
        return $response;
    }

    private function getUserAccessToken()
    {
        $params = array(
            'endpoint_url' => $this->apiBaseUrl . 'oauth/access_token',
            'type' => 'POST',
            'url_params' => array(
                'app_id' => $this->appId,
                'app_secret' => $this->appSecret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirectUrl,
                'code' => $this->getCode
            )
        );

        $response = $this->makeApiCall($params);
        return $response;
    }

    private function makeApiCall($params)
    {
        $curl = curl_init();

        $endpoint = $params['endpoint_url'];

        if ('POST' == $params['type']) { // post request
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params['url_params']));
            curl_setopt($curl, CURLOPT_POST, 1);
        }elseif ( 'GET' == $params['type'] && !$params['url_params']['paging'] ) { // get request
            $params['url_params']['access_token'] = $this->_userAccessToken;

            //add params to endpoint
            $endpoint .= '?' . http_build_query( $params['url_params'] );
        }

        // general curl options
        curl_setopt($curl, CURLOPT_URL, $endpoint);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);

        $responseArray = json_decode($response, true);

        // check response data
        if (isset($responseArray['error_type'])) {
            var_dump($responseArray);
            die();
        } else {
            return $responseArray;
        }
    }

    public function getThisUserAccessToken()
    {
        return $this->userAccessToken;
    }

    public function getUserAccessTokenExpires()
    {
        return $this->userAccessTokenExpires;
    }
}
