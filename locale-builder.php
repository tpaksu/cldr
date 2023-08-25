<?php
/**
 * CLDR formatter builder
 *
 * @package Currencies
 * @phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
 */

if ( ! file_exists( './json' ) ) {
	mkdir( './json' );
}

if ( ! file_exists( './output' ) ) {
	mkdir( './output' );
}

$currency_locale_data  = require 'includes/currency-locale-matcher.php';
$country_currency_data = require 'includes/country-currency-matcher.php';
$currencies            = require 'includes/currencies.php';
$currency_symbols      = get_json( __DIR__ . '/cldr/cldr-numbers-full/main/en/currencies.json' );
$currency_data         = get_json( __DIR__ . '/cldr/cldr-core/supplemental/currencyData.json' );
$unit_preference_data  = get_json( __DIR__ . '/cldr/cldr-core/supplemental/unitPreferenceData.json' );
$default_content       = get_json( __DIR__ . '/cldr/cldr-core/defaultContent.json' );
$default_locales       = get_json( __DIR__ . '/json/default-locales.json' );
$language_directions   = get_json( __DIR__ . '/json/language-direction.json' );

require './includes/functions.php';

$data           = [];
$global_formats = [];

foreach ( $currencies as $code => $name ) {
	$data[ $code ] = get_currency_object( $code, $name, $currency_symbols, $currency_data, $currency_locale_data );
}

$data['BYN']['format']['symbol-alt-narrow'] = 'р.';

// Map currency info to countries.
$locale_info = [];

$defaults = [
	'currency_pos'   => 'left_space',
	'num_decimals'   => 2,
	'thousand_sep'   => '.',
	'decimal_sep'    => ',',
	'dimension_unit' => 'cm',
	'weight_unit'    => 'kg',
];

foreach ( $country_currency_data as $country => $currencies ) {
	if ( ! array_key_exists( $country, (array) $default_locales ) ) {
		continue;
	}
	foreach ( $currencies as $currency ) {
		if ( array_key_exists( $currency, $data ) ) {
			$locale_info[ $country ]                   = $data[ $currency ];
			$locale_info[ $country ]['currency_code']  = $currency;
			$locale_info[ $country ]['weight_unit']    = $unit_preference_data['supplemental']['unitPreferenceData']['mass']['default'][ $country ][1]['unit'] ?? $unit_preference_data['supplemental']['unitPreferenceData']['mass']['default']['001'][1]['unit'];
			$locale_info[ $country ]['dimension_unit'] = $unit_preference_data['supplemental']['unitPreferenceData']['length']['default'][ $country ][1]['unit'] ?? $unit_preference_data['supplemental']['unitPreferenceData']['length']['default']['001'][2]['unit'];
			$locale_info[ $country ]['singular']       = $locale_info[ $country ]['format']['displayName-count-one'] ?? null;
			$locale_info[ $country ]['plural']         = $locale_info[ $country ]['format']['displayName-count-other'] ?? null;
			$locale_info[ $country ]['short_symbol']   = $locale_info[ $country ]['format']['symbol-alt-narrow'] ?? null;
			$locale_info[ $country ]['direction']      = get_language_direction( $country, $default_locales, $language_directions );
			$weight                                    = &$locale_info[ $country ]['weight_unit'];
			if ( 'kilogram' === $weight ) {
				$weight = 'kg';
			} elseif ( 'pound' === $weight ) {
				$weight = 'oz';
			}

			$dimension = &$locale_info[ $country ]['dimension_unit'];
			if ( 'centimeter' === $dimension ) {
				$dimension = 'cm';
			} elseif ( 'inches' === $dimension ) {
				$dimension = 'in';
			}

			$default_locale = $default_locales[ $country ];

			if ( is_array( $locale_info[ $country ]['codes'] ) && isset( $locale_info[ $country ]['codes'][ $default_locale ] ) ) {
				$locale_info[ $country ]['selected_code'] = [ $default_locale => $locale_info[ $country ]['codes'][ $default_locale ] ];
				if ( is_array( $locale_info[ $country ]['selected_code'] ) ) {
					if ( count( $locale_info[ $country ]['selected_code'] ) > 0 ) {
						$format_container = $locale_info[ $country ];
						$locale_code      = array_keys( $format_container['selected_code'] )[0];
						$format_data      = $format_container['codes'][ $locale_code ];
						$formats          = $format_data['standard'];
						$formats          = fix_formats( $formats );
						if ( substr( $formats, 0, 2 ) === 'o ' ) {
							$locale_info[ $country ]['currency_pos'] = 'left_space';
						} elseif ( substr( $formats, 0, 1 ) === 'o' ) {
							$locale_info[ $country ]['currency_pos'] = 'left';
						} elseif ( substr( $formats, -2, 2 ) === ' o' ) {
							$locale_info[ $country ]['currency_pos'] = 'right_space';
						} elseif ( substr( $formats, -1, 1 ) === 'o' ) {
							$locale_info[ $country ]['currency_pos'] = 'right';
						}
						$locale_info[ $country ]['locales']        = array_keys( $locale_info[ $country ]['codes'] );
						$locale_info[ $country ]['default_locale'] = $locale_code;
						$locale_info[ $country ]['thousand_sep']   = fix_formats( $format_data['group'] );
						$locale_info[ $country ]['decimal_sep']    = fix_formats( $format_data['decimal'] );
						$locale_info[ $country ]['num_decimals']   = $currency_data['supplemental']['currencyData']['fractions'][ $currency ]['_cashDigits'] ??
						$currency_data['supplemental']['currencyData']['fractions'][ $currency ]['_digits'] ?? null;
					}

					foreach ( $locale_info[ $country ]['codes'] as $key => &$value ) {
						$formats   = $value['standard'];
						$new_value = [];
						$formats   = fix_formats( $formats );
						if ( substr( $formats, 0, 2 ) === 'o ' ) {
							$new_value['currency_pos'] = 'left_space';
						} elseif ( substr( $formats, 0, 1 ) === 'o' ) {
							$new_value['currency_pos'] = 'left';
						} elseif ( substr( $formats, -2, 2 ) === ' o' ) {
							$new_value['currency_pos'] = 'right_space';
						} elseif ( substr( $formats, -1, 1 ) === 'o' ) {
							$new_value['currency_pos'] = 'right';
						}
						$new_value['thousand_sep']              = fix_formats( $value['group'] );
						$new_value['decimal_sep']               = fix_formats( $value['decimal'] );
						$new_value['direction']                 = get_locale_direction( $key, $language_directions );
						$new_value['format']                    = get_format_key( $currency . ' - ' . $key, $new_value );
						$global_formats[ $new_value['format'] ] = [
							'thousand_sep' => $new_value['thousand_sep'],
							'decimal_sep'  => $new_value['decimal_sep'],
							'direction'    => $new_value['direction'],
							'currency_pos' => $new_value['currency_pos'],
						];
						$new_value['format']                    = '%%$global_formats["' . $new_value['format'] . '"]%%';
						$value                                  = $new_value;
					}
				}

				unset( $locale_info[ $country ]['selected_code'] );
				unset( $locale_info[ $country ]['rounding'] );
				unset( $locale_info[ $country ]['format'] );
				unset( $locale_info[ $country ]['locales'] );
			}
		}
	}
}


