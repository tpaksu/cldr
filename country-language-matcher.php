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

$territory_data = get_json( './cldr/cldr-core/supplemental/territoryInfo.json' );

$countries = [];
$defaults  = [];
foreach ( $territory_data['supplemental']['territoryInfo'] as $country => $data ) {
	$countries[ $country ] = [];
	if ( isset( $data['languagePopulation'] ) ) {
		foreach ( $data['languagePopulation'] as $language => $language_data ) {
			if ( isset( $language_data['_officialStatus'] ) ) {
				$countries[ $country ][ str_replace( '_', '-', $language ) . '-' . strtoupper( $country ) ] = floatval( $language_data['_populationPercent'] );
			}
		}
		arsort( $countries[ $country ] );
		$defaults[ $country ]  = array_slice( $countries[ $country ], 0, 3 );
		$countries[ $country ] = array_keys( $countries[ $country ] );
	}
}
$countries = array_filter(
	$countries,
	function( $c ) {
		return ! empty( $c );
	}
);

ksort( $countries );
$defaults = array_filter( $defaults );
// echo '<pre>' . print_r( $countries, true );
file_put_contents( './json/country-languages.json', json_encode( $countries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );
file_put_contents( './json/default-languages.json', json_encode( $defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );

return $countries;
