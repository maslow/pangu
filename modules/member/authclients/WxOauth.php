<?php
namespace app\modules\member\authclients;

use Yii;
use yii\authclient\OAuth2;

/**
 * Class WxOAuth
 * @package app\modules\member\authclients
 */
class WxOAuth extends OAuth2
{
    public $authUrl = 'https://open.weixin.qq.com/connect/qrconnect';
    public $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    public $apiBaseUrl = 'https://api.weixin.qq.com/sns';
    public $openid;

    public function buildAuthUrl(array $params = [])
    {
        $defaultParams = [
            'appid' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->getReturnUrl(),
            'xoauth_displayname' => Yii::$app->name,
        ];
        if (!empty($this->scope)) {
            $defaultParams['scope'] = $this->scope;
        }

        return $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
    }

    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'snsapi_login',
            ]);
        }
    }

    protected function initUserAttributes()
    {
        //$openid =  $this->api('oauth2.0/me', 'GET');  已经有openid
        $wxuser = $this->api("userinfo", 'GET');
        return $wxuser;
    }


    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        if (!isset($params['format'])) {
            $params['format'] = 'json';
        }

        $params['oauth_token'] = $accessToken->getToken();
        $params['openid'] = $this->openid;
        return $this->sendRequest($method, $url, $params, $headers);
    }

    public function fetchAccessToken($authCode, array $params = [])
    {
        $defaultParams = [
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
            'code' => $authCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getReturnUrl(),
        ];
        $response = $this->sendRequest('POST', $this->tokenUrl, array_merge($defaultParams, $params));
        $token = $this->createToken(['params' => $response]);
        $this->openid = $response['openid'];
        $this->setAccessToken($token);
        return $token;
    }

    protected function defaultName()
    {
        return 'wx';
    }

    protected function defaultTitle()
    {
        return 'Wx';
    }


}