<?php

namespace tests\_pages;

use yii\codeception\BasePage;

/**
 * Represents email settings page.
 *
 * @property \FunctionalTester $actor
 */
class SettingsPage extends BasePage
{
    /** @inheritdoc */
    public $route = '/user/settings/account';

    /**
     * @param $email
     * @param $slug
     * @param $name
     * @param null $password
     * @param $currentPassword
     */
    public function update($email, $slug, $name, $currentPassword, $password = null)
    {
        $this->actor->fillField('#settings-form-email', $email);
        $this->actor->fillField('#settings-form-slug', $slug);
        $this->actor->fillField('#settings-form-name', $name);
        $this->actor->fillField('#settings-form-new_password', $password);
        $this->actor->fillField('#settings-form-current_password', $currentPassword);
        $this->actor->click('Save');
    }
}
