<?php
/**
 * Comment
 *
 * @package Currencies
 */

define( 'ABSPATH', 2 );

$locale_info = include './output/locale-info.php';

foreach ( $locale_info as $country => $country_info ) {
	foreach ( $country_info['locales'] as $local => $format ) {
		$price  = number_format( 1250.25, $country_info['num_decimals'], $format['decimal_sep'], $format['thousand_sep'] );
		$symbol = $country_info['short_symbol'] ?? $country_info['currency_code'];
		$price  = ( 'left' === $format['currency_pos']
			? $symbol . $price
			: ( 'left_space' === $format['currency_pos']
				? $symbol . ' ' . $price
				: ( 'right' === $format['currency_pos']
					? $price . $symbol
					: $price . ' ' . $symbol ) ) );
		echo filter_var( $country . ' - ' . $local . ': ' . $price . '<br>' );
	}
}
