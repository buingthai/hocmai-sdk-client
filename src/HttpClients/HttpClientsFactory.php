<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\HttpClients;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 11:06 AM
 * @version 1.0.0
 *
 */
class HttpClientsFactory
{
    public function __construct()
    {
    }

    public static function createHttpClient($handler)
    {
        if (! $handler) {
            return self::detectDefaultClient();
        }

        if ($handler instanceof HocmaiHttpClientInterface) {
            return $handler;
        }

        if ($handler === 'curl') {
            if (! extension_loaded('curl')) {
                throw new \Exception('The cURL extension must be loaded in order to use curl');
            }
            return new HocmaiCurlHttpClient();
        }

        throw new \InvalidArgumentException('the http client handler must be set to "curl"');
    }

    public static function detectDefaultClient()
    {
        if (extension_loaded('curl')) {
            return new HocmaiCurlHttpClient();
        }

        if (class_exists('GuzzleHttp\Client')) {

        }

    }
}

