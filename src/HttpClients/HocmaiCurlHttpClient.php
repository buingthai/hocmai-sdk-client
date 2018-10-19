<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\HttpClients;
use Hocmai\Exceptions\HocmaiOtherException;
use Hocmai\Http\RawResponse;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 11:09 AM
 * @version 1.0.0
 *
 */
class HocmaiCurlHttpClient implements HocmaiHttpClientInterface
{
    protected $curlErrorMessage = '';

    protected $curlErrorCode = 0;

    protected $rawResponse;

    protected $hocmaiCurl;

    public function __construct(HocmaiCurl $hocmaiCurl = null)
    {
        $this->hocmaiCurl = $hocmaiCurl ?: new HocmaiCurl();
    }

    public function send($url, $method, $body, array $headers, $timeout)
    {
        $this->openConnection($url, $method, $body, $headers, $timeout);
        $this->sendRequest();

        if ($this->curlErrorCode = $this->hocmaiCurl->errno()) {
            throw new HocmaiOtherException($this->hocmaiCurl->error(), $this->curlErrorCode);
        }
        list($rawHeader, $rawBody) = $this->extractResponseHeadersAndBody();

        $this->closeConnection();

        return new RawResponse($rawHeader, $rawBody);
    }

    public function openConnection($url, $method, $body, array $headers, $timeout)
    {
        $options = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->compileRequestHeaders($headers),
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            /*CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO => __DIR__ . '/certs/DigiCertHighAssuranceEVRootCA.pem',*/

        ];

        if ($method !== 'GET') {
            $options[CURLOPT_POSTFIELDS] = $body;
        }

        $this->hocmaiCurl->init();
        $this->hocmaiCurl->settOptArray($options);
    }

    public function closeConnection()
    {
        $this->hocmaiCurl->close();
    }

    public function compileRequestHeaders(array $headers)
    {
        $return = [];

        foreach ($headers as $key => $value) {
            $return[] = $key . ':' . $value;
        }

        return $return;
    }

    public function sendRequest()
    {
        $this->rawResponse = $this->hocmaiCurl->exec();
    }


    public function extractResponseHeadersAndBody()
    {
        $parts = explode("\r\n\r\n", $this->rawResponse);
        $rawBody = array_pop($parts);
        $rawHeaders = implode("\r\n\r\n", $parts);

        return [trim($rawHeaders), trim($rawBody)];
    }
}

