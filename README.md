# Joomla! Content Management Framework

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
php cli/console sql import default -y
php cli/console cmf make
```

The CMF system will convert this Joomla! package to CMF package, and ask you to create a new user.

## Removed Core Extensions

- component:
    - admin
        - com_ajax
        - com_banners
        - com_contact
        - com_contenthistory
        - com_finder
        - com_newsfeeds
        - com_search
        - com_weblinks
        - com_mailto
        - com_wrapper
        - com_messages
        - com_content
        - com_joomlaupdate
        - com_postinstall
- module:
    - site
        - mod_articles_archive
        - mod_articles_latest
        - mod_articles_popular
        - mod_banners
        - mod_feed
        - mod_footer
        - mod_articles_news
        - mod_random_image
        - mod_related_items
        - mod_search
        - mod_stats
        - mod_weblinks
        - mod_whosonline
        - mod_wrapper
        - mod_articles_category
        - mod_articles_categories
        - mod_finder
    - admin
        - mod_feed
        - mod_latest
        - mod_popular
        - mod_status
        - mod_multilangstatus
        - mod_version
        - mod_stats_admin
        - mod_tags_popular
        - mod_tags_similar
- plugin:
    - plg_content_contact
    - plg_content_emailcloak
    - plg_content_pagebreak
    - plg_content_pagenavigation
    - plg_content_vote
    - plg_editors-xtd_article
    - plg_editors-xtd_pagebreak
    - plg_search_categories
    - plg_search_contacts
    - plg_search_content
    - plg_search_newsfeeds
    - plg_search_weblinks
    - plg_user_profile
    - plg_extension_joomla
    - plg_content_joomla
    - plg_quickicon_joomlaupdate
    - plg_quickicon_extensionupdate
    - plg_system_highlight
    - plg_content_finder
    - plg_finder_categories
    - plg_finder_contacts
    - plg_finder_content
    - plg_finder_newsfeeds
    - plg_finder_weblinks
    - plg_finder_tags
    - plg_twofactorauth_totp
    - plg_twofactorauth_yubikey
