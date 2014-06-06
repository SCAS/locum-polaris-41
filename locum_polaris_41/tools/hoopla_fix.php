#!/usr/bin/php5 -q
<?php
/**
 * Finds and fixes Hoopla records
 *
 * @author John Blyberg (john@blyberg.net) at (www.blyberg.net)
 *
 * See README.md for important details.
 */

// Mat Codes (may need to chage these)
$codes = array(
	'audio' => 11,
	'video' => 22,
);

ini_set('mssql.charset', 'UTF-8');
ini_set('memory_limit', '400M');

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

$scas_db = MDB2::connect($locum->dsn);
$utf = "SET NAMES 'utf8' COLLATE 'utf8_general_ci'";
$utfprep = $scas_db->query($utf);

$hoopla_sql = 'SELECT bnum, callnum from locum_bib_items WHERE callnum LIKE "%hoopla%"';

$hoopla_q = $scas_db->query($hoopla_sql);
$hoopla_bib_arr = $hoopla_q->fetchAll( MDB2_FETCHMODE_ASSOC );

foreach ($hoopla_bib_arr as $hoopla_bib) {
	$hoopla_bibs[$hoopla_bib['bnum']] = array('callnum' => $hoopla_bib['callnum']);
}

foreach ($hoopla_bibs as $bnum => $callnum) {

	// Grab MARC details for bib record
	$polaris_db_sql = 'SELECT tag.[BibliographicTagID],[BibliographicRecordID],[Sequence],[TagNumber],[IndicatorOne],[IndicatorTwo],[EffectiveTagNumber],[SubfieldSequence],[Subfield],[Data], CAST([Data] AS VARBINARY(MAX)) AS bindata FROM [Polaris].[Polaris].[BibliographicTags] AS tag LEFT OUTER JOIN [Polaris].[Polaris].[BibliographicSubfields] AS sub ON tag.BibliographicTagID = sub.BibliographicTagID WHERE tag.[BibliographicRecordID] = ' . $bnum . ' ORDER BY [Sequence] ASC, [SubfieldSequence] ASC, [Subfield] ASC';
	$polaris_db_query = $polaris_db->query( $polaris_db_sql );
	$polaris_bib_tags = $polaris_db_query->fetchAll( MDB2_FETCHMODE_ASSOC );
	if ( !count( $polaris_bib_tags ) ) { return FALSE; }

	foreach ( $polaris_bib_tags as $tag_arr ) {
		// Digital content links
		if ( $tag_arr['tagnumber'] == 856 && $tag_arr['subfield'] == 'u' ) {

		  if ( preg_match( '/(mp3|wma)/i', $tag_arr['data'] ) ) {
		    $hoopla_bibs[$bnum]['excerpt_link'] = $tag_arr['data'];
		  } else if ( preg_match( '/(jpg|jpeg|png)/i', $tag_arr['data'] ) ) {
		  	$hoopla_bibs[$bnum]['cover_img'] = $tag_arr['data'];
		  } else {
		    $hoopla_bibs[$bnum]['download_link'] = $tag_arr['data'];
		  }
		}
	}
}

foreach ($hoopla_bibs as $bnum => $bib) {
	if ( preg_match( '/audio/i', $bib['callnum'] ) ) {
		$hoopla_bibs[$bnum]['mat_code'] = $codes['audio'];
	} else if ( preg_match( '/video/i', $bib['callnum'] ) ) {
		$hoopla_bibs[$bnum]['mat_code'] = $codes['video'];
	} else {
		$hoopla_bibs[$bnum]['mat_code'] = 0;
	}

	$scas_sql = 'UPDATE locum_bib_items SET mat_code = "' . $hoopla_bibs[$bnum]['mat_code'] . '", cover_img = "' . $hoopla_bibs[$bnum]['cover_img'] . '", download_link = "' . $hoopla_bibs[$bnum]['download_link'] . '" WHERE bnum = ' . $bnum;
	$scas_db->query($scas_sql);
}


