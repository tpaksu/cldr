<?php
/**
 * CLDR formatter builder
 *
 * @package Currencies
 * @phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
 */

function get_json( $path ) {
	/* phpcs:disable */
	return json_decode( file_get_contents( $path ), true );
	/* phpcs:enable */
}

function var_export_override( $expression, $return = false ) {
	$export = var_export( $expression, true ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
	$export = preg_replace( '/^([ ]*)(.*)/m', '$1$1$2', $export );
	$array  = preg_split( "/\r\n|\n|\r/", $export );
	$array  = preg_replace( [ '/\s*array\s\($/', '/\)(,)?$/', '/\s=>\s$/' ], [ null, ']$1', ' => [' ], $array );
	$array  = preg_replace_callback(
		"/('.+?') => (.+),/",
		function( $m ) {
			return str_pad( $m[1], 17, '*' ) . '=> ' . $m[2] . ',';
		},
		$array
	);
	$export = join( PHP_EOL, array_filter( [ '[' ] + $array ) );
	$export = str_replace( '    ', "\t", $export );
	$export = str_replace( '*', ' ', $export );
	if ( (bool) $return ) {
		return $export;
	} else {
		echo $export;
	}
}

function get_current_currency( $country, $currency_history ) {
	$currencies = [];
	foreach ( $currency_history as $currency_history_array ) {
		foreach ( $currency_history_array as $currency => $currency_history_item ) {
			if ( ! isset( $currency_history_item['_to'] ) && ! isset( $currency_history_item['_tender'] ) ) {
				$currencies[] = $currency;
			}
		}
	}

	// Select the default currency for multiple currency countries.
	switch ( $country ) {
		case 'BT':
			return [ 'BTN' ];
		case 'CU':
			return [ 'CUP' ];
		case 'HT':
			return [ 'HTG' ];
		case 'LS':
			return [ 'LSL' ];
		case 'NA':
			return [ 'NAD' ];
		case 'PA':
			return [ 'PAB' ];
		case 'PS':
			return [ 'ILS' ];
	}

	return $currencies;
}

function str_starts_with( $haystack, $needle ) {
	return 0 === strpos( $haystack, $needle, 0 );
}

function str_ends_with( $haystack, $needle ) {
	return substr( $haystack, -strlen( $needle ) ) === $needle;
}

function get_country_languages( $country, $language_data ) {
	$country_languages = [];
	if ( isset( $language_data['supplemental']['territoryInfo'][ $country ]['languagePopulation'] ) && is_array( $language_data['supplemental']['territoryInfo'][ $country ]['languagePopulation'] ) ) {
		if ( 1 === count( $language_data['supplemental']['territoryInfo'][ $country ]['languagePopulation'] ) ) {
			$language            = array_keys( $language_data['supplemental']['territoryInfo'][ $country ]['languagePopulation'] )[0];
			$country_languages[] = $language;
		} else {
			foreach ( $language_data['supplemental']['territoryInfo'][ $country ]['languagePopulation'] as $language => $data ) {

				if ( isset( $data['_officialStatus'] ) && in_array( $data['_officialStatus'], [ 'official', 'de_facto_official', 'official_regional' ], true ) ) {
					$country_languages[] = $language;
				}
			}
		}
	}
	sort( $country_languages );
	return $country_languages;
}

function fix_locale( $_locale, $country ) {
	$_locale = str_replace( '-', '_', $_locale );
	$_locale = str_replace( [ '_Hant', '_Arab', '_Cyrl', '_Deva', '_Latn', '_Mong' ], [ '-Hant', '-Arab', '-Cyrl', '-Deva', '-Latn', '-Mong' ], $_locale );
	$_locale = false === strpos( $_locale, '_' ) ? $_locale . '_' . $country : $_locale;
	$_locale = str_replace( '-', '_', $_locale );
	return $_locale;
}

function get_country_locales( $country, $languages, $locales_list ) {
	$selected_locales = [];
	foreach ( $locales_list as $locale ) {
		if ( 'und' === $locale ) {
			continue;
		}
		if ( strpos( $locale, "-$country" ) > 0 ) {
			$split = explode( '-', $locale );
			array_pop( $split );
			$first_part = implode( '-', $split );
			if ( in_array( $first_part, $languages, true ) ) {
				$languages[ array_search( $first_part, $languages, true ) ] = '--';
				if ( false !== array_search( $first_part, $selected_locales, true ) ) {
					unset( $selected_locales[ array_search( $first_part, $selected_locales, true ) ] );
				}
				$selected_locales[] = $locale;
			}
		} elseif ( in_array( $locale, $languages, true ) ) {
			$selected_locales[] = $locale;
		} elseif ( in_array( str_replace( '-', '_', $locale ), $languages, true ) ) {
			$selected_locales[] = $locale;
		}
	}

	return array_values( $selected_locales );
}

function get_country_default_locale( $country, $locales, $language_data ) {
	// Default language is defined as most used in writing in
	// https://cldr.unicode.org/development/development-process/design-proposals/language-data-consistency

	if ( ! count( $locales ) ) {
		return null;
	}

	if ( 1 === count( $locales ) ) {
		return $locales[0];
	}

	$country_language_data = $language_data['supplemental']['territoryInfo'][ $country ]['languagePopulation'];
	if ( 1 === count( $country_language_data ) ) {
		return array_key_first( $country_language_data ); // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.array_key_firstFound
	}

	$official_languages = array_filter(
		$country_language_data,
		function( $language ) {
			return isset( $language['_officialStatus'] ) && ( 'official' === $language['_officialStatus'] || 'de_facto_official' === $language['_officialStatus'] ) &&
			( ! isset( $language['_writingPercent'] ) || intval( $language['_writingPercent'] ) > 50 );
		}
	);

	if ( 1 === count( $official_languages ) ) {
		$language = array_key_first( $official_languages ); // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.array_key_firstFound
		if ( in_array( $language, $locales, true ) ) {
			return $language;
		}
		$locale = array_filter(
			$locales,
			function( $locale ) use ( $language ) {
				return str_starts_with( $locale, "$language-" );
			}
		);
		if ( 1 === count( $locale ) ) {
			return array_values( $locale )[0];
		}
	}

	if ( 0 < count( $official_languages ) ) {
		uasort(
			$official_languages,
			function( $lang1, $lang2 ) {
				return intval( $lang1['_populationPercent'] ) > intval( $lang2['_populationPercent'] ) ? -1 : 1;
			}
		);

		if ( 'SD' === $country ) {
			return 'ar-SD';
		}

		foreach ( $official_languages as $default_language => $data ) {
			$matching_locales_1 = array_filter(
				$locales,
				function( $locale ) use ( $default_language ) {
					return $locale === str_replace( '_', '-', $default_language );
				}
			);
			if ( count( $matching_locales_1 ) ) {
				return array_values( $matching_locales_1 )[0];
			}
			$matching_locales_1 = array_filter(
				$locales,
				function( $locale ) use ( $default_language, $country ) {
					return $locale === str_replace( '_', '-', $default_language ) . '-' . $country;
				}
			);
			if ( count( $matching_locales_1 ) ) {
				return array_values( $matching_locales_1 )[0];
			}
			$matching_locales_2 = array_filter(
				$locales,
				function( $locale ) use ( $default_language ) {
					return str_starts_with( $locale, str_replace( '_', '-', $default_language ) );
				}
			);
			if ( count( $matching_locales_2 ) ) {
				return array_values( $matching_locales_2 )[0];
			}
		}
	}

	return $official_languages;
}

function get_locale_format( $locale, $currency, $currency_data ) {
	$path    = __DIR__ . '/cldr/cldr-numbers-full/main/' . strtolower( str_replace( '_', '-', $locale ) ) . '/numbers.json';
	$data    = get_json( $path );
	$base    = $data['main'][ $locale ]['numbers'];
	$default = $base['defaultNumberingSystem'];
	$data    = [];
	if ( isset( $base[ 'currencyFormats-numberSystem-' . $default ] ) ) {
		$data = $base[ 'currencyFormats-numberSystem-' . $default ];
	}
	if ( isset( $base[ 'symbols-numberSystem-' . $default ] ) ) {
		$data = array_merge( $data, $base[ 'symbols-numberSystem-' . $default ] );
	}

	return summarize_format( $data, $currency, $currency_data );
}

function fix_formats( $formats ) {
	$formats = preg_replace( "/\xC2\xA4/", 'o', $formats );
	$formats = preg_replace( "/\xE2\x80\xAF/", ' ', $formats );
	$formats = preg_replace( "/\xC2\xA0/", ' ', $formats );
	$formats = preg_replace( "/\xD9\xAC/", '.', $formats );
	$formats = preg_replace( "/\xD9\xAb/", ',', $formats );
	$formats = preg_replace( "/\xE2\x80\x99/", "'", $formats );
	$formats = preg_replace( "/\xE2\x80\x8F/", '', $formats );
	$formats = preg_replace( "/\xE2\x80\x8E/", '', $formats );
	$formats = preg_replace( "/\xD8\x9C/", '', $formats );

	return $formats;
}

function parse_amount_format( $format_string ) {

	if ( str_starts_with( $format_string, 'o ' ) ) {
			$currency_pos = 'left_space';
	} elseif ( str_starts_with( $format_string, 'o' ) ) {
			$currency_pos = 'left';
	} elseif ( str_ends_with( $format_string, ' o' ) ) {
			$currency_pos = 'right_space';
	} elseif ( str_ends_with( $format_string, 'o' ) ) {
			$currency_pos = 'right';
	} else {
		echo "Undefined currency pos for $format_string\n\n";
		$currency_pos = 'undefined';
	}
	return [
		'has_space'    => str_ends_with( $currency_pos, '_space' ),
		'currency_pos' => $currency_pos,
	];
}

function summarize_format( $format, $currency, $currency_data ) {
	$amount_formats                  = explode( ';', $format['accounting'] );
	$amount_formats_without_currency = explode( ';', $format['accounting-noCurrency'] );
	$positive_format                 = fix_formats( $amount_formats[0] );
	$negative_format                 = isset( $amount_formats[1] ) ? fix_formats( $amount_formats[1] ) : "-$positive_format";
	$format_info                     = parse_amount_format( $positive_format, fix_formats( $amount_formats_without_currency[0] ) );
	$num_decimals                    = $currency_data['supplemental']['currencyData']['fractions'][ $currency ]['_cashDigits']
	?? $currency_data['supplemental']['currencyData']['fractions'][ $currency ]['_digits']
	?? $currency_data['supplemental']['currencyData']['fractions']['DEFAULT']['_digits']
	?? null;

	return [
		'decimal_sep'     => fix_formats( $format['decimal'] ),
		'thousand_sep'    => fix_formats( $format['group'] ),
		'has_space'       => $format_info['has_space'],
		'currency_pos'    => $format_info['currency_pos'],
		'negative_format' => $negative_format,
		'num_decimals'    => $num_decimals,
	];
}

function get_locale_direction( $locale ) {
	$path      = __DIR__ . '/cldr/cldr-misc-full/main/' . strtolower( str_replace( '_', '-', $locale ) ) . '/layout.json';
	$data      = get_json( $path );
	$direction = $data['main'][ $locale ]['layout']['orientation']['characterOrder'];
	return 'right-to-left' === $direction ? 'rtl' : 'ltr';
}

function fix_combined_info( $info ) {
	$info['BY']['short_symbol'] = 'р.';
	return $info;
}

// Step 1: Get country, currency, language and locales together.

$currency_data        = get_json( __DIR__ . '/cldr/cldr-core/supplemental/currencyData.json' );
$language_data        = get_json( __DIR__ . '/cldr/cldr-core/supplemental/territoryInfo.json' );
$available_locales    = get_json( __DIR__ . '/cldr/cldr-core/availableLocales.json' );
$unit_preference_data = get_json( __DIR__ . '/cldr/cldr-core/supplemental/unitPreferenceData.json' );
$locales_list         = $available_locales['availableLocales']['full'];

$country_info = [];
foreach ( $currency_data['supplemental']['currencyData']['region'] as $country => $currency_info ) {
	$country_info[ $country ]['currencies'] = get_current_currency( $country, $currency_info );
	if ( 1 < count( $country_info[ $country ]['currencies'] ) ) {
		echo "Multiple currencies found for $country\n";
	}

	$country_info[ $country ]['weight_unit']    = $unit_preference_data['supplemental']['unitPreferenceData']['mass']['default'][ $country ][1]['unit'] ?? $unit_preference_data['supplemental']['unitPreferenceData']['mass']['default']['001'][1]['unit'];
	$country_info[ $country ]['dimension_unit'] = $unit_preference_data['supplemental']['unitPreferenceData']['length']['default'][ $country ][1]['unit'] ?? $unit_preference_data['supplemental']['unitPreferenceData']['length']['default']['001'][2]['unit'];

	$weight = &$country_info[ $country ]['weight_unit'];
	if ( 'kilogram' === $weight ) {
		$weight = 'kg';
	} elseif ( 'pound' === $weight ) {
		$weight = 'oz';
	}

	$dimension = &$country_info[ $country ]['dimension_unit'];
	if ( 'centimeter' === $dimension ) {
		$dimension = 'cm';
	} elseif ( 'inches' === $dimension ) {
		$dimension = 'in';
	}

	$country_info[ $country ]['languages']      = get_country_languages( $country, $language_data );
	$country_info[ $country ]['locales']        = get_country_locales( $country, $country_info[ $country ]['languages'], $locales_list );
	$country_info[ $country ]['default_locale'] = get_country_default_locale( $country, $country_info[ $country ]['locales'], $language_data );
}

file_put_contents( './json2/0-country-info.json', json_encode( $country_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */

// Step 2: Import formats for each locale.
$format_cache = [];
foreach ( $country_info as $country => &$info ) {
	$info['locale_formats'] = [];
	foreach ( $info['locales'] as $_locale ) {
		foreach ( $info['currencies'] as $currency ) {
			if ( isset( $format_cache[ $_locale ][ $currency ] ) ) {
				$info['locale_formats'][ $_locale ][ $currency ] = $format_cache[ $_locale ][ $currency ];
				continue;
			}
			$info['locale_formats'][ $_locale ][ $currency ]              = get_locale_format( $_locale, $currency, $currency_data );
			$info['locale_formats'][ $_locale ][ $currency ]['direction'] = get_locale_direction( $_locale );
			$format_cache[ $_locale ][ $currency ]                        = $info['locale_formats'][ $_locale ][ $currency ];
		}
	}

	if ( isset( $info['locale_formats'][ $info['default_locale'] ] ) ) {
		$info['default_format']            = $info['locale_formats'][ $info['default_locale'] ];
		$info['locale_formats']['default'] = $info['locale_formats'][ $info['default_locale'] ];
	} else {
		$info['default_format'] = '';
	}
}

file_put_contents( './json2/1-locale-formats.json', json_encode( $country_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */

$english_numbers    = get_json( __DIR__ . '/cldr/cldr-numbers-full/main/en/currencies.json' )['main']['en']['numbers'];
$english_currencies = $english_numbers['currencies'];

// Step 3: Group by currencies
$combined_info = [];
foreach ( $country_info as $country => &$info ) {
	if ( empty( $info['currencies'] ) ) {
		continue;
	}
	$currency = $info['currencies'][0];
	if ( empty( $info['default_format'] ) ) {
		continue;
	}
	$default_format            = $info['default_format'][ $currency ];
	$combined_info[ $country ] = [
		'currency_code'  => $currency,
		'currency_pos'   => $default_format['currency_pos'],
		'thousand_sep'   => $default_format['thousand_sep'] ?? '.',
		'decimal_sep'    => $default_format['decimal_sep'] ?? ',',
		'num_decimals'   => intval( $default_format['num_decimals'] ) ?? 2,
		'weight_unit'    => $info['weight_unit'],
		'dimension_unit' => $info['dimension_unit'],
		'direction'      => $default_format['direction'],
		'default_locale' => fix_locale( $info['default_locale'], $country ),
		'name'           => $english_currencies[ $currency ]['displayName'],
		'singular'       => $english_currencies[ $currency ]['displayName-count-one'],
		'plural'         => $english_currencies[ $currency ]['displayName-count-other'],
		'short_symbol'   => isset( $english_currencies[ $currency ]['symbol-alt-narrow'] ) ? $english_currencies[ $currency ]['symbol-alt-narrow'] : null,
		'locales'        => "\$locales['$currency']",
	];
}

function combine_format( $data, $language ) {

	$key = [];
	if ( ! isset( $data['currency_pos'] ) ) {
		echo "Missing currency_pos for $language\n";
	} else {
		switch ( $data['currency_pos'] ) {
			case 'left':
				$key[] = 'lx';
				break;
			case 'right':
				$key[] = 'rx';
				break;
			case 'left_space':
				$key[] = 'ls';
				break;
			case 'right_space':
				$key[] = 'rs';
				break;
			default:
				echo 'invalid currency pos case: ' . $language . ' ' . dechex( ord( $data['currency_pos'] ) ) . ' (' . $data['currency_pos'] . ') ' . "\n";
				break;
		}
	}

	if ( ! isset( $data['decimal_sep'] ) ) {
		echo "Missing decimal sep for $language\n";
	} else {
		switch ( $data['decimal_sep'] ) {
			case '.':
				$key[] = 'dot';
				break;
			case ',':
				$key[] = 'comma';
				break;
			case ' ':
				$key[] = 'space';
				break;
			case "'":
				$key[] = 'apos';
				break;
			default:
				echo 'invalid decimal delimiter case: ' . $language . ' ' . dechex( ord( $data['decimal_sep'] ) ) . ' (' . $data['decimal_sep'] . ') ' . "\n";
				break;
		}
	}
	if ( ! isset( $data['thousand_sep'] ) ) {
		echo "Missing thousand sep for $language\n";
	} else {
		switch ( $data['thousand_sep'] ) {
			case '.':
				$key[] = 'dot';
				break;
			case ',':
				$key[] = 'comma';
				break;
			case ' ':
			case '  ':
				$key[] = 'space';
				break;
			case "'":
				$key[] = 'apos';
				break;
			default:
				echo 'invalid thousand delimiter case: ' . $language . ' ' . dechex( ord( $data['thousand_sep'] ) ) . ' (' . $data['thousand_sep'] . ') ' . "\n";
				break;
		}
	}

	if ( ! isset( $data['direction'] ) ) {
		echo "Missing direction for $language\n";
	} else {
		$key[] = $data['direction'];
	}

	return implode( '_', $key );
}

$available_formats = [];
$country_formats   = [];
foreach ( $country_info as $country => $info ) {
	foreach ( $info['locale_formats'] as $_locale => $locale_info ) {
		$currency = $info['currencies'][0];
		$format   = $locale_info[ $currency ];
		if ( 'default' !== $_locale ) {
			$_locale = fix_locale( $_locale, $country );

		}
		$combined_key = combine_format( $format, $_locale );
		if ( ! isset( $available_formats[ $combined_key ] ) ) {
			$available_formats[ $combined_key ] = [
				'thousand_sep' => $format['thousand_sep'],
				'decimal_sep'  => $format['decimal_sep'],
				'direction'    => $format['direction'],
				'currency_pos' => $format['currency_pos'],
			];
		}
		$country_formats[ $currency ][ $_locale ] = "\$global_formats['$combined_key']";
	}
}

$combined_info = fix_combined_info( $combined_info );
ksort( $combined_info );
ksort( $country_formats );
ksort( $available_formats );

file_put_contents( './json2/2-locale-info.json', json_encode( $combined_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */
file_put_contents( './json2/3-currency-info.json', json_encode( $country_formats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */
file_put_contents( './json2/4-available-formats.json', json_encode( $available_formats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */


$currency_locale_data = '<?php
/**
 * Currency formatting information
 *
 * @package WooCommerce\Payments\i18n
 * @version 3.5.0
 */

defined( \'ABSPATH\' ) || exit;

$global_formats = ' . var_export_override( $available_formats, true ) . ';

return ' . var_export_override( $country_formats, true ) . ';
';

$currency_locale_data = str_replace( "'\$global_formats[\\'", '$global_formats[\'', $currency_locale_data );
$currency_locale_data = str_replace( "\\']'", '\']', $currency_locale_data );
$currency_locale_data = str_replace( 'NULL', 'null', $currency_locale_data );
$currency_locale_data = str_replace( "'%%", '', $currency_locale_data );
$currency_locale_data = str_replace( "%%'", '', $currency_locale_data );
/* phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents */
file_put_contents( './output2/currency-info.php', $currency_locale_data );


$country_locale_data = '<?php
/**
 * Locales information
 *
 * @package WooCommerce\Payments\i18n
 * @version 3.5.0
 */

defined( \'ABSPATH\' ) || exit;

$locales = include WCPAY_ABSPATH . \'/i18n/currency-info.php\';

return ' . var_export_override( $combined_info, true ) . ';
';

$country_locale_data = str_replace( "'\$locales[\\'", '$locales[\'', $country_locale_data );
$country_locale_data = str_replace( "\\']'", '\']', $country_locale_data );
$country_locale_data = str_replace( 'NULL', 'null', $country_locale_data );
$country_locale_data = str_replace( '`', "'", $country_locale_data );
/* phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents */
file_put_contents( './output2/locale-info.php', $country_locale_data );

exec( 'phpcbf ./output2/*.php' ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec
