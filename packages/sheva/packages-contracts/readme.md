## Install

In the main project `composer.json` file add new package registration
```json
{
  "repositories": [
    {
      "type": "path",
      "url": "./packages/sheva/packages-contracts",
      "options": {
        "symlink": true
      }
    }
  ]
}
```

Install package
```bash
composer require sheva/packages-contracts
```
