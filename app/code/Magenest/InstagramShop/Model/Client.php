<?php

namespace Magenest\InstagramShop\Model;

use Magento\Backend\App\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\UrlInterface;

/**
 * Class Client
 * @package Magenest\InstagramShop\Model
 */
class Client
{
    protected $redirect_uri_path = 'admin/instagram/instagram/connect/';
    protected $subscribe_redirect = 'instagram/instagram/subscribe';

    protected $path_client_id = 'magenest_instagram_shop/instagram/client_id';
    protected $path_client_secret = 'magenest_instagram_shop/instagram/client_secret';
    protected $path_access_token = 'magenest_instagram_shop/instagram/access_token';

    protected $path_tags = 'magenest_instagram_shop/instagram_tags/tags';

    protected $oauth2_service_uri = 'https://api.instagram.com/v1';
    protected $oauth2_auth_uri = 'https://api.instagram.com/oauth/authorize';
    protected $oauth2_token_uri = 'https://api.instagram.com/oauth/access_token';

    protected $scope = ['basic', 'public_content'];

    /**
     * @var ConfigInterface
     */
    protected $_config;

    /**
     * @var ZendClientFactory
     */
    protected $_httpClientFactory;

    /**
     * @var UrlInterface
     */
    protected $_url;
    /**
     * @var null|string
     */
    protected $clientId = null;

    /**
     * @var null|string
     */
    protected $clientSecret = null;

    /**
     * @var null|string
     */
    protected $redirectUri = null;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var TaggedPhotoFactory
     */
    protected $_taggedPhotoFactory;

    /**
     * Client constructor.
     * @param ZendClientFactory $httpClientFactory
     * @param ConfigInterface $config
     * @param UrlInterface $url
     */
    public function __construct(
        ZendClientFactory $httpClientFactory,
        ConfigInterface $config,
        UrlInterface $url,
        \Magenest\InstagramShop\Model\TaggedPhotoFactory $taggedPhotoFactory
    )
    {
        $this->_taggedPhotoFactory = $taggedPhotoFactory;
        $this->_httpClientFactory = $httpClientFactory;
        $this->_config = $config;
        $this->_url = $url;
        $this->redirectUri = $this->_url->getBaseUrl() . $this->redirect_uri_path;
        $this->clientId = $this->_getClientId();
        $this->clientSecret = $this->_getClientSecret();
        $this->_config = $config;
    }

    /**
     * url to instagram authorization site
     * @param string $clientId
     * @return string
     */
    public function createAuthUrl($clientId = '')
    {
        $query = [
            'client_id' => $clientId == '' ? $this->getClientId() : $this->clientId,
            'redirect_uri' => $this->getRedirectUri(),
            'scope' => implode(' ', $this->getScope()),
            'response_type' => "code"
        ];
        $url = $this->oauth2_auth_uri . '?' . http_build_query($query);

        return $url;
    }

    /**
     * @param string $endpoint
     * @param string $method
     * @param array $params
     * @return array
     */
    public function api($endpoint, $method = 'GET', $params = [])
    {
        if (empty($this->token)) {
            $this->_getAccessToken();
        }
        $url = $this->oauth2_service_uri . $endpoint;
        $method = strtoupper($method);
        $params = array_merge([
            'access_token' => $this->token
        ], $params);
        $response = $this->_httpRequest($url, $method, $params);
        if (isset($response['meta']['error_type']) && $response['meta']['error_type'] == 'OAuthAccessTokenError') {
            $this->_config->setValue('magenest_instagram_shop/instagram/access_token', 'Expired');
        }

        return $response;
    }

    /**
     * @param null $code
     * @return string
     * @throws LocalizedException
     */
    public function fetchAccessToken($code = null)
    {
        $token_array = [
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getRedirectUri(),
            'code' => $code,
        ];

        if (empty($code)) {
            throw new LocalizedException(
                __('Unable to retrieve access code.')
            );
        }
        $response = $this->_httpRequest(
            $this->oauth2_token_uri,
            'POST',
            $token_array
        );

        return json_encode($response);
    }

    /**
     * subscribe for instagram account's update
     */
    public function subscribe()
    {
        $url = 'https://api.instagram.com/v1/subscriptions/';
        $params = [
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'object' => 'user',
            'aspect' => 'media',
            'verify_token' => '592180',
            'callback_url' => $this->_url->sessionUrlVar(
                $this->_url->getUrl($this->subscribe_redirect)
            )
        ];
        $this->_httpRequest($url, 'POST', $params);
    }

    /**
     * @param $url
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws LocalizedException
     */
    protected function _httpRequest($url, $method = 'GET', $params = array())
    {
        $client = $this->_httpClientFactory->create();
        $client->setUri($url);
        switch ($method) {
            case 'GET':
                $client->setParameterGet($params);
                break;
            case 'DELETE':
                $client->setParameterGet($params);
                break;
            case 'POST':
                $client->setParameterPost($params);
                break;
            default:
                throw new LocalizedException(
                    __('Required HTTP method is not supported.')
                );
        }
        $response = $client->request($method);
        $decodedResponse = json_decode($response->getBody(), true);

        if ($response->isError()) {
            $status = $response->getStatus();
            if (($status == 400 || $status == 401)) {
                if (isset($decodedResponse['meta']['error_message'])) {
                    $message = $decodedResponse['meta']['error_message'];
                } else {
                    $message = __('Unspecified OAuth error occurred.');
                }
                throw new LocalizedException(__($message));
            } else {
                $message = sprintf(
                    __('HTTP error %d occurred while issuing request.'),
                    $status
                );
                throw new LocalizedException(__($message));
            }
        }

        return $decodedResponse;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        if (empty($this->token)) {
            $this->_getAccessToken();
        }

        return $this->token;
    }

    /**
     * @param $token
     */
    public function setAccessToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return null|string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return null|string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @return null|string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        $str = trim($this->_getStoreConfig($this->path_tags, " "));
        if ($str == "")
            return [];
        $tags = explode(",", $str);
        return $tags;
    }

    /**
     * get access token from store configuration
     */
    public function _getAccessToken()
    {
        $this->setAccessToken($this->_getStoreConfig($this->path_access_token));
    }

    /**
     * @return string
     */
    protected function _getClientId()
    {
        return $this->_getStoreConfig($this->path_client_id);
    }

    /**
     * @return string
     */
    protected function _getClientSecret()
    {
        return $this->_getStoreConfig($this->path_client_secret);
    }

    /**
     * @param $xmlPath
     * @return mixed
     */
    protected function _getStoreConfig($xmlPath)
    {
        return $this->_config->getValue($xmlPath);
    }
}
