<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$active_group = 'dev';
$active_record = TRUE;


$db['dev']['hostname'] = 'mysql:host=localhost;dbname=ci_audio';
$db['dev']['username'] = 'root';
$db['dev']['password'] = 'root';
$db['dev']['database'] = 'ci_audio';
$db['dev']['dbdriver'] = 'pdo';
$db['dev']['cache_on'] = TRUE;
$db['dev']['cachedir'] = '';
$db['dev']['char_set'] = 'utf8';
$db['dev']['dbcollat'] = 'utf8_general_ci';

$db['production']['hostname'] = 'mysql:host=localhost;dbname=YOUR_DATABASE_NAME';
$db['production']['username'] = 'YOUR_USERNAME';
$db['production']['password'] = 'YOUR_PASSWORD';
$db['production']['database'] = 'YOUR_DATABASE_NAME';
$db['production']['dbdriver'] = 'pdo';
$db['production']['cache_on'] = TRUE;
$db['production']['cachedir'] = '';
$db['production']['char_set'] = 'utf8';
$db['production']['dbcollat'] = 'utf8_general_ci';

/* End of file database.php */
/* Location: ./application/config/database.php */