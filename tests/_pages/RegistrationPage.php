<?php

namespace tests\_pages;

use yii\codeception\BasePage;

/**
 * Represents registration page.
 *
 * @property \FunctionalTester $actor
 */
class RegistrationPage extends BasePage
{
    /** @inheritdoc */
    public $route = '/user/registration/register';

    /**
     * @param $name
     * @param $email
     * @param $password
     */
    public function register($email, $name = null, $password = null)
    {
        $this->actor->fillField('#register-form-email', $email);
        $this->actor->fillField('#register-form-username', $name);
        if ($password !== null) {
            $this->actor->fillField('#register-form-password', $password);
        }
        $this->actor->click('Sign up');
    }
}
