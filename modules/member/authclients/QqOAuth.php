<?php
namespace app\modules\member\authclients;

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
class QqOAuth extends OAuth2
{
    public $authUrl = 'https://graph.qq.com/oauth2.0/authorize';
    public $tokenUrl = 'https://graph.qq.com/oauth2.0/token';
    public $apiBaseUrl = 'https://graph.qq.com';

    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'get_user_info',
            ]);
        }
    }

    protected function initUserAttributes()
    {
        $openid = $this->api('oauth2.0/me', 'GET');
        $qquser = $this->api("user/get_user_info", 'GET', ['oauth_consumer_key' => $openid['client_id'], 'openid' => $openid['openid']]);
        $qquser['openid'] = $openid['openid'];
        return $qquser;
    }

    protected function defaultName()
    {
        return 'qq';
    }

    protected function defaultTitle()
    {
        return 'Qq';
    }


    /**
     * 该扩展初始的处理方法似乎QQ互联不能用，应此改写了方法
     * @see \yii\authclient\BaseOAuth::processResponse()
     * @param string $rawResponse
     * @param string $contentType
     * @return array|mixed
     * @throws Exception
     */
    protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
    {
        if (empty($rawResponse)) {
            return [];
        }
        switch ($contentType) {
            case self::CONTENT_TYPE_AUTO: {
                $contentType = $this->determineContentTypeByRaw($rawResponse);
                if ($contentType == self::CONTENT_TYPE_AUTO) {
                    //以下代码是特别针对QQ互联登录的，也是与原方法不一样的地方 
                    if (strpos($rawResponse, "callback") !== false) {
                        $lpos = strpos($rawResponse, "(");
                        $rpos = strrpos($rawResponse, ")");
                        $rawResponse = substr($rawResponse, $lpos + 1, $rpos - $lpos - 1);
                        $response = $this->processResponse($rawResponse, self::CONTENT_TYPE_JSON);
                        break;
                    }
                    //代码添加结束
                    throw new Exception('Unable to determine response content type automatically.');
                }
                $response = $this->processResponse($rawResponse, $contentType);
                break;
            }
            case self::CONTENT_TYPE_JSON: {
                $response = Json::decode($rawResponse, true);
                if (isset($response['error'])) {
                    throw new Exception('Response error: ' . $response['error']);
                }
                break;
            }
            case self::CONTENT_TYPE_URLENCODED: {
                $response = [];
                parse_str($rawResponse, $response);
                break;
            }
            case self::CONTENT_TYPE_XML: {
                $response = $this->convertXmlToArray($rawResponse);
                break;
            }
            default: {
                throw new Exception('Unknown response type "' . $contentType . '".');
            }
        }

        return $response;
    }


}