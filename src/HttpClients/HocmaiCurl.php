<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\HttpClients;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 3:43 PM
 * @version 1.0.0
 *
 */
class HocmaiCurl
{
    protected $curl;


    public function init()
    {
        $this->curl = curl_init();
    }

    public function setOpt($key, $value)
    {
        curl_setopt($this->curl, $key, $value);
    }

    public function settOptArray(array $options)
    {
        curl_setopt_array($this->curl, $options);
    }

    public function exec()
    {
        return curl_exec($this->curl);
    }

    public function errno()
    {
        return curl_errno($this->curl);

    }

    public function error()
    {
        return curl_error($this->curl);
    }

    public function getinfo()
    {
        return curl_getinfo($this->curl);
    }

    public function version()
    {
        return curl_version();
    }

    public function close()
    {
        curl_close($this->curl);
    }


}

