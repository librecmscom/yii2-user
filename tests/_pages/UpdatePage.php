<?php

namespace tests\_pages;

use yii\codeception\BasePage;

/**
 * Represents admin update page.
 *
 * @property \FunctionalTester $actor
 */
class UpdatePage extends BasePage
{
    /** @inheritdoc */
    public $route = '/user/admin/update';

    /**
     * @param $name
     * @param $slug
     * @param $email
     * @param $password
     */
    public function update($name,$slug, $email, $password = null)
    {
        $this->actor->fillField('#user-slug', $slug);
        $this->actor->fillField('#user-name', $name);
        $this->actor->fillField('#user-email', $email);
        $this->actor->fillField('#user-password', $password);
        $this->actor->click('Update');
    }
}
