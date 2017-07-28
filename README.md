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

* Registration with an optional confirmation per mail
* Registration via social networks
* Password recovery
* Account and profile management
* Console commands
* User management interface
* Ability to impersonate as another user given admin access is available

> **NOTE:** Module is in initial development. Anything may change at any time.

## Installation

Next steps will guide you through the process of installing yii2-admin using [composer](http://getcomposer.org/download/). Installation is a quick and easy three-step process.

### Step 1: Install component via composer

Either run

```
composer require --prefer-dist yuncms/yii2-user
```

or add

```json
"yuncms/yii2-user": "~2.0.0"
```

to the `require` section of your composer.json.


### Step 2: Configuring your application

Add following lines to your main configuration file:

```
'modules' => [
    'user' => [
        'class' => 'yuncms\user\frontend\Module',
    ],
],
```

###  Step 3: Updating database schema

After you downloaded and configured Yii2-user, the last thing you need to do is updating your database schema by applying the migrations:

    $ php yii migrate/up --migrationPath=@vendor/yuncms/yii2-user/migrations

## Thanks to

* [Yii framework](https://github.com/yiisoft/yii2)
* [dektrium](https://github.com/dektrium/yii2-user)

## License

Yii2-user is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details.