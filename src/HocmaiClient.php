<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai;
use Hocmai\HttpClients\HocmaiCurlHttpClient;
use Hocmai\HttpClients\HocmaiHttpClientInterface;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 11:02 AM
 * @version 1.0.0
 *
 */
class HocmaiClient
{
    const HOCMAI_API_URL = 'http://localhost/api-gateway/';
    
    const DEFAULT_REQUEST_TIMEOUT = 60;
    
    public static $requestCount = 0;

    /**
     * @var HocmaiHttpClientInterface
     */
    protected $httpClientHandler;

    public function __construct(HocmaiHttpClientInterface $httpClientHandler = null)
    {
        $this->httpClientHandler = $httpClientHandler ?: $this->detectHttpClientHandler();
    }

    public function setHttpClientHandler(HocmaiHttpClientInterface $httpClientHandler)
    {
        $this->httpClientHandler = $httpClientHandler;
    }

    public function detectHttpClientHandler()
    {
        return extension_loaded('curl') ? new HocmaiCurlHttpClient() : null;
    }

    public function getHttpClientHandler()
    {
        return $this->httpClientHandler;
    }

    public function sendRequest(HocmaiRequest $request)
    {
        if (get_class($request) === 'Hocmai\HocmaiRequest') {
            $request->validateAccessToken();
        }

        list($url, $method, $headers, $body) = $this->prepareRequestMessage($request);

        // Since file uploads can take a while, we need to give more time for uploads
        $timeOut = static::DEFAULT_REQUEST_TIMEOUT;
        /*if ($request->containsFileUploads()) {
            $timeOut = static::DEFAULT_FILE_UPLOAD_REQUEST_TIMEOUT;
        } elseif ($request->containsVideoUploads()) {
            $timeOut = static::DEFAULT_VIDEO_UPLOAD_REQUEST_TIMEOUT;
        }*/

        // Should throw `FacebookSDKException` exception on HTTP client error.
        // Don't catch to allow it to bubble up.
        $rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);

        static::$requestCount++;

        $returnResponse = new HocmaiResponse(
            $request,
            $rawResponse->getBody(),
            $rawResponse->getHttpResponseCode(),
            $rawResponse->getHeaders()
        );

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }

    public function prepareRequestMessage(HocmaiRequest $request)
    {
        /*$postToVideoUrl = $request->containsVideoUploads();
        $url = $this->getBaseGraphUrl($postToVideoUrl) . $request->getUrl();

        // If we're sending files they should be sent as multipart/form-data
        if ($request->containsFileUploads()) {
            $requestBody = $request->getMultipartBody();
            $request->setHeaders([
                'Content-Type' => 'multipart/form-data; boundary=' . $requestBody->getBoundary(),
            ]);
        } else {
            $requestBody = $request->getUrlEncodedBody();
            $request->setHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]);
        }*/

        $url = $this->getBaseUrl() . $request->getUrl();

        $requestBody = $request->getUrlEncodedBody();
        $request->setHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ]);

        return [
            $url,
            $request->getMethod(),
            $request->getHeaders(),
            $requestBody->getBody(),
        ];
    }

    public function getBaseUrl()
    {
        return static::HOCMAI_API_URL;

    }
}

