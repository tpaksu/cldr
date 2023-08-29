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

$currency_data = get_json( './cldr/cldr-core/supplemental/currencyData.json' );
$countries     = [];
foreach ( $currency_data['supplemental']['currencyData']['region'] as $country => $data ) {
	foreach ( $data as $currencies_array ) {
		if ( ! isset( $countries[ $country ] ) ) {
			$countries[ $country ] = [];
		}
		foreach ( $currencies_array as $currency => $currency_info ) {
			if ( ! isset( $currency_info['_to'] ) ) {
				$countries[ $country ][] = $currency;
				break;
			}
		}
	}
}

ksort( $countries );
file_put_contents( './json/country-currencies.json', json_encode( $countries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );

// echo '<pre>' . print_r( $countries, true );

return $countries;
