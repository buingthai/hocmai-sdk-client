<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai;
use Hocmai\Authentication\AccessToken;
use Hocmai\Exceptions\HocmaiSDKException;
use Hocmai\Http\RequestBodyUrlEncoded;
use Hocmai\Url\HocmaiUrlManipulator;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 2:08 PM
 * @version 1.0.0
 *
 */
class HocmaiRequest
{
    protected $app;

    protected $accessToken;

    protected $method;

    protected $endpoint;

    protected $headers = [];

    protected $params = [];

    protected $eTag;



    public function __construct(HocmaiApp $app = null, $accessToken = null,
                                $method = null, $endpoint = null, array $params = [], $eTag = null)
    {
        $this->setApp($app);
        $this->setAccessToken($accessToken);
        $this->setMethod($method);
        $this->setEndpoint($endpoint);
        $this->setParams($params);
        $this->setETag($eTag);

    }

    public function setApp(HocmaiApp $app)
    {
        $this->app = $app;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        if ($accessToken instanceof AccessToken) {
            $this->accessToken = $accessToken->getValue();
        }

        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getAccessTokenEntity()
    {
        return $this->accessToken ? new AccessToken($this->accessToken) : null;
    }


    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    public function setEndpoint($endpoint)
    {
        // Harvest the access token from the endpoint to keep things in sync
        $params = HocmaiUrlManipulator::getParamsAsArray($endpoint);
        if (isset($params['access_token'])) {
            $this->setAccessTokenFromParams($params['access_token']);
        }

        // Clean the token & app secret proof from the endpoint.
        $filterParams = ['access_token', 'appsecret_proof'];
        $this->endpoint = HocmaiUrlManipulator::removeParamsFromUrl($endpoint, $filterParams);

        return $this;
    }

    public function setParams(array $params = [])
    {
        if (isset($params['access_token'])) {
            $this->setAccessTokenFromParams($params['access_token']);
        }

        // Don't let these buggers slip in.
        unset($params['access_token'], $params['appsecret_proof']);

        // @TODO Refactor code above with this
        //$params = $this->sanitizeAuthenticationParams($params);
//        $params = $this->sanitizeFileParams($params);
        $this->dangerouslySetParams($params);

        return $this;
    }

    public function setAccessTokenFromParams($accessToken)
    {
        $existingAccessToken = $this->getAccessToken();
        if (!$existingAccessToken) {
            $this->setAccessToken($accessToken);
        } elseif ($accessToken !== $existingAccessToken) {
            throw new \Exception('Access token mismatch. The access token provided in the
            FacebookRequest and the one provided in the URL or POST params do not match.');
        }

        return $this;
    }

    public function dangerouslySetParams(array $params = [])
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function setETag($eTag)
    {
        $this->eTag = $eTag;
    }

    public function getUrlEncodedBody()
    {
        $params = $this->getPostParams();

        return new RequestBodyUrlEncoded($params);
    }

    public function getPostParams()
    {
        if ($this->getMethod() === 'POST') {
            return $this->getParams();
        }

        return [];
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParams()
    {
        $params = $this->params;

        $accessToken = $this->getAccessToken();
        if ($accessToken) {
            $params['access_token'] = $accessToken;
            $params['appsecret_proof'] = $this->getAppSecretProof();
        }

        return $params;
    }

    public function validateMethod()
    {
        if (!$this->method) {
            throw new HocmaiSDKException('HTTP method not specified.');
        }

        if (!in_array($this->method, ['GET', 'POST', 'DELETE'])) {
            throw new HocmaiSDKException('Invalid HTTP method specified.');
        }
    }

    public function getUrl()
    {
        $this->validateMethod();

//        $graphVersion = HocmaiUrlManipulator::forceSlashPrefix($this->graphVersion);
        $endpoint = HocmaiUrlManipulator::forceSlashPrefix($this->getEndpoint());

        $url = $endpoint;

        if ($this->getMethod() !== 'POST') {
            $params = $this->getParams();
            $url = HocmaiUrlManipulator::appendParamsToUrl($url, $params);
        }

        return $url;
    }

    public function getEndpoint()
    {
        // For batch requests, this will be empty
        return $this->endpoint;
    }

    public function getHeaders()
    {
        $headers = static::getDefaultHeaders();

        if ($this->eTag) {
            $headers['If-None-Match'] = $this->eTag;
        }

        return array_merge($this->headers, $headers);
    }

    /**
     * Set the headers for this request.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    public static function getDefaultHeaders()
    {
        return [
            'User-Agent' => 'hocmai-php-' . Hocmai::VERSION,
            'Accept-Encoding' => '*',
        ];
    }

    public function validateAccessToken()
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            throw new HocmaiSDKException('You must provide an access token.');
        }
    }

    public function getAppSecretProof()
    {
        if (!$accessTokenEntity = $this->getAccessTokenEntity()) {
            return null;
        }

        return $accessTokenEntity->getAppSecretProof($this->app->getSecret());
    }

}

