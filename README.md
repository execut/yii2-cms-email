CMS email module for Yii 2
========================


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require infoweb-internet-solutions/yii2-cms-email "*"
```

or add

```
"infoweb-internet-solutions/yii2-cms-email": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

Your backend configuration as follows:

```php
return [
    ...
    'modules' => [
        'email' => [
            'class' => 'infoweb\email\Module'
        ],
    ],
    ...
];
```

Import the translations and use category 'infoweb/email':
```
yii i18n/import @infoweb/email/messages
```

To use the module, execute yii migration
```
yii migrate/up --migrationPath=@vendor/infoweb-internet-solutions/yii2-cms-email/migrations