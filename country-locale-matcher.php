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

$language_data   = include './country-language-matcher.php';
$locales         = get_json( './cldr/cldr-core/availableLocales.json' );
$locales         = $locales['availableLocales']['full'];
$default_content = get_json( './cldr/cldr-core/defaultContent.json' );

$data = [];
foreach ( $language_data as $country => $languages ) {
	$data[ $country ] = [];
	foreach ( $languages as $language ) {
		if ( in_array( $language, $locales, true ) ) {
			$data[ $country ][] = $language;
		} elseif ( in_array( $language, $default_content['defaultContent'], true ) ) {
			$info = explode( '-', $language );
			if ( end( $info ) === $country ) {
				$data[ $country ][] = $info[0];
			}
		}
	}
}

$default_languages = get_json( './json/default-languages.json' );
$default_locales   = [];

foreach ( $default_languages as $country => $languages ) {
	foreach ( $languages as $language => $population ) {
		$parts        = explode( '-', $language );
		$country_code = array_pop( $parts );
		$first_part   = implode( '-', $parts );
		if ( in_array( $language, $locales, true ) ) {
			$default_locales[ $country ] = $language;
			break;
		} elseif ( in_array( $first_part, $locales, true ) ) {
			$default_locales[ $country ] = $first_part;
			break;
		}
	}
}

$default_locales['MV'] = 'dv';


ksort( $data );
ksort( $default_locales );
// echo '<pre>' . print_r( $data, true );
file_put_contents( './json/country-locales.json', json_encode( $data, JSON_PRETTY_PRINT ) );
file_put_contents( './json/default-locales.json', json_encode( $default_locales, JSON_PRETTY_PRINT ) );

return $data;
