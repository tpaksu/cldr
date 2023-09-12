<?php

require './includes/functions.php';
$currency_data = get_json( __DIR__ . '/cldr/cldr-core/supplemental/currencyData.json' );
$country_info  = [];
foreach ( $currency_data['supplemental']['currencyData']['region'] as $country => $currencies ) {
	$active_currencies = [];
	foreach ( $currencies as $currency_info ) {
		$key   = array_keys( $currency_info )[0];
		$value = $currency_info[ $key ];
		if ( isset( $value['_tender'] ) && 'false' === $value['_tender'] ) {
			continue;
		}
		if ( ! isset( $value['_to'] ) ) {
			$active_currencies[] = $key;
		}
	}

	if ( 1 < count( $active_currencies ) ) {
		echo "$country : " . implode( ', ', $active_currencies ) . "\n";
	}
}
