# Module UB Data Migration Lite
>This is a Magento 2 module, allows migrate some data from Magento 1.x to Magento 2.x

### Author: [UberTheme](http://www.ubertheme.com)

### Allow Features:
+ [x] Migrate Web sites, Stores, Store views
+ [x] Migrate Product Attribute Sets, Product Attribute Groups, Product Attributes
+ [x] Migrate Product Categories
+ [x] Migrate Products
+ [-] Migrate Customer Groups and Customers (_PRO version only_) 
+ [-] Migrate Sales Data: (_PRO version only_) 
    + Sales Orders (_PRO version only_)
    + Sales Quote (_PRO version only_)
    + Sales Payments (_PRO version only_)
    + Sales Invoices (_PRO version only_)
    + Sales Shipments (_PRO version only_) 
    + Sales Credit Memo (_PRO version only_)
    + Sales Rules & Coupons (_PRO version only_)
+ [-] Migrate Product Reviews, Ratings data (_PRO version only_)
+ [-] Migrate Tax Rules, Tax Zones and Tax Rates (_PRO version only_)
+ [-] Catalog Rules  (_PRO version only_)
+ [-] Email Templates and Newsletter Data (_PRO version only_)
    + Email Templates (_PRO version only_)
    + Newsletter Templates (_PRO version only_)
    + Newsletter Queue (_PRO version only_)
    + Newsletter Subscribers (_PRO version only_)
    + Newsletter Problem Reports (_PRO version only_)
+ [-] Migrate System Increment IDs (_PRO version only_)

### Compatible:
    + Magento CE 1.x: 1.6.x, 1.7.x, 1.8.x, 1.9.x
    + Magento 2 CE 2.0.0 and later

### Prepare (precondition):
- Install a Magento 2 fresh website (without data sample)
    + Download Magento Community Edition 2.0.x from below link: https://www.magentocommerce.com/download
    + Follow [Installation guide](http://devdocs.magento.com/guides/v2.0/install-gde/install-quick-ref.html) to Install a Magento 2 fresh website
- Enable the **[php sqlite](http://php.net/manual/en/sqlite.installation.php)** in your server (_required_)
- Make write permission for `pub` folder at path `PATH_YOUR_MAGENTO_2/pub`

### How To Install:

#### Option 1: Install from zip file downloaded (manual install) 
- Extract the zip file you have downloaded. Copy the `app` folder and paste to root folder of your Magento 2 fresh (_you have to select merge folders/files option_).
- Open your Terminal window, go to your Magento 2 folder and run below commands to install this module:
    + `php -f bin/magento module:enable --clear-static-content Ubertheme_Ubdatamigration`
    + `php -f bin/magento setup:upgrade`
    + `php -f bin/magento cache:clean` (_optional_)

#### Option 2: Install via Composer:
- Open your terminal window, go to your Magento 2 folder and run below command:
    + `composer config repositories.ubdatamigration-lite vcs git@bitbucket.org:ubertheme/module-ubdatamigration-lite.git`
    + `composer require ubertheme/module-ubdatamigration-lite`
    + `php -f bin/magento module:enable --clear-static-content Ubertheme_Ubdatamigration`
    + `php -f bin/magento setup:upgrade`
    + `php -f bin/magento cache:clean` (_optional_)
- Successfully screenshots:
> Coming soon...

### How To Use:

#### 1 - Prepare to migrate data:
- Set maintenance mode for your Magento 1
- Off all cron jobs related your Magento 1

#### 2 - Login to back-end of your Magento 2, follow step by step with UI of this module as below screenshots:

#### 3 - Finish (_required_)
**To finish the data migration from Magento 1.x to Magento 2.x, you have to do some tasks below:**

- Copy media files
    + Copy the folder at PATH_YOUR_MAGENTO_1\media\catalog and paste replace to PATH_YOUR_MAGENTO_2\pub\media\
    + Copy the folder at PATH_YOUR_MAGENTO_1\media\downloadable and paste replace to PATH_YOUR_MAGENTO_2\pub\media\
    + Make recursive write permission to "catalog" and "downloadable" folders which you have just copied.

- Re-save all the Attribute Sets migrated:
    In back-end of your Magento 2 go to Stores/Attributes/Attribute menu navigation. 
    Open the Attribute Set, edit information of it if needed and click the save button

- Re-Index the data: In your terminal window, go to your Magento 2 folder and run below command:
    `php -f bin/magento indexer:reindex`

- Clean Magento 2 cache: In your terminal window, go to your Magento 2 folder and run below command:
    `php -f bin/magento cache:clean`

### 3 - Let’s discover Magento 2 with your data migrated by URL:
    http://your_magento2_url/