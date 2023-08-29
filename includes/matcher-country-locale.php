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
	 * @throws Exception      When file doesn't exist.
	 */
	function get_json( $path ) {
		/* phpcs:disable */
		if(!file_exists($path)){
			throw new Exception("File doesn't exist: $path");
		}
		return json_decode( file_get_contents( $path ), true );
		/* phpcs:enable */
	}
}

$language_data      = require 'matcher-country-language.php';
$cldr_language_data = get_json( './cldr/cldr-core/supplemental/languageData.json' );
$available_locales  = get_json( './cldr/cldr-core/availableLocales.json' );
$available_locales  = $available_locales['availableLocales']['full'];
$cldr_language_data = $cldr_language_data['supplemental']['languageData'];

$data         = [];
$replacements = [];
foreach ( $language_data as $country => $languages ) {
	$data[ $country ] = [];
	foreach ( $languages as $language ) {
		// Language contains a script!
		if ( 0 < strpos( $language, '_' ) ) {
			$parts             = explode( '_', $language );
			$language_w_script = $parts[0];
			$script            = $parts[1];
			if ( isset( $cldr_language_data[ $language_w_script ]['_territories'] ) && isset( $cldr_language_data[ $language_w_script ]['_scripts'] ) ) {
				if ( in_array( $country, $cldr_language_data[ $language_w_script ]['_territories'], true )
					&& in_array( $script, $cldr_language_data[ $language_w_script ]['_scripts'], true ) ) {
					$data[ $country ][]                    = $language_w_script . '_' . $script . '_' . $country;
					$replacements[ $country ][ $language ] = $language_w_script . '_' . $script . '_' . $country;
				}
			} else {
				$data[ $country ][]                    = $language;
				$replacements[ $country ][ $language ] = $language;
			}
		} else {
			if ( isset( $cldr_language_data[ $language ]['_territories'] ) ) {
				if ( in_array( $country, $cldr_language_data[ $language ]['_territories'], true ) ) {
					$data[ $country ][]                    = $language . '_' . $country;
					$replacements[ $country ][ $language ] = $language . '_' . $country;
				}
			} else {
				$data[ $country ][]                    = $language;
				$replacements[ $country ][ $language ] = $language;
			}
		}
	}
}

$default_languages = get_json( './json/default-languages.json' );
$default_locales   = [];

foreach ( $default_languages as $country => $languages ) {
	foreach ( $languages as $language => $population ) {
		$default_locales[ $country ][ $replacements[ $country ][ $language ] ] = $population;
	}
}

$default_locales['MV'] = 'dv';


ksort( $data );
ksort( $default_locales );
// echo '<pre>' . print_r( $data, true );
file_put_contents( './json/country-locales.json', json_encode( $data, JSON_PRETTY_PRINT ) );
file_put_contents( './json/default-locales.json', json_encode( $default_locales, JSON_PRETTY_PRINT ) );
return $data;
