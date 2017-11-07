<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace tests\unit\models;

use tests\_fixtures\UserFixture;
use yuncms\user\models\User;

/**
 * Class AdminTest
 * @package tests\models
 */
class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }



    public function testFindUserByMobile()
    {
        expect_that($user = User::findByMobile('13800138000'));
        expect_not(User::findByMobile('13800137000'));
    }

    public function testFindUserByEmail()
    {
        expect_that($user = User::findByEmail('user@example.com'));
        expect_not(User::findByEmail('us123er@example.com'));
    }

    public function testFindUserByUsername()
    {
        expect_that($user = User::findModelByUsername('user'));
        expect_not(User::findByEmail('us123er'));
    }

    /**
     * @depends testFindUserByMobile
     */
    public function testValidateUser()
    {
        $user = User::findByMobile('13800138000');
        expect_that($user->validateAuthKey('39HU0m5lpjWtqstFVGFjj6lFb7UZDeRq'));
        expect_not($user->validateAuthKey('test102key'));
        expect_that($user->validatePassword('123456'));
        expect_not($user->validatePassword('admin'));
    }
}