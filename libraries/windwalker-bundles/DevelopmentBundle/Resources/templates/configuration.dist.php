<?php
class JConfig
{
	// Database settings
	public $dbtype   = 'pdomysql';
	public $host     = 'localhost';
	public $user     = 'root';
	public $password = '';
	public $db       = '';
	public $dbprefix = 'jos_';

	// Paths
	public $log_path = '../logs';
	public $tmp_path = '../tmp';

	// Cache settings
	public $caching       = '0';
	public $cache_handler = 'file';
	public $cachetime     = '15';
	public $cache_platformprefix = '0';

	// Error reporting settings
	public $error_reporting = 'none';

	// Debug settings
	public $debug      = '0';
	public $debug_lang = '0';

	// Site Information
	public $sitename = '__Site_Name__';
	public $MetaDesc = '';
	public $MetaKeys = '';
	public $MetaTitle = '1';
	public $MetaAuthor = '1';
	public $MetaVersion = '0';
	public $MetaRights = '';

	// Timezone
	public $offset = 'UTC';

	// Mail
	public $fromname = '__Site_Name__';
	public $mailonline = '1';
	public $mailer = 'mail';
	public $mailfrom = 'noreply@example.com';
	public $sendmail = '/usr/sbin/sendmail';
	public $smtpauth = '1';
	public $smtpuser = '';
	public $smtppass = '';
	public $smtphost = 'mailtrap.io';
	public $smtpsecure = 'tls';
	public $smtpport = '2525';
	public $massmailoff = '0';

	// SEF URL
	public $sef = '1';
	public $sef_rewrite = '1';
	public $sef_suffix = '1';
	public $unicodeslugs = '0';

	// Other config
	public $offline = '0';
	public $offline_message = 'This site is down for maintenance.<br /> Please check back again soon.';
	public $display_offline_message = '1';
	public $offline_image = '';
	public $editor = 'tinymce';
	public $captcha = '0';
	public $list_limit = '20';
	public $access = '1';
	public $live_site = '';
	public $secret = 'V0rML8ej3sPaDber';
	public $gzip = '0';
	public $helpurl = 'https://help.joomla.org/proxy/index.php?option=com_help&keyref=Help{major}{minor}:{keyref}';
	public $ftp_host = '';
	public $ftp_port = '';
	public $ftp_user = '';
	public $ftp_pass = '';
	public $ftp_root = '';
	public $ftp_enable = '0';
	public $robots = '';
	public $feed_limit = '10';
	public $feed_email = 'none';
	public $lifetime = '150';
	public $session_handler = 'database';
	public $memcache_persist = '1';
	public $memcache_compress = '0';
	public $memcache_server_host = 'localhost';
	public $memcache_server_port = '11211';
	public $memcached_persist = '1';
	public $memcached_compress = '0';
	public $memcached_server_host = 'localhost';
	public $memcached_server_port = '11211';
	public $redis_persist = '1';
	public $redis_server_host = 'localhost';
	public $redis_server_port = '6379';
	public $redis_server_auth = '';
	public $redis_server_db = '0';
	public $proxy_enable = '0';
	public $proxy_host = '';
	public $proxy_port = '';
	public $proxy_user = '';
	public $proxy_pass = '';
	public $sitename_pagetitles = '0';
	public $force_ssl = '0';
	public $session_memcache_server_host = 'localhost';
	public $session_memcache_server_port = '11211';
	public $session_memcached_server_host = 'localhost';
	public $session_memcached_server_port = '11211';
	public $frontediting = '1';
	public $cookie_domain = '';
	public $cookie_path = '';
	public $asset_id = '1';

	public function __construct()
	{
		$this->log_path = JPATH_ROOT . '/logs';
		$this->tmp_path = JPATH_ROOT . '/tmp';
	}
}
