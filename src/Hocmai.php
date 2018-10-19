<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai;
use Hocmai\Authentication\AccessToken;
use Hocmai\HttpClients\HttpClientsFactory;
use Hocmai\PseudoRandomString\PseudoRandomStringGeneratorFactory;
use Hocmai\Url\UrlDetectionInterface;

/**
 *
 * @author Thai Bui - Created At: 10/12/2018 - 4:15 PM
 * @version 1.0.0
 *
 */
class Hocmai
{
    const VERSION = '1.0.0';

    const APP_ID_NAME = 'APP-ID-NAME';

    const APP_SECRET_NAME = 'APP-SECRET-NAME';

    protected $app;

    /**
     * @var HocmaiClient
     */
    protected $client;

    protected $oAuth2Client;

    protected $urlDetectionHandler;

    protected $pseudoRandomStringGenerator;

    protected $defaultAccessToken;

    protected $persistentDataHandler;

    /**
     * @var HocmaiResponse
     */
    protected $lastResponse;

    public function __construct(array $config = [])
    {
        $config = array_merge([
            'app_id' => getenv(static::APP_ID_NAME),
            'app_secret' => getenv(static::APP_SECRET_NAME),
            'enable_beta_mode' => false,
            'http_client_handler' => null,
            'persistent_data_handler' => null,
            'pseudo_random_string_generator' => null,
            'url_detection_handler' => null,
        ], $config);

        if (! $config['app_id']) {
            throw new \Exception('required "app_id"');
        }

        if (! $config['app_secret']) {
            throw new \Exception('required "app_secret');
        }

        $this->app = new HocmaiApp($config['app_id'], $config['app_secret']);

        $this->client = new HocmaiClient(HttpClientsFactory::createHttpClient($config['http_client_handler']));

        $this->pseudoRandomStringGenerator = PseudoRandomStringGeneratorFactory::createPseudoRandomStringGenerator(
            $config['pseudo_random_string_generator']
        );

        if (isset($config['default_access_token'])) {
            $this->setDefaultAccessToken($config['default_access_token']);
        }
    }

    /**
     * Sets the default access token to use with requests.
     *
     * @param AccessToken|string $accessToken The access token to save.
     *
     * @throws \InvalidArgumentException
     */
    public function setDefaultAccessToken($accessToken)
    {
        if (is_string($accessToken)) {
            $this->defaultAccessToken = new AccessToken($accessToken);

            return;
        }

        if ($accessToken instanceof AccessToken) {
            $this->defaultAccessToken = $accessToken;
            return;
        }

        throw new \InvalidArgumentException('The default access token must
        be of type "string" or Facebook\AccessToken');
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getOAuth2Client()
    {

    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getUrlDetectionHandler()
    {
        return $this->urlDetectionHandler;
    }

    public function setUrlDetectionHandler(UrlDetectionInterface $urlDetectionHandler)
    {
        $this->urlDetectionHandler = $urlDetectionHandler;

    }

    public function getDefaultAccessToken()
    {
        return $this->defaultAccessToken;
    }

    public function get($endpoint, $accessToken = null, $eTag = null)
    {
        return $this->sendRequest('GET', $endpoint, $params = [], $accessToken, $eTag);
        
    }

    public function post($endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequest('POST', $endpoint, $params, $accessToken, $eTag);
        
    }

    public function delete($endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        return $this->sendRequest('DELETE', $endpoint, $params, $accessToken, $eTag);

    }

    public function sendRequest($method, $endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        $accessToken = $accessToken ? $accessToken : $this->defaultAccessToken;
        $request = $this->request($method, $endpoint, $params, $accessToken, $eTag);
        return $this->lastResponse = $this->client->sendRequest($request);
    }

    public function request($method, $endpoint, array $params = [], $accessToken = null, $eTag = null)
    {
        $accessToken = $accessToken ?: $this->defaultAccessToken;

        return new HocmaiRequest($this->app, $accessToken, $method, $endpoint, $params, $eTag);
    }
    
}

