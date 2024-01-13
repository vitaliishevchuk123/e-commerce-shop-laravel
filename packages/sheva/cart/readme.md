## Install

In the main project `composer.json` file add new package registration
```json
{
  "repositories": [
    {
      "type": "path",
      "url": "./packages/sheva/cart",
      "options": {
        "symlink": true
      }
    }
  ]
}
```

Install package
```bash
composer require sheva/cart
```
Add user model in config file, by default:
```php
    'user_model' => \App\Models\User::class,
```
Add a payment module and a delivery module, configure them

`money-formatter` Configure currency output
`CartsStorage::class` User uuid storage is registered as Cookie storage by default
