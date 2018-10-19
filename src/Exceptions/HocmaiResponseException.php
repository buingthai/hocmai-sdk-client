<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\Exceptions;
use Hocmai\HocmaiResponse;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 3:15 PM
 * @version 1.0.0
 *
 */
class HocmaiResponseException extends HocmaiSDKException
{
    protected $response;

    protected $responseData;

    public function __construct(HocmaiResponse $response, HocmaiSDKException $preException = null)
    {
        $this->response = $response;
        $this->responseData = $response->getDecodedBody();

        $errorMessage = $this->get('message', 'Unknown error');

        $errorCode = $this->get('code', -1);

        parent::__construct($errorMessage, $errorCode, $preException);
    }

    public static function create(HocmaiResponse $response)
    {
        $data = $response->getDecodedBody();

        if (!isset($data['error']['code']) && isset($data['code'])) {
            $data = ['error' => $data];
        }

        $code = isset($data['error']['code']) ? $data['error']['code'] : null;
        $message = isset($data['error']['message']) ? $data['error']['message'] : 'Unknown error from HocmaiAPI.';

        /*if (isset($data['error']['error_subcode'])) {
            switch ($data['error']['error_subcode']) {
                // Other authentication issues
                case 458:
                case 459:
                case 460:
                case 463:
                case 464:
                case 467:
                    return new static($response, new FacebookAuthenticationException($message, $code));
                // Video upload resumable error
                case 1363030:
                case 1363019:
                case 1363037:
                case 1363033:
                case 1363021:
                case 1363041:
                    return new static($response, new FacebookResumableUploadException($message, $code));
            }
        }

        switch ($code) {
            // Login status or token expired, revoked, or invalid
            case 100:
            case 102:
            case 190:
                return new static($response, new FacebookAuthenticationException($message, $code));

            // Server issue, possible downtime
            case 1:
            case 2:
                return new static($response, new FacebookServerException($message, $code));

            // API Throttling
            case 4:
            case 17:
            case 32:
            case 341:
            case 613:
                return new static($response, new FacebookThrottleException($message, $code));

            // Duplicate Post
            case 506:
                return new static($response, new FacebookClientException($message, $code));
        }

        // Missing Permissions
        if ($code == 10 || ($code >= 200 && $code <= 299)) {
            return new static($response, new FacebookAuthorizationException($message, $code));
        }

        // OAuth authentication error
        if (isset($data['error']['type']) && $data['error']['type'] === 'OAuthException') {
            return new static($response, new FacebookAuthenticationException($message, $code));
        }*/

        // All others
        return new static($response, new HocmaiOtherException($message, $code));
    }

    private function get($key, $default = null)
    {
        if (isset($this->responseData['error'][$key])) {
            return $this->responseData['error'][$key];
        }
        return $default;
    }
}

