# Joomla! CMF for SMS Taiwan

A light Joomla! with limited core extensions.

## Installation

Clone this repo and copy `configuration.dist.php` to `configuration.php`, then fill database information.

Execute these commands:

``` bash
php cli/console sql import default
php cli/console cmf make
```

