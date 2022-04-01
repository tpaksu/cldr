<?php
/**
 * CLDR formatter builder
 *
 * @package Currencies
 */

define( 'ABSPATH', true );

require './functions.php';

$l1        = include './locale-info.php';
$l2        = include './output/locale-info.php';
$countries = get_json( './cldr/cldr-localenames-full/main/en/territories.json' );

$diff = array_keys( array_diff_key( $l2, $l1 ) );

echo '| ';
foreach ( $diff as $index => $country_code ) {
	echo '**' . filter_var( $country_code, FILTER_SANITIZE_STRING ) . '**: ' . filter_var( $countries['main']['en']['localeDisplayNames']['territories'][ $country_code ], FILTER_SANITIZE_STRING ) . ' | ';
	if ( 0 === $index % 5 && 0 !== $index ) {
		echo '<br>| ';
	}
}

