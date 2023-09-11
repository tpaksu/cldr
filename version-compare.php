<?php

// phpcs:disable WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

define( 'ABSPATH', '' );

function get_locales( $subfolder ) {
	$file = $subfolder . '/locale-info.php';
	file_put_contents( $file, str_replace( 'WCPAY_ABSPATH', '$wcpay_abspath', file_get_contents( $file ) ) );
	mkdir( $subfolder . '/i18n', 0777, true );
	copy( $subfolder . '/currency-info.php', $subfolder . '/i18n/currency-info.php' );
	$wcpay_abspath = $subfolder;
	$locale        = include $file;
	return $locale;
}

function revert_files( $subfolder ) {
	unlink( "$subfolder/i18n/currency-info.php" );
	rmdir( "$subfolder/i18n" );
	file_put_contents(
		"$subfolder/locale-info.php",
		str_replace(
			'$wcpay_abspath',
			'WCPAY_ABSPATH',
			file_get_contents( "$subfolder/locale-info.php" )
		)
	);
}

function diff_recursive( $old, $new ): array {
	$diffs = [
		'added'         => [],
		'removed'       => [],
		'changed'       => [],
		'empty_symbols' => [],
	];

	$diffs['added']   = array_keys( array_diff_key( $new, $old ) );
	$diffs['removed'] = array_keys( array_diff_key( $old, $new ) );

	foreach ( $new as $country => $currency_data ) {
		$diffs['changed'][ $country ] = [
			'changes'         => [],
			'added_locales'   => [],
			'removed_locales' => [],
		];
		foreach ( $currency_data as $key => $value ) {
			if ( isset( $old[ $country ][ $key ] ) && ! is_array( $value ) && $old[ $country ][ $key ] !== $value ) {
				if ( 'name' === $key ) {
					if ( strtoupper( $value ) === strtoupper( $old[ $country ][ $key ] ) ) {
						continue;
					} else {
						$diffs['changed'][ $country ]['changes'][] = "Changed `$key` from `{$old[$country][$key]}` to `$value` for {$currency_data['currency_code']}";
						continue;
					}
				}
				$diffs['changed'][ $country ]['changes'][] = "Changed `$key` from `{$old[$country][$key]}` to `$value` for {$currency_data['currency_code']} ({$currency_data['name']})";
			}
		}
		if ( empty( $currency_data['short_symbol'] ) ) {
			$diffs['empty_symbols'][] = $currency_data['currency_code'];
		}
		if ( isset( $old[ $country ] ) ) {
			$diffs['changed'][ $country ]['added_locales']   = array_keys( array_diff_key( $new[ $country ]['locales'], $old[ $country ]['locales'] ) );
			$diffs['changed'][ $country ]['removed_locales'] = array_keys( array_diff_key( $old[ $country ]['locales'], $new[ $country ]['locales'] ) );
		}

		if ( empty( $diffs['changed'][ $country ] ) || ( empty( $diffs['changed'][ $country ]['changes'] )
		&& empty( $diffs['changed'][ $country ]['added_locales'] )
		&& empty( $diffs['changed'][ $country ]['removed_locales'] ) ) ) {
			unset( $diffs['changed'][ $country ] );
		}
	}

	$diffs['empty_symbols'] = array_unique( $diffs['empty_symbols'] );
	sort( $diffs['empty_symbols'] );

	return $diffs;
}

$old_locale = get_locales( __DIR__ . '/previous_version' );
$new_locale = get_locales( __DIR__ . '/output' );

$difference = diff_recursive( $old_locale, $new_locale );

// print_r( $difference ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

foreach ( $difference['added'] as $added ) {
	echo "* Added `{$new_locale[$added]['currency_code']}` currency specifications for `$added` country.\n";
}
echo "\n";

foreach ( $difference['removed'] as $removed ) {
	echo "* Removed $removed country specifications.\n";
}
echo "\n";

foreach ( $difference['changed'] as $country => $data ) {
	if ( empty( $data['changes'] ) ) {
		continue;
	}
	echo "#### $country changes:\n";
	foreach ( $data['changes'] as $mod ) {
		echo "* $mod\n";
	}
	/*
	foreach ( $data['removed_locales'] as $_locale ) {
		echo "* $_locale locale added to $country\n";
	}
	foreach ( $data['added_locales'] as $_locale ) {
		echo "* $_locale locale removed from $country\n";
	}
	*/
	echo "\n";
}

revert_files( __DIR__ . '/previous_version' );
revert_files( __DIR__ . '/output' );
