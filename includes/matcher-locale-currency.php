<?php
/**
 * CLDR formatter builder
 *
 * @package Currencies
 */

if ( ! function_exists( 'get_json' ) ) {
	/**
	 * Test
	 *
	 * @param string $path  Path to open.
	 *
	 * @return object         File contents.
	 */
	function get_json( $path ) {
		/* phpcs:disable */
		return json_decode( file_get_contents( $path ), true );
		/* phpcs:enable */
	}
}

$locale_data   = require 'matcher-country-locale.php';
$currency_data = require 'matcher-country-currency.php';


$data = [];
foreach ( $locale_data as $country => $locales ) {
	foreach ( $locales as $locale_single ) {
		if ( ! isset( $data[ $locale_single ] ) ) {
			$data[ $locale_single ] = [];
		}
		foreach ( $currency_data[ $country ] as $currency ) {
			$data[ $locale_single ][] = $currency;
		}
	}
}

$data['zh']    = [ 'CNY' ];
$data['zh_CN'] = [ 'CNY' ];
//$data['kn']    = [ 'KHR' ];
// $data['en-GG']   = [ 'GGP' ];
$data['dv'] = [ 'MVR' ];
//$data['sr']      = [ 'RSD' ];
$data['zh_Hant'] = [ 'TWD' ];
$data['es_VE']   = [ 'VES', 'VEF' ];

ksort( $data );

$currency_locales = [];
foreach ( $data as $_locale => $currencies ) {
	foreach ( $currencies as $currency ) {
		if ( ! isset( $currency_locales[ $currency ] ) ) {
			$currency_locales[ $currency ] = [];
		}
		$currency_locales[ $currency ][] = $_locale;
	}
}

ksort( $currency_locales );

file_put_contents( './json/locale-currencies.json', json_encode( $data, JSON_PRETTY_PRINT ) );
file_put_contents( './json/currency-locales.json', json_encode( $currency_locales, JSON_PRETTY_PRINT ) );

return $data;
