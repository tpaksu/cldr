<?php
/**
 * CLDR formatter builder
 *
 * @package Currencies
 */

$global_locale_formats = [];

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

/**
 * Returns the first key
 *
 * @param   array $array   The array to return from.
 *
 * @return  mixed          The first key.
 */
function my_array_key_first( $array ) {
	$keys = array_keys( $array );
	return $keys[0];
}

/**
 * Gets numbers data
 *
 * @param string $locale  Locale.
 *
 * @return object         the numbers data
 */
function get_numbers_data( $locale ) {
	if ( strpos( $locale, '_' ) > 0 ) {
		$parts = explode( '_', $locale );
		if ( strtolower( $parts[0] ) === strtolower( $parts[1] ) ) {
			$locale = strtolower( $parts[0] );
		}
	}
	$path = __DIR__ . '/../cldr/cldr-numbers-full/main/' . strtolower( $locale ) . '/numbers.json';
	if ( file_exists( $path ) ) {
		return get_json( $path );
	}
	return [];
}

/**
 * Gets the currency data
 *
 * @param   string $currency  Currency to query.
 * @param   array  $data       Data.
 *
 * @return  array             The currency data
 */
function get_currency_info( $currency, $data ) {
	return $data['main']['en']['numbers']['currencies'][ $currency ] ?? [];
}

/**
 * Gets the currency formatting
 *
 * @param   array  $data    The format container.
 * @param   string $locale  The related locale.
 *
 * @return  array|null      Currency formats
 */
function get_currency_format( $data, $locale ) {
	$locale = str_replace( '_', '-', $locale );
	if ( isset( $data['main'][ $locale ]['numbers'] ) ) {
		$base    = $data['main'][ $locale ]['numbers'];
		$default = $base['defaultNumberingSystem'];
		$data    = [];
		if ( isset( $base[ 'currencyFormats-numberSystem-' . $default ] ) ) {
			$data = $base[ 'currencyFormats-numberSystem-' . $default ];
		}
		if ( isset( $base[ 'symbols-numberSystem-' . $default ] ) ) {
			$data = array_merge( $data, $base[ 'symbols-numberSystem-' . $default ] );
		}
		return $data;
	}
	return null;
}


/**
 * Returns the associated locales for currency
 *
 * @param   string $currency              Currency to return codes for.
 * @param   array  $currency_locale_data  Locale data.
 *
 * @return  array                         Related locales
 */
function get_codes_for( $currency, $currency_locale_data ) {
	global $global_locale_formats;
	if ( isset( $currency_locale_data[ $currency ] ) && ! empty( $currency_locale_data[ $currency ] ) ) {
		$locale_formats = [];
		foreach ( $currency_locale_data[ $currency ] as $locale ) {
			if ( isset( $global_locale_formats[ $locale ] ) ) {
				$locale_formats[ $locale ] = $global_locale_formats[ $locale ];
				continue;
			}
			$locale_formats[ $locale ]        = get_locale_format( $locale );
			$global_locale_formats[ $locale ] = $locale_formats[ $locale ];
		}
		return $locale_formats;
	}
	return [];
}

/**
 * Get format for locale
 *
 * @param   string $locale  The locale to return it's data.
 *
 * @return  array            The format specifications
 */
function get_locale_format( $locale ) {
	$data = get_numbers_data( $locale );
	return get_currency_format( $data, $locale );
}

/**
 * Builds the initial currency object
 *
 * @param   string $code              The currency code.
 * @param   string $name              Currency name.
 * @param   array  $currency_symbols  Currency symbols data.
 * @param   array  $currency_data     Currency formatting data.
 * @param   array  $locale_data       Locale info.
 *
 * @return  array
 */
function get_currency_object( $code, $name, $currency_symbols, $currency_data, $locale_data ) {
	$currency_object = [
		'name'     => $name,
		'codes'    => get_codes_for( $code, $locale_data ),
		'format'   => get_currency_info( $code, $currency_symbols ),
		'rounding' => $currency_data['supplemental']['currencyData']['fractions'][ $code ] ?? $currency_data['supplemental']['currencyData']['fractions']['DEFAULT'],
	];

	foreach ( $currency_object['codes'] as $lcl => &$value ) {
		$format = get_currency_format( get_numbers_data( $lcl ), $lcl );
		if ( null !== $format ) {
			$value = $format;
			if ( isset( $value['currencySpacing'] ) ) {
				unset( $value['currencySpacing'] );
			}
			if ( isset( $value['short'] ) ) {
				unset( $value['short'] );
			}
		} else {
			$value = null;
		}
	}
	$currency_object['codes'] = array_filter( $currency_object['codes'] );
	return $currency_object;
}

