<?php
namespace app\common\components;

use yii\authclient\OAuth2;
use yii\base\Exception;
use yii\helpers\Json;

/**
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'qq' => [
 *                 'class' => 'common\components\QqOAuth',
 *                 'clientId' => 'qq_client_id',
 *                 'clientSecret' => 'qq_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 *
 * @see http://connect.qq.com/
 *
 * @author easypao <admin@easypao.com>
 * @since 2.0
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