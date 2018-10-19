<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\HttpClients;
use Hocmai\Http\RawResponse;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 11:03 AM
 * @version 1.0.0
 *
 */
interface HocmaiHttpClientInterface
{
    /**
     * @param $url
     * @param $method
     * @param $body
     * @param array $headers
     * @param $timeout
     * @return RawResponse
     *
     */
    public function send($url, $method, $body, array $headers, $timeout);
}

