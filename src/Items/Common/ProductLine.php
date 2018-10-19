<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\Items\Common;
use Hocmai\Items\ItemBase;

/**
 *
 * @author Thai Bui - Created At: 10/16/2018 - 1:53 PM
 * @version 1.0.0
 *
 */
class ProductLine extends ItemBase
{
    public function getId()
    {
        return $this->getField('id');
    }

    public function getName()
    {
        return $this->getField('name');
    }
}

