<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\console;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use yuncms\user\models\User;

class UserController extends Controller
{
    /**
     * This command creates new user account. If password is not set, this command will generate new 8-char password.
     * After saving user to database, this command uses mailer component to send credentials (username and password) to
     * user via email.
     *
     * @param string $email Email address
     * @param string $username Username
     * @param null|string $password Password (if null it will be generated automatically)
     */
    public function actionCreate($email, $username, $password = null)
    {
        $user = new User(['scenario' => 'create', 'email' => $email, 'username' => $username, 'password' => $password]);
        if ($user->create()) {
            $this->stdout(Yii::t('user', 'User has been created') . "!\n", Console::FG_GREEN);
        } else {
            $this->stdout(Yii::t('user', 'Please fix following errors:') . "\n", Console::FG_RED);
            foreach ($user->errors as $errors) {
                foreach ($errors as $error) {
                    $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
        }
    }

    /**
     * Confirms a user by setting confirmed_at field to current time.
     *
     * @param string $search Email or username
     */
    public function actionConfirm($search)
    {
        $user = User::findByEmailOrMobile($search);
        if ($user === null) {
            $this->stdout(Yii::t('user', 'User is not found') . "\n", Console::FG_RED);
        } else {
            if ($user->setEmailConfirm()) {
                $this->stdout(Yii::t('user', 'User has been confirmed') . "\n", Console::FG_GREEN);
            } else {
                $this->stdout(Yii::t('user', 'Error occurred while confirming user') . "\n", Console::FG_RED);
            }
        }
    }

    /**
     * Updates user's password to given.
     *
     * @param string $search Email or username
     * @param string $password New password
     */
    public function actionPassword($search, $password)
    {
        $user = User::findByEmailOrMobile($search);
        if ($user === null) {
            $this->stdout(Yii::t('user', 'User is not found') . "\n", Console::FG_RED);
        } else {
            if ($user->resetPassword($password)) {
                $this->stdout(Yii::t('user', 'Password has been changed') . "\n", Console::FG_GREEN);
            } else {
                $this->stdout(Yii::t('user', 'Error occurred while changing password') . "\n", Console::FG_RED);
            }
        }
    }

    /**
     * Deletes a user.
     *
     * @param string $search Email or username
     */
    public function actionDelete($search)
    {
        if ($this->confirm(Yii::t('user', 'Are you sure? Deleted user can not be restored'))) {
            $user = User::findByEmailOrMobile($search);
            if ($user === null) {
                $this->stdout(Yii::t('user', 'User is not found') . "\n", Console::FG_RED);
            } else {
                if ($user->delete()) {
                    $this->stdout(Yii::t('user', 'User has been deleted') . "\n", Console::FG_GREEN);
                } else {
                    $this->stdout(Yii::t('user', 'Error occurred while deleting user') . "\n", Console::FG_RED);
                }
            }
        }
    }
}