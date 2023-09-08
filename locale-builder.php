<?php
/**
 * CLDR formatter builder
 *
 * @package Currencies
 * @phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
 */
require 'includes/functions.php';

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

file_put_contents( './json/0-country-info.json', json_encode( $country_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */

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

file_put_contents( './json/1-locale-formats.json', json_encode( $country_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */

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
		'negativity'     => $default_format['negative_format'] ?? '-',
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
				'negativity'   => $format['negative_format'],
			];
		}
		$country_formats[ $currency ][ $_locale ] = "\$global_formats['$combined_key']";
	}
	ksort( $country_formats[ $currency ] );
}

$combined_info = fix_combined_info( $combined_info );
ksort( $combined_info );
ksort( $country_formats );
ksort( $available_formats );

file_put_contents( './json/2-locale-info.json', json_encode( $combined_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */
file_put_contents( './json/3-currency-info.json', json_encode( $country_formats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */
file_put_contents( './json/4-available-formats.json', json_encode( $available_formats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions */


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

file_put_contents( './output/currency-info.php', $currency_locale_data );


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

file_put_contents( './output/locale-info.php', $country_locale_data );

ob_implicit_flush( true );
system( 'phpcbf ./output/currency-info.php -v' ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_system
