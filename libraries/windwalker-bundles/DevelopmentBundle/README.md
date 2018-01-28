# Windwalker Joomla RAD Development Bundle

Some helpful command line tools to operate database for development.

## Table of Content

- [Sqlsync](#sql-sync)
- [Seeder](#seeder)

## Installation via Composer

``` bash
cd /to/your/joomla

composer create-project windwalker/rad-development-bundle libraries/windwalker-bundles/DevelopmentBundle 1.*
```

## Commands

``` bash
Windwalker Console - version: 2.1
------------------------------------------------------------

# system commands ...

  seed         The data seeder help you create fake data.
    import       Import seeders.
    clear        Clear seeders.

  sql          SQL sync & diff tools.
    backup       Backup sql.
    col          Column operation
    export       Export sql.
    import       Import a sql file.
    profile      Profiles.
    restore      Restore to pervious point.
    table        Model operation.
```

## SQL Sync

Windwalker Sqlsync is a powerful SQL compare & diff tools help developers update their SQL schema.

### Why Not Migration Tools?

Actually, we are developing a migration tools for Joomla & Windwalker now, but migration is not so suitable for Joomla CMS, sometimes 
we will want to sync Joomla articles, modules, extensions and menus to your production server. Migration tools are
hard to do this. 

So, using Sqlsync tools will help you compare sql schema between your local & remote machine, simply run `export` in your local,
and git track all schema YAML files. Then push your files to remote server and run `import`, all schemas will update to remote.

> Currently Sqlsync are weak on column name change, you can use hooks to do this.

### Export & Import SQL Schema

``` bash
php bin/windwalker sql export

php bin/windwalker sql import
```

Scheam will export to `resources/sqlsync/default/schema.yml`

### Track Tables

Table track information stores in `resources/sqlsync/default/track.yml`

Default is:

``` yaml
table:
    '#__assets': all
    '#__associations': all
    '#__banner_clients': all
    '#__banner_tracks': all
    '#__banners': all
    '#__categories': all
    '#__contact_details': all
    '#__content': all
    '#__content_frontpage': all
    '#__content_rating': all
    '#__content_types': all
    '#__contentitem_tag_map': all
    '#__core_log_searches': all
    '#__extensions': all
    '#__finder_filters': cols
    '#__finder_links': cols
    '#__finder_links_terms0': cols
    '#__finder_links_terms1': cols
    '#__finder_links_terms2': cols
    '#__finder_links_terms3': cols
    '#__finder_links_terms4': cols
    '#__finder_links_terms5': cols
    '#__finder_links_terms6': cols
    '#__finder_links_terms7': cols
    '#__finder_links_terms8': cols
    '#__finder_links_terms9': cols
    '#__finder_links_termsa': cols
    '#__finder_links_termsb': cols
    '#__finder_links_termsc': cols
    '#__finder_links_termsd': cols
    '#__finder_links_termse': cols
    '#__finder_links_termsf': cols
    '#__finder_taxonomy': cols
    '#__finder_taxonomy_map': cols
    '#__finder_terms': cols
    '#__finder_terms_common': all
    '#__finder_tokens': cols
    '#__finder_tokens_aggregate': cols
    '#__finder_types': cols
    '#__languages': all
    '#__menu': all
    '#__menu_types': all
    '#__messages': all
    '#__messages_cfg': all
    '#__modules': all
    '#__modules_menu': all
    '#__newsfeeds': all
    '#__overrider': all
    '#__postinstall_messages': all
    '#__redirect_links': all
    '#__schemas': all
    '#__session': cols
    '#__tags': all
    '#__template_styles': all
    '#__ucm_base': all
    '#__ucm_content': all
    '#__ucm_history': all
    '#__update_sites': all
    '#__update_sites_extensions': all
    '#__updates': cols
    '#__user_keys': all
    '#__user_notes': all
    '#__user_profiles': all
    '#__user_usergroup_map': all
    '#__usergroups': all
    '#__users': all
    '#__utf8_conversion': all
    '#__viewlevels': all
```

There are 3 track rules:

- `all` - Track all data, always refresh data when import & export.
- `cols` - Only track table columns, do not refresh data.
- `none` - Ignore this table

> All table which not list in track.yml will be `none`.

### Sync Track Tables

If you installed a new component, there may be multiple tables added to ypur database, you can run:

``` bash 
php bin/windwalker sql table sync
```

To auto add all non-tracked table to `track.yml`

### Status

This command help you check table track information.

``` bash
php bin/windwalker sql status
```

![p-2016-04-05-006](https://cloud.githubusercontent.com/assets/1639206/14274758/d143e078-fb45-11e5-9e53-c5967f94a5a9.jpg)

### Profiles

You can change profile by use

``` bash
# List profile
php bin/windwalker sql profile

# Create & checkout profile
php bin/windwalker sql profile create test
php bin/windwalker sql profile checkout test
```

Now you can export schema to other profile, this is very similar to git branches.

### Quick import & export to profile

You don't need to always checkout profiles, add profiles as arguments in commands.

``` bash
# Single profile
php bin/windwalker sql export test

# Multiple profiles
php bin/windwalker sql export default foo bar test

# Ignore checking prompt
php bin/windwalker sql export default foo bar test -y

# Export all profiles
php bin/windwalker sql export --all -y
```

This operations can support `export` / `import` both commands.

### Rename Column

Modify `From` property in your schema files.

``` yaml
columns:
  oldname: { Field: newname, ... , From: [oldname, oldname2] }
```

All oldnames in `From` property will convert to `newname`

> Currently Sqlsync are weak on column name change, try avoid to do this operation.

### Export & Import Hooks

Add these files to profile folder.

``` php
pre-export.php
post-export.php
pre-import.php
post-import.php
```

And simply write your script to do something.

``` php
// resources/sqlsync/default/pre-import.php

if (!JFactory::getConfig()->get('debug'))
{
    throw new \Exception('STOP importing, please enable debug mode to do any DB operations.');
}

// Or do some advanced DB actions, for instance, rename column or remove indexes.
JFactory::getDbo()->setQuery('ALTER TABLE ...')->execute();

```

## Seeder

Windwalker Developement Bundle provides a simple seeder and faker tools to help you generate fake data.

Open `resources/seeders/DatabaseSeeder.php`, you will see `DatabaseSeeder` default class:

Add a new seeder class at `resources/seeders/ProductSeeder.php`

``` php
use Faker\Factory;
use Windwalker\Data\Data;
use Windwalker\DataMapper\DataMapper;

class ProductSeeder extends \DevelopmentBundle\Seeder\AbstractSeeder
{
	public function doExecute()
	{
		$faker = Factory::create();
		$mapper = new DataMapper('#__mycomponent_products');
		$categories = (new DataMapper('#__categories'))->findColumn('id', ['extension' => 'com_mycomponent']);
		$userIds = (new DataMapper('#__users'))->id;

		foreach (range(1, 200) as $i)
		{
			$data = new Data;
			
			$user_id = JFactory::getUser()->id;

			$data['catid']        = $faker->randomElement($categories);
			$data['title']        = $faker->sentence(rand(3, 5));
			$data['alias']        = JFilterOutput::stringURLSafe($data['title']);
			$data['temperature']  = $faker->randomElement(['normal', 'refrigeration', 'freeze']);
			$data['price']        = rand(5000, 10000)/100;
			$data['location']     = $faker->country;
			$data['description']  = $faker->paragraph(5);
			$data['image']        = $faker->imageUrl();
			$data['state']        = $faker->randomElement([1, 1, 1, 1, 1, 0]);
			$data['created']      = $faker->dateTimeThisMonth->format('Y-m-d H:i:s');
			$data['created_by']   = $faker->randomElement($userIds);
			$data['modified']     = $faker->dateTimeThisMonth->format('Y-m-d H:i:s');
			$data['modified_by']  = $faker->randomElement($userIds);
			$data['params']       = '';

			$mapper->createOne($data);

			$this->command->out('.', false);
		}

		$this->command->out();
	}
	
	public function doClear()
	{
		$this->truncate('#__mycomponent_products');
	}
}
```

And set this class to `DatabaseSeeder`, you must sort seeders by denpendencies.

``` php
// ...

	public function doExecute()
	{
		$this->execute(new CategorySeeder);

		$this->execute(new ProductSeeder);

		$this->execute(new OrderSeeder);
	}

	public function doClear()
	{
		$this->clear(new CategorySeeder);

		$this->clear(new ProductSeeder);

		$this->clear(new OrderSeeder);
	}
```

Now run seeder by:

``` bash
php bin/windwalker seed import
```

Or clear it

``` bash
php bin/windwalker seed export
```


