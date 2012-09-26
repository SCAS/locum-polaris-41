#!/usr/bin/php5 -q
<?php
/**
 * Fix Overlay utility for rolling forward Bib #s in SCAS
 *
 * @author John Blyberg (john@blyberg.net) at (www.blyberg.net)
 *
 * See README.md for important details.
 */

ini_set('mssql.charset', 'UTF-8');

// Drupal DSN
$drupal_dsn = require_once('/usr/local/etc/drupal_dsn.php');

// Drupal Table:Field mapping.  Array is tablename => fieldname
// where fieldname is the field containing the bib number
$drupal_bib_map = array(
	'darpl_content_field_itembnum' => 'field_itembnum_value',
);

// Grab Locum server code
require_once('/usr/local/lib/locum/locum-server.php');

// New locum_server()
$locum = new locum_server;

$psql_username = $locum->locum_config['polaris_sql']['username'];
$psql_password = $locum->locum_config['polaris_sql']['password'];
$psql_database = $locum->locum_config['polaris_sql']['database'];
$psql_server = $locum->locum_config['polaris_sql']['server'];
$psql_port = $locum->locum_config['polaris_sql']['port'];

$polaris_dsn = 'mssql://' . $psql_username . ':' . $psql_password . '@' . $psql_server . ':' . $psql_port . '/' . $psql_database;
$polaris_db =& MDB2::connect($polaris_dsn);
if (PEAR::isError($polaris_db)) {
  die($mdb2->getMessage());
}

$sql = 'SELECT [OldBibRecordID], [NewBibRecordID], [TranClientDate] FROM [Polaris].[dbo].[!!SOPAC_BibReplacement] ORDER BY OldBibRecordID ASC';