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

$locale_data   = include './country-locale-matcher.php';
$currency_data = include './country-currency-matcher.php';


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
$data['zh-CN'] = [ 'CNY' ];
//$data['kn']    = [ 'KHR' ];
// $data['en-GG']   = [ 'GGP' ];
$data['dv']      = [ 'MVR' ];
//$data['sr']      = [ 'RSD' ];
$data['zh-Hant'] = [ 'TWD' ];
$data['es-VE']   = [ 'VES', 'VEF' ];

ksort( $data );
file_put_contents( './json/locale-currencies.json', json_encode( $data, JSON_PRETTY_PRINT ) );

return $data;
