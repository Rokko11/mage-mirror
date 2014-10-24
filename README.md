# Rokko11_MageMirror

This Project provides a Mirror of most common Magento-Versions for easy installation via composer or modman.

## Installation via modman

Simply run `modman clone https://github.com/Rokko11/mage-mirror.git`

## Installation via composer

Create a `composer.json` like this and run `composer update`

```
{
    "require": {
        "rokko11/mage-mirror": "1.9.0.1"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git://github.com/Rokko11/mage-mirror.git"
        }
    ],
    "extra": {
        "magento-root-dir": "./html",
        "magento-deploystrategy": "copy",
        "magento-force": "true"
    }
}
```

