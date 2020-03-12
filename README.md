# Yii2 ABCMS structure library

## Install:
```bash
composer require abcms/yii2-structure:dev-master
```

## DB migrations
1- Add the migration namespaces in the console.php configuration:
```php
'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationNamespaces' => [
            'abcms\library\migrations',
            'abcms\structure\migrations',
        ],
    ],
],
```

2- Run `./yii migrate`

## Default translation source
Add a default translation source in the main configuration:
```php
'i18n' => [
    'translations' => [
        '*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/messages',
        ],
    ],
],
```

## Add the module
List the structure module in the modules property of the application:
```php
[
    'modules' => [
        'structure' => [
            'class' => 'abcms\structure\module\Module',
        ],
    ],
]
```
