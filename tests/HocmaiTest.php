<?php
/**
 * @link http://hocmai.vn/
 * @copyright Cong ty CP Dau tu va dich vu Giao duc
 * @license http://hocmai.vn/
 */
namespace HocmaiSDKTest;
use Hocmai\Hocmai;

/**
 *
 * @author Thai Bui - Created At: 10/15/2018 - 4:08 PM
 * @version 1.0.0
 *
 */
class HocmaiTest extends TestBase
{

    public function setUp()
    {

    }

    public function tearDown()
    {

    }

    /**
     * @test
     *
     */
    public function call_get_method()
    {
        $hocmai = new Hocmai([
            'app_id' => '123',
            'app_secret' => 'abc'
        ]);
        $ret = $hocmai->get('/user/1', '123123');
        $productLine = $ret->getProductLine();
        var_dump($productLine->getId());die;
//        var_dump($ret);die;
    }
}

