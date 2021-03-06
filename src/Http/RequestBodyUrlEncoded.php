<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\Http;

/**
 *
 * @author Thai Bui - Created At: 10/15/2018 - 11:07 AM
 * @version 1.0.0
 *
 */
class RequestBodyUrlEncoded implements RequestBodyInterface
{
    protected $params = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function getBody()
    {
        return http_build_query($this->params, null, '&');

    }
}

