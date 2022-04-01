<pre><?php
/**
 * CLDR formatter builder
 *
 * @package Currencies
 */

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
	$path = __DIR__ . '/cldr/cldr-numbers-full/main/' . strtolower( $locale ) . '/numbers.json';
	if ( file_exists( $path ) ) {
		return get_json( $path );
	}
	return [];
}

/**
 * [build_locale_currency_matcher description]
 *
 * @return  array                  [return description].
 */
function build_locale_currency_matcher() {

	$locale_data = array_reduce(
		\ResourceBundle::getLocales( '' ),
		function ( array $currencies, string $locale ) {
			$locale_key                                     = strpos( $locale, '_' ) > 0 ? $locale : $locale . '_' . strtoupper( $locale );
			$currencies[ str_replace( '_', '-', $locale ) ] = \NumberFormatter::create(
				$locale_key,
				\NumberFormatter::CURRENCY
			)->getTextAttribute( \NumberFormatter::CURRENCY_CODE );

			return $currencies;
		},
		[]
	);

	$locale_data['vi']    = 'VND';
	$locale_data['ja']    = 'JPY';
	$locale_data['bn']    = 'BDT';
	$locale_data['zh']    = 'CNY';
	$locale_data['zh-CN'] = 'CNY';
	$locale_data['cs']    = 'CZK';
	$locale_data['kn']    = 'KHR';
	$locale_data['ko']    = 'KRW';
	$locale_data['lo']    = 'LAK';
	$locale_data['ko']    = 'KRW';
	$locale_data['sq']    = 'ALL';
	$locale_data['sq']    = 'ALL';

	$locale_data['ka']    = 'GEL';
	$locale_data['en-GG'] = 'GGP';
	$locale_data['en-LS'] = 'LSL';
	$locale_data['my']    = 'MMK';
	$locale_data['dv']    = 'MVR';
	$locale_data['ne']    = 'NPR';
	$locale_data['sr']    = 'RSD';
	$locale_data['tg']    = 'TJS';
	$locale_data['tk']    = 'TMT';
	$locale_data['zh-TW'] = 'TWD';
	$locale_data['es-VE'] = 'VEF';

	return array_filter(
		$locale_data,
		function ( $data ) {
			return 'XXX' !== $data;
		}
	);
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
	return $data['main']['en']['numbers']['currencies'][ $currency ];
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

function dd( $item ) {
	die( var_dump( $item ) );
}
/**
 * Default locale selection
 *
 * @param   array  $codes    The locales array.
 * @param   string $country  The country to find out locale for.
 *
 * @return  array            The most preferable locale
 */
function select_default_locale_for_country( $codes, $country, $default_content ) {

	// If there are no locales for that country, what will we do?
	if ( 0 === count( $codes ) ) {
		return [];
	}

	// If there's only one, return it.
	if ( 1 === count( $codes ) ) {
		return $codes;
	}

	$defaults = $default_content['defaultContent'];

	$default = array_values(
		array_filter(
			$defaults,
			function( $code ) use ( $country ) {
				// echo "$country => $code -> " . strtolower( $country ) . '-' . '-> ' . strpos( $code, strtolower( $country ) . '-' ) . "\n";
				return strpos( $code, strtolower( $country ) . '-' ) === 0;
			}
		)
	);

	// print_r( $default );

	if ( count( $default ) === 1 && array_key_exists( $default[0], $codes ) ) {
		// print_r( [ $country, $default[0] ] );
		return [ $default[0] => $codes[ $default[0] ] ];
	}

	// Filter by matching country tag.
	$response = array_filter(
		$codes,
		function( $code ) use ( $country ) {
			return strtolower( $code ) === strtolower( $country );
		},
		ARRAY_FILTER_USE_KEY
	);

	// if there's one, return it.
	if ( 1 === count( $response ) ) {
		return $response;
	}

	// Filter by matching country tag in the locale info.
	$response = array_filter(
		$codes,
		function( $code ) use ( $country ) {
			return substr( $code, 0, 3 ) === strtolower( $country ) . '-';
		},
		ARRAY_FILTER_USE_KEY
	);

	// if there's one, return it.
	if ( 1 === count( $response ) ) {
		return $response;
	}

	if ( 1 < count( $response ) ) {
		return normalize_locales( $response );
	}

	$response = array_filter(
		$codes,
		function( $code ) use ( $country ) {
			return strtolower( substr( $code, -3, 3 ) ) === '-' . strtolower( $country );
		},
		ARRAY_FILTER_USE_KEY
	);

	// if there's one, return it.
	if ( 1 === count( $response ) ) {
		return $response;
	}

	if ( 1 < count( $response ) ) {
		return normalize_locales( $response );
	}

	$first = array_key_first( $codes );
	return [ $first => $codes[ $first ] ];
}

/**
 * Normalizes sub-locale data
 *
 * @param   array $codes  The locales array to normalize.
 *
 * @return  array          Normalized locales
 */
function normalize_locales( $codes ) {
	if ( count( $codes ) == 0 ) {
		return [];
	}
	$specs       = [];
	$spec_counts = [];
	foreach ( $codes as $locale => $definition ) {
		$specs[ $locale ]       = [
			'format'  => $definition['standard'],
			'group'   => $definition['group'],
			'decimal' => $definition['decimal'],
		];
		$spec_counts[ $locale ] = hash( 'sha256', json_encode( $specs[ $locale ] ) );
	}

	$counts = array_count_values( $spec_counts );
	arsort( $counts );
	$max_hash = array_keys( $counts )[0];
	foreach ( $spec_counts as $locale => $hash ) {
		if ( $hash === $max_hash ) {
			return [ $locale => $codes[ $locale ] ];
		}
	}
	$first = array_key_first( $codes );
	return [ $first => $codes[ $first ] ];
}

$currency_symbols     = get_json( __DIR__ . '/cldr/cldr-numbers-full/main/en/currencies.json' );
$currency_data        = get_json( __DIR__ . '/cldr/cldr-core/supplemental/currencyData.json' );
$unit_preference_data = get_json( __DIR__ . '/cldr/cldr-core/supplemental/unitPreferenceData.json' );
$default_content      = get_json( __DIR__ . '/cldr/cldr-core/defaultContent.json' );

$locales = build_locale_currency_matcher();

$output = [];

$wanted = include './currencies.php';

foreach ( $wanted as $curr => $label ) {
	$wanted[ $curr ] = [
		'name'     => $label,
		'codes'    => array_filter(
			$locales,
			function( $value ) use ( $curr ) {
				return $value === $curr;
			}
		),
		'format'   => get_currency_info( $curr, $currency_symbols ),
		'rounding' => $currency_data['supplemental']['currencyData']['fractions'][ $curr ] ?? $currency_data['supplemental']['currencyData']['fractions']['DEFAULT'],
	];

	foreach ( $wanted[ $curr ]['codes'] as $lcl => &$value ) {
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
	$wanted[ $curr ]['codes'] = array_filter( $wanted[ $curr ]['codes'] );
}

$countries = get_json( __DIR__ . '/country_map.json' );
echo 'Country Currency match count: ' . count( $countries );
$locale_info = [];
foreach ( $countries as $country ) {
	if ( array_key_exists( $country['currency'], $wanted ) ) {
		$locale_info[ $country['country'] ]                  = $wanted[ $country['currency'] ];
		$locale_info[ $country['country'] ]['currency_code'] = $country['currency'];
		if ( is_array( $wanted[ $country['currency'] ]['codes'] ) ) {

			$locale_info[ $country['country'] ]['selected_code'] = select_default_locale_for_country( $locale_info[ $country['country'] ]['codes'], $country['country'], $default_content );
			if ( is_array( $locale_info[ $country['country'] ]['selected_code'] ) ) {
				if ( count( $locale_info[ $country['country'] ]['selected_code'] ) > 0 ) {
					$format_container = $locale_info[ $country['country'] ];
					$locale           = array_key_first( $format_container['selected_code'] );
					$format_data      = $format_container['codes'][ $locale ];
					$formats          = $format_data['standard'];
					if ( mb_substr( $formats, 0, 2 ) === '¤ ' ) {
						$locale_info[ $country['country'] ]['currency_pos'] = 'left_space';
					} elseif ( mb_substr( $formats, 0, 1 ) === '¤' ) {
						$locale_info[ $country['country'] ]['currency_pos'] = 'left';
					} elseif ( mb_substr( $formats, -2, 2 ) === ' ¤' ) {
						$locale_info[ $country['country'] ]['currency_pos'] = 'right_space';
					} elseif ( mb_substr( $formats, -1, 1 ) === '¤' ) {
						$locale_info[ $country['country'] ]['currency_pos'] = 'right';
					}
					$locale_info[ $country['country'] ]['locales']        = array_keys( $locale_info[ $country['country'] ]['codes'] );
					$locale_info[ $country['country'] ]['default_locale'] = $locale;
					$locale_info[ $country['country'] ]['thousand_sep']   = $format_data['group'];
					$locale_info[ $country['country'] ]['decimal_sep']    = $format_data['decimal'];
				}
			}

			$locale_info[ $country['country'] ]['num_decimals']   = $format_container['rounding']['_cashDigits'] ?? $format_container['rounding']['_digits'];
			$locale_info[ $country['country'] ]['weight_unit']    = $unit_preference_data['supplemental']['unitPreferenceData']['mass']['default'][ $country['country'] ][1]['unit'] ?? $unit_preference_data['supplemental']['unitPreferenceData']['mass']['default']['001'][1]['unit'];
			$locale_info[ $country['country'] ]['dimension_unit'] = $unit_preference_data['supplemental']['unitPreferenceData']['length']['default'][ $country['country'] ][1]['unit'] ?? $unit_preference_data['supplemental']['unitPreferenceData']['length']['default']['001'][2]['unit'];

			$locale_info[ $country['country'] ]['singular']     = $locale_info[ $country['country'] ]['format']['displayName-count-one'] ?? null;
			$locale_info[ $country['country'] ]['plural']       = $locale_info[ $country['country'] ]['format']['displayName-count-other'] ?? null;
			$locale_info[ $country['country'] ]['short_symbol'] = $locale_info[ $country['country'] ]['format']['symbol-alt-narrow'] ?? null;

			$weight = &$locale_info[ $country['country'] ]['weight_unit'];
			if ( $weight == 'kilogram' ) {
				$weight = 'kg';
			} elseif ( $weight == 'pound' ) {
				$weight = 'oz';
			}

			$dimension = &$locale_info[ $country['country'] ]['dimension_unit'];
			if ( $dimension == 'centimeter' ) {
				$dimension = 'cm';
			} elseif ( $dimension == 'inches' ) {
				$dimension = 'in';
			}

			foreach ( $locale_info[ $country['country'] ]['codes'] as &$value ) {
				$formats   = $value['standard'];
				$new_value = [];
				if ( mb_substr( $formats, 0, 2 ) === '¤ ' ) {
					$new_value['currency_pos'] = 'left_space';
				} elseif ( mb_substr( $formats, 0, 1 ) === '¤' ) {
					$new_value['currency_pos'] = 'left';
				} elseif ( mb_substr( $formats, -2, 2 ) === ' ¤' ) {
					$new_value['currency_pos'] = 'right_space';
				} elseif ( mb_substr( $formats, -1, 1 ) === '¤' ) {
					$new_value['currency_pos'] = 'right';
				}
				$new_value['thousand_sep'] = $format_data['group'];
				$new_value['decimal_sep']  = $format_data['decimal'];
				$value                     = $new_value;
			}

			unset( $locale_info[ $country['country'] ]['selected_code'] );
			unset( $locale_info[ $country['country'] ]['rounding'] );
			unset( $locale_info[ $country['country'] ]['format'] );
			unset( $locale_info[ $country['country'] ]['locales'] );
		}
	}
}


// Reformat output.

$defaults = [
	'currency_pos'   => 'left_space',
	'num_decimals'   => 2,
	'thousand_sep'   => '.',
	'decimal_sep'    => ',',
	'dimension_unit' => 'cm',
	'weight_unit'    => 'kg',
];

$output    = [];
$locales   = [];
$repeating = [];

foreach ( $locale_info as $locale => $info ) {
	$locales[ $info['currency_code'] ] = $info['codes'];
	$output[ $locale ]                 = [
		'currency_code'  => $info['currency_code'],
		'currency_pos'   => $info['currency_pos'] ?? $defaults['currency_pos'],
		'thousand_sep'   => $info['thousand_sep'] ?? $defaults['thousand_sep'],
		'decimal_sep'    => $info['decimal_sep'] ?? $defaults['decimal_sep'],
		'num_decimals'   => intval( $info['num_decimals'] ?? $defaults['num_decimals'] ),
		'weight_unit'    => $info['weight_unit'] ?? $defaults['weight_unit'],
		'dimension_unit' => $info['dimension_unit'] ?? $defaults['dimension_unit'],
		'default_locale' => $info['default_locale'] ?? null,
		'name'           => $info['name'],
		'singular'       => $info['singular'] ?? null,
		'plural'         => $info['plural'] ?? null,
		'short_symbol'   => $info['short_symbol'] ?? null,
		'locales'        => '%%$locales["' . $info['currency_code'] . '"]%%',
	];
	if ( 0 === count( $info['codes'] ) ) {
		echo 'Missing Locales: ' . $locale . ': ' . $info['currency_code'] . '<br>';
	}
	if ( 1 < count( $output[ $locale ] ) ) {
		$repeating[] = $info['currency_code'];
	}
}

// $repeats = array_count_values( $repeating );
// arsort( $repeats );
// print_r( count( $output ) );
// print_r( $repeats );
ksort( $locales );
file_put_contents( './locale_data.json', json_encode( $locales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );
file_put_contents( './currency_data.json', json_encode( $output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );

$locale_data_new = '<?php

$locales = ' . var_export( $locales, true ) . ';

return ' . var_export( $output, true ) . ';
';

$locale_data_new = str_replace( "'%%", '', $locale_data_new );
$locale_data_new = str_replace( "%%'", '', $locale_data_new );

file_put_contents( './locale-info-new.php', $locale_data_new );
