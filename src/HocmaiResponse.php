<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai;
use Hocmai\Exceptions\HocmaiResponseException;
use Hocmai\Items\ItemFactory;
use Hocmai\Items\Reports\Table;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 2:08 PM
 * @version 1.0.0
 *
 */
class HocmaiResponse
{
    protected $httpStatusCode;

    protected $headers;

    protected $body;

    protected $decodedBody = [];

    protected $request;

    protected $throwException;

    public function __construct(HocmaiRequest $request, $body = null, $httpStatusCode = null, array $headers = [])
    {
        $this->request = $request;
        $this->body = $body;
        $this->httpStatusCode = $httpStatusCode;
        $this->headers = $headers;

        $this->decodeBody();
    }

    public function decodeBody()
    {
        $this->decodedBody = json_decode($this->body, true);

        if ($this->decodedBody === null) {
            $this->decodedBody = [];
            parse_str($this->body, $this->decodedBody);
        } elseif (is_bool($this->decodedBody)) {
            // Backwards compatibility for Graph < 2.1.
            // Mimics 2.1 responses.
            // @TODO Remove this after Graph 2.0 is no longer supported
            $this->decodedBody = ['success' => $this->decodedBody];
        } elseif (is_numeric($this->decodedBody)) {
            $this->decodedBody = ['id' => $this->decodedBody];
        }

        if (!is_array($this->decodedBody)) {
            $this->decodedBody = [];
        }

        if ($this->isError()) {
            $this->makeException();
        }
    }

    public function isError()
    {
        return isset($this->decodedBody['error']);
    }

    public function makeException()
    {
        $this->throwException = HocmaiResponseException::create($this);
    }

    public function getThrownException()
    {
        return $this->throwException;
    }

    public function getDecodedBody()
    {
        return $this->decodedBody;
    }

    public function getProductLine()
    {
        $factory = new ItemFactory($this);
        return $factory->makeProductLine();
    }

    /**
     * @return Table
     *
     */
    public function getReport()
    {
        $factory = new ItemFactory($this);
        return $factory->makeReport();
    }
}

