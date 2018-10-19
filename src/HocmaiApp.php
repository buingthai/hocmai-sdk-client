<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai;
use Hocmai\Authentication\AccessToken;
use Hocmai\Exceptions\HocmaiSDKException;

/**
 *
 * @author Thai Bui - Created At: 10/13/2018 - 10:57 AM
 * @version 1.0.0
 *
 */
class HocmaiApp implements \Serializable
{
    protected $id;

    protected $secret;

    /**
     * @param string $id
     * @param string $secret
     *
     * @throws HocmaiSDKException
     */
    public function __construct($id, $secret)
    {
        if (!is_string($id)
            // Keeping this for BC. Integers greater than PHP_INT_MAX will make is_int() return false
            && !is_int($id)) {
            throw new HocmaiSDKException('The "app_id" must be formatted as a string
            since many app ID\'s are greater than PHP_INT_MAX on some systems.');
        }
        // We cast as a string in case a valid int was set on a 64-bit system and this is unserialised on a 32-bit system
        $this->id = (string) $id;
        $this->secret = $secret;
    }

    /**
     * Returns the app ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the app secret.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Returns an app access token.
     *
     * @return AccessToken
     */
    public function getAccessToken()
    {
        return new AccessToken($this->id . '|' . $this->secret);
    }

    /**
     * Serializes the FacebookApp entity as a string.
     *
     * @return string
     */
    public function serialize()
    {
        return implode('|', [$this->id, $this->secret]);
    }

    /**
     * Unserializes a string as a FacebookApp entity.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($id, $secret) = explode('|', $serialized);

        $this->__construct($id, $secret);
    }
}

