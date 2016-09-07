<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user;

use Yii;

/**
 * Trait ModuleTrait
 * @property-read Module $module
 * @package Leaps\User\Traits
 * @author Xu Tongle <xutongle@gmail.com>
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return Yii::$app->getModule('user');
    }
}