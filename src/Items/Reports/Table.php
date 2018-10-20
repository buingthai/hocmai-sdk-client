<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace Hocmai\Items\Reports;
use Hocmai\Items\ItemBase;

/**
 *
 * @author Thai Bui - Created At: 10/20/2018 - 11:49 AM
 * @version 1.0.0
 *
 */
class Table extends ItemBase
{
    /**
     * @return array
     *
     */
    public function getColumns()
    {
        return $this->getField('columns');
    }

    /**
     * So ban ghi hien thi dong thoi
     * @return int
     *
     */
    public function getLimit()
    {
        return $this->getField('limit');
    }

    public function getTotal()
    {
        return $this->getField('total');
    }

    public function getOffset()
    {
        return $this->getField('offset');
    }

    public function getData()
    {
        return $this->getField('data');
    }
}

