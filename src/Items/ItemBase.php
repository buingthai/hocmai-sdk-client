<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\Items;

/**
 *
 * @author Thai Bui - Created At: 10/15/2018 - 3:35 PM
 * @version 1.0.0
 *
 */
class ItemBase extends Collection
{
    public function __construct(array $data = [])
    {
        parent::__construct($this->castItems($data));
    }

    public function castItems(array $data)
    {
        $items = [];

        foreach ($data as $k => $v) {
            if ($this->shouldCastAsDateTime($k)
                && is_numeric($v)
            ) {
                $items[$k] = $this->castToDateTime($v);
            } else {
                $items[$k] = $v;
            }
        }

        return $items;
    }

    public function shouldCastAsDateTime($key)
    {
        return in_array($key, [
            'created_at',
        ], true);

    }

    public function castToDateTime($value)
    {
        if (is_int($value)) {
            $dt = new \DateTime();
            $dt->setTimestamp($value);
        } else {
            $dt = new \DateTime($value);
        }

        return $dt;
    }
}

