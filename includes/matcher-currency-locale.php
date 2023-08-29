<?php
/**
 * CLDR formatter builder
 *
 * @package Currencies
 */

$locale_currencies = require 'matcher-locale-currency.php';

$locales = [];
foreach ( $locale_currencies as $code => $currencies ) {
	foreach ( $currencies as $currency ) {
		if ( ! isset( $locales[ $currency ] ) ) {
			$locales[ $currency ] = [];
		}
		$locales[ $currency ][] = $code;
	}
}

ksort( $locales );
file_put_contents( './json/locales.json', json_encode( $locales, JSON_PRETTY_PRINT ) );

// echo '<pre>' . print_r( $locales, true );

return $locales;