/**
 * Normalizes sub-locale data
 *
 * @param   array $codes  The locales array to normalize.
 *
 * @return  array          Normalized locales
 */
function select_default_locale_for_currency( $codes ) {
	if ( count( $codes ) === 0 ) {
		return [];
	}

	$spec_counts = [];

	foreach ( $codes as $locale => $definition ) {
		$spec_counts[ $locale ] = get_format_key( $locale, $definition );
	}

	if ( count( $spec_counts ) === 0 ) {
		return [];
	}

	$counts = array_count_values( $spec_counts );
	arsort( $counts );
	$max_hash = array_keys( $counts )[0];
	foreach ( $spec_counts as $locale => $hash ) {
		if ( $hash === $max_hash ) {
			return $codes[ $locale ];
		}
	}
	$first = my_array_key_first( $codes );
	return $codes[ $first ];
}

/**
 * Var export with 4 spaces and square brackets
 *
 * @param   mixed $expression  Variable to export.
 * @param   bool  $return      Flag for return or print.
 *
 * @return  string             Exported object representation.
 */
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

/**
 * Gets the language direction by default locale
 *
 * @param   string $country              The country to check for.
 * @param   array  $default_locales      Default locales for countries.
 * @param   array  $language_directions  Language directions for locales.
 *
 * @return  string|null                   The language direction if exists, or "ltr" by default
 */
function get_language_direction( $country, $default_locales, $language_directions ) {
	$default_locale = $default_locales[ $country ];
	return get_locale_direction( $default_locale, $language_directions );

}

/**
 * Gets the direction of the locale.
 *
 * @param   string $locale               The locale to check for.
 * @param   array  $language_directions  The locale directions array.
 *
 * @return  string                       The direction
 */
function get_locale_direction( $locale, $language_directions ) {
	while ( true ) {
		if ( strpos( $locale, '-' ) > 0 ) {
			$parts = explode( '-', $locale );
			array_pop( $parts );
			$locale = implode( '-', $parts );
		}
		if ( array_key_exists( $locale, $language_directions ) ) {
			return $language_directions[ $locale ];
		}
		if ( strpos( $locale, '-' ) > 0 ) {
			continue;
		}
		return 'ltr';
	}
}

/**
 * Gets the hash key for grouping format data
 *
 * @param   string $language Language.
 * @param   array  $data     Currency formatting data.
 *
 * @return  string         The key of the format
 */
function get_format_key( $language, $data ) {

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
				echo 'missing decimal delimiter case: ' . $language . ' ' . dechex( ord( $data['decimal_sep'] ) ) . ' (' . $data['decimal_sep'] . ') ' . "\n";
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
				echo 'missing thousand delimiter case: ' . $language . ' ' . dechex( ord( $data['thousand_sep'] ) ) . ' (' . $data['thousand_sep'] . ') ' . "\n";
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

/**
 * Fixes the non-ASCII text to ASCII equivalents
 *
 * @param   string $formats  String to fix.
 *
 * @return  string            Fixed string
 */
function fix_formats( $formats ) {
	$formats = explode( ';', $formats )[0];
	$formats = str_replace( "\xC2\xA4", 'o', $formats );
	$formats = str_replace( "\xE2\x80\xAF", ' ', $formats );
	$formats = str_replace( "\xC2\xA0", ' ', $formats );
	$formats = str_replace( "\xD9\xAC", '.', $formats );
	$formats = str_replace( "\xD9\xAb", ',', $formats );
	$formats = str_replace( "\xE2\x80\x99", "'", $formats );
	$formats = str_replace( "\xE2\x80\x8F", '', $formats );
	$formats = str_replace( "\xE2\x80\x8E", '', $formats );

	return $formats;
}

/**
 * Completes locales without country suffixes from defaultContent data
 *
 * @param   string $key   The locale to complete.
 * @param   array  $list  The default locales list.
 *
 * @return  string        The completed locale.
 */
function complete_country_for_locale( $key, $list ) {
	$matches = array_values(
		array_filter(
			$list,
			function( $locale ) use ( $key ) {
				return strpos( $locale, $key . '-' ) === 0;
			}
		)
	);
	if ( count( $matches ) > 0 ) {
		$parts  = explode( '-', $matches[0] );
		$second = end( $parts );
		return $key . '_' . $second;
	}
	return $key;
}