// Reformat output.

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
		'direction'      => $info['direction'],
		'default_locale' => str_replace( '-', '_', $info['default_locale'] ?? '' ) ?? null,
		'name'           => $info['name'],
		'singular'       => $info['singular'] ?? null,
		'plural'         => $info['plural'] ?? null,
		'short_symbol'   => $info['short_symbol'] ?? null,
		'locales'        => '%%$locales[`' . $info['currency_code'] . '`]%%',
	];
	if ( 0 === count( $info['codes'] ) ) {
		echo 'Missing Locales: ' . $locale . ': ' . $info['currency_code'] . PHP_EOL;
	}
	if ( 1 < count( $output[ $locale ] ) ) {
		$repeating[] = $info['currency_code'];
	}
}

ksort( $locales );
ksort( $global_formats );

$new_locales = [];
foreach ( $locales as $currency => &$locale ) {
	$new_locales[ $currency ] = [];
	foreach ( $locale as $key => $value ) {
		$k                              = str_replace( '-', '_', $key );
		$new_locales[ $currency ][ $k ] = $value;
	}
	$new_locales[ $currency ]['default'] = select_default_locale_for_currency( $locale );
}

foreach ( $new_locales as $currency => &$locale ) {
	foreach ( $locale as $key => &$value ) {
		if ( 'num_decimals' === $key ) {
			continue;
		}
		if ( false === strpos( $key, '_' ) && 'default' !== $key ) {
			$locale[ complete_country_for_locale( $key, $default_content['defaultContent'] ) ] = $locale[ $key ];
		}
		if ( isset( $value['format'] ) ) {
			$value = $value['format'];
		} else {
			echo 'Missing format for ' . $currency . '-' . $key . "\n";
		}
	}
}

foreach ( $output as $country => &$data ) {
	$data['default_locale'] = complete_country_for_locale( $data['default_locale'], $default_content['defaultContent'] );
}

$locales = $new_locales;

file_put_contents( './json/locale_data.json', json_encode( $locales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */
file_put_contents( './json/currency_data.json', json_encode( $output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */

$currency_locale_data = '<?php
/**
 * Currency formatting information
 *
 * @package WooCommerce\Payments\i18n
 * @version 3.5.0
 */

defined( \'ABSPATH\' ) || exit;

$global_formats = ' . var_export_override( $global_formats, true ) . ';

return ' . var_export_override( $locales, true ) . ';
';

$currency_locale_data = str_replace( 'NULL', 'null', $currency_locale_data );
$currency_locale_data = str_replace( "'%%", '', $currency_locale_data );
$currency_locale_data = str_replace( "%%'", '', $currency_locale_data );
/* phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents */
file_put_contents( './output/currency-info.php', $currency_locale_data );

$country_locale_data = '<?php
/**
 * Locales information
 *
 * @package WooCommerce\Payments\i18n
 * @version 3.5.0
 */

defined( \'ABSPATH\' ) || exit;

$locales = require WCPAY_ABSPATH . \'/i18n/currency-info.php\';

return ' . var_export_override( $output, true ) . ';
';

$country_locale_data = str_replace( "'%%", '', $country_locale_data );
$country_locale_data = str_replace( "%%'", '', $country_locale_data );
$country_locale_data = str_replace( 'NULL', 'null', $country_locale_data );
$country_locale_data = str_replace( '`', "'", $country_locale_data );
/* phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents */
file_put_contents( './output/locale-info.php', $country_locale_data );

// exec( 'phpcbf ./output/*.php' ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_exec

return $data;
