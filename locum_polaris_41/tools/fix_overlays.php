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
ini_set('memory_limit', '400M');

$cache_purge_script_url = 'http://www.darienlibrary.org/cache-b-gone.php';

// Drupal DSN
require_once('/usr/local/etc/drupal_dsn.php');

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
  die($polaris_db->getMessage());
}

$drupal_db = MDB2::connect($dsn);
if (PEAR::isError($drupal_db)) {
  die($drupal_db->getMessage());
}

$polaris_db_sql = 'SELECT [OldBibRecordID], [NewBibRecordID], [TranClientDate] FROM [Polaris].[dbo].[!!SOPAC_BibReplacement] ORDER BY OldBibRecordID ASC';
$polaris_db_query = $polaris_db->query($polaris_db_sql);
$polaris_result = $polaris_db_query->fetchAll(MDB2_FETCHMODE_ASSOC);

$bib_map = array();
foreach ($polaris_result as $bib) {
  $locum->bib_shift($bib['oldbibrecordid'], $bib['newbibrecordid']);

  foreach ($drupal_bib_map as $table => $field) {
    $sql = 'UPDATE ' . $table . ' SET ' . $field . ' = ' . $bib['newbibrecordid'] . ' WHERE ' . $field . ' = ' . $bib['oldbibrecordid'];
    $drupal_db->query($sql);
    $drupal_db->free();
  }
}

// Clear Drupal cache
file_get_contents($cache_purge_script_url);






