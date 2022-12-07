# Tun2U Magento 2 KlaviyoAddons extension

[![Latest Stable Version](https://poser.pugx.org/tun2u/m2-module-klaviyoaddons/v/stable)](https://packagist.org/packages/tun2u/m2-module-klaviyoaddons)
[![Total Downloads](https://poser.pugx.org/tun2u/m2-module-klaviyoaddons/downloads)](https://packagist.org/packages/tun2u/m2-module-klaviyoaddons)
[![License](https://poser.pugx.org/tun2u/m2-module-klaviyoaddons/license)](https://packagist.org/packages/tun2u/m2-module-klaviyoaddons)


## Features

* Gets all unsubscribed users from klaviyo and unsubscribes them from magento. Scheduled by cron.
* Supports Magento 2.x

## Installing

##### Manual Installation
Install Tun2U KlaviyoAddons extensions for Magento 2
 * Download the extension
 * Unzip the file
 * Create a folder {Magento root}/app/code/Tun2U/KlaviyoAddons
 * Copy the content from the unzip folder
 * Run following command
 ```
 php bin/magento setup:upgrade
 php bin/magento setup:static-content:deploy
 php bin/magento setup:di:compile
 php bin/magento cache:flush
 ```
 * Flush cache

##### Using Composer (from Magento Root Dir run)

```
composer require tun2u/m2-module-klaviyoaddons
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
php bin/magento setup:di:compile
php bin/magento cache:flush
```

## Requirements

- PHP >= 7.0.0

## Compatibility

- Magento >= 2.0

## Support

If you encounter any problems or bugs, please create an issue on [GitHub](https://github.com/Tun2U/M2-Module-KlaviyoAddons/issues).

## Developer

##### Tun2U Team
* Website: [https://www.tun2u.com](https://www.tun2u.com)
* Twitter: [@tun2u](https://twitter.com/tun2u)

## Licence

[GNU General Public License, version 3 (GPLv3)](http://opensource.org/licenses/gpl-3.0)

## Copyright

(c) 2022 Tun2U Team