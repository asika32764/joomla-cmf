# Joomla! CMF for SMS Taiwan

A light Joomla! with limited core extensions.

## Installation

Download [this repo](https://github.com/asika32764/joomla-cmf/archive/master.zip) to your project path.

Copy `configuration.dist.php` to `configuration.php`, then fill database information.

``` bash
cp configuration.dist.php configuration.php
EDITOR configuration.php
```

Execute these commands:

``` bash
php cli/console sql import default
php cli/console cmf make
```

