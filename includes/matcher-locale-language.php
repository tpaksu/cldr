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

$locale_data      = get_json( __DIR__ . '/../json/default-locales.json' );
$language_data    = get_json( __DIR__ . '/../json/default-languages.json' );
$locale_languages = [];

foreach ( $locale_data as $country => $locale_array ) {
	$languages = $language_data[ $country ];
	foreach ( $locale_array as $_locale ) {
		if ( isset( $languages[ $_locale ] ) ) {
			$locale_languages[ $_locale ] = $_locale;
			unset( $languages[ $_locale ] );
			continue;
		}
		$locale_with_country = $_locale . '_' . $country;
		if ( isset( $languages[ $locale_with_country ] ) ) {
			$locale_languages[ $_locale ] = $locale_with_country;
			unset( $languages[ $locale_with_country ] );
			continue;
		}
		$matching = array_filter( $languages, fn( $language) => 0 === strpos( $language, $_locale ), ARRAY_FILTER_USE_KEY );
		if ( 1 === count( $matching ) ) {
			$first_matching               = array_keys( $matching )[0];
			$locale_languages[ $_locale ] = $first_matching;
			unset( $languages[ $first_matching ] );
			continue;
		}
	}
}

file_put_contents( './json/locale-languages.json', json_encode( $locale_languages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );

return $locale_languages;
