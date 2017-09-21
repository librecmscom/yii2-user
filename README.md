# yii2-user

[![Latest Stable Version](https://poser.pugx.org/yuncms/yii2-user/v/stable.png)](https://packagist.org/packages/yuncms/yii2-user)
[![Total Downloads](https://poser.pugx.org/yuncms/yii2-user/downloads.png)](https://packagist.org/packages/yuncms/yii2-user)
[![Reference Status](https://www.versioneye.com/php/yuncms:yii2-user/reference_badge.svg)](https://www.versioneye.com/php/yuncms:yii2-user/references)
[![Build Status](https://img.shields.io/travis/yiisoft/yii2-user.svg)](http://travis-ci.org/yuncms/yii2-user)
[![Dependency Status](https://www.versioneye.com/php/yuncms:yii2-user/dev-master/badge.png)](https://www.versioneye.com/php/yuncms:yii2-user/dev-master)
[![License](https://poser.pugx.org/yuncms/yii2-user/license.svg)](https://packagist.org/packages/yuncms/yii2-user)

Most of web applications provide a way for users to register, log in or reset
their forgotten passwords. Rather than re-implementing this on each application,
you can use Yii2-user which is a flexible user management module for Yii2 that
handles common tasks such as registration, authentication and password retrieval.
The latest version includes following features:

* 注册使用邮箱激活账号
* 使用社交账户注册
* 密码找回
* 账号和个人资料管理
* 控制台命令
* 用户管理接口

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist yuncms/yii2-user
```

or add

```json
"yuncms/yii2-user": "~2.0.0"
```

to the `require` section of your composer.json.


## Configuring your application

Add following lines to your main configuration file:

```
'modules' => [
    'user' => [
        'class' => 'yuncms\user\frontend\Module',
    ],
],
```

## Updating database schema

After you downloaded and configured Yii2-user, the last thing you need to do is updating your database schema by applying the migrations:

    $ php yii migrate/up --migrationPath=@vendor/yuncms/yii2-user/migrations

## Thanks to

* [Yii framework](https://github.com/yiisoft/yii2)
* [dektrium](https://github.com/dektrium/yii2-user)

## License

This is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.