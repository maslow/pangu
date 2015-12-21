<?php
namespace app\modules\member\authclients;

use yii\authclient\OAuth2;

/**
 * Class WbOAuth
 * @package app\modules\member\authclients
 */
class WbOAuth extends OAuth2
{
    public $authUrl = 'https://api.weibo.com/oauth2/authorize';
    public $tokenUrl = 'https://api.weibo.com/oauth2/access_token';
    public $apiBaseUrl = 'https://api.weibo.com/2';

    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'get_token_info',
            ]);
        }
    }

    protected function initUserAttributes()
    {

        $wbuser = $this->api("users/show.json", 'GET');
        return $wbuser;
    }


    protected function apiInternal($accessToken, $url, $method, array $params, array $headers)
    {
        if (!isset($params['format'])) {
            $params['format'] = 'json';
        }

        $params['oauth_token'] = $accessToken->getToken();
        return $this->sendRequest($method, $url, $params, $headers);
    }

    protected function defaultName()
    {
        return 'wb';
    }

    protected function defaultTitle()
    {
        return 'Wb';
    }
}