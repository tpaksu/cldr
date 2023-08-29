<?php
/**
 * Currency formatting information
 *
 * @package WooCommerce\Payments\i18n
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$global_formats = [
	'ls_comma_dot_ltr'   => [
		'thousand_sep' => '.',
		'decimal_sep'  => ',',
		'direction'    => 'ltr',
		'currency_pos' => 'left_space',
	],
	'ls_comma_dot_rtl'   => [
		'thousand_sep' => '.',
		'decimal_sep'  => ',',
		'direction'    => 'rtl',
		'currency_pos' => 'left_space',
	],
	'ls_comma_space_ltr' => [
		'thousand_sep' => ' ',
		'decimal_sep'  => ',',
		'direction'    => 'ltr',
		'currency_pos' => 'left_space',
	],
	'ls_dot_comma_ltr'   => [
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'direction'    => 'ltr',
		'currency_pos' => 'left_space',
	],
	'ls_dot_comma_rtl'   => [
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'direction'    => 'rtl',
		'currency_pos' => 'left_space',
	],
	'lx_comma_dot_ltr'   => [
		'thousand_sep' => '.',
		'decimal_sep'  => ',',
		'direction'    => 'ltr',
		'currency_pos' => 'left',
	],
	'lx_comma_dot_rtl'   => [
		'thousand_sep' => '.',
		'decimal_sep'  => ',',
		'direction'    => 'rtl',
		'currency_pos' => 'left',
	],
	'lx_comma_space_ltr' => [
		'thousand_sep' => ' ',
		'decimal_sep'  => ',',
		'direction'    => 'ltr',
		'currency_pos' => 'left',
	],
	'lx_dot_comma_ltr'   => [
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'direction'    => 'ltr',
		'currency_pos' => 'left',
	],
	'lx_dot_comma_rtl'   => [
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'direction'    => 'rtl',
		'currency_pos' => 'left',
	],
	'lx_dot_space_ltr'   => [
		'thousand_sep' => ' ',
		'decimal_sep'  => '.',
		'direction'    => 'ltr',
		'currency_pos' => 'left',
	],
	'rs_comma_dot_ltr'   => [
		'thousand_sep' => '.',
		'decimal_sep'  => ',',
		'direction'    => 'ltr',
		'currency_pos' => 'right_space',
	],
	'rs_comma_dot_rtl'   => [
		'thousand_sep' => '.',
		'decimal_sep'  => ',',
		'direction'    => 'rtl',
		'currency_pos' => 'right_space',
	],
	'rs_comma_space_ltr' => [
		'thousand_sep' => ' ',
		'decimal_sep'  => ',',
		'direction'    => 'ltr',
		'currency_pos' => 'right_space',
	],
	'rs_dot_apos_ltr'    => [
		'thousand_sep' => '\'',
		'decimal_sep'  => '.',
		'direction'    => 'ltr',
		'currency_pos' => 'right_space',
	],
	'rs_dot_comma_ltr'   => [
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'direction'    => 'ltr',
		'currency_pos' => 'right_space',
	],
	'rs_dot_comma_rtl'   => [
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'direction'    => 'rtl',
		'currency_pos' => 'right_space',
	],
	'rx_comma_dot_ltr'   => [
		'thousand_sep' => '.',
		'decimal_sep'  => ',',
		'direction'    => 'ltr',
		'currency_pos' => 'right',
	],
	'rx_comma_dot_rtl'   => [
		'thousand_sep' => '.',
		'decimal_sep'  => ',',
		'direction'    => 'rtl',
		'currency_pos' => 'right',
	],
	'rx_dot_comma_ltr'   => [
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'direction'    => 'ltr',
		'currency_pos' => 'right',
	],
	'rx_dot_comma_rtl'   => [
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'direction'    => 'rtl',
		'currency_pos' => 'right',
	],
];

return [
	'AC' => [
		'en' => 'lx_dot_comma_ltr',
	],
	'AD' => [
		'ca-AD' => 'rs_comma_dot_ltr',
	],
	'AE' => [
		'ar-AE' => 'rx_dot_comma_rtl',
	],
	'AF' => [
		'fa-AF'   => 'ls_comma_dot_rtl',
		'ps'      => 'lx_comma_dot_rtl',
		'tk'      => 'rs_comma_space_ltr',
		'uz-Arab' => 'ls_comma_dot_rtl',
	],
	'AG' => [
		'en-AG' => 'lx_dot_comma_ltr',
	],
	'AI' => [
		'en-AI' => 'lx_dot_comma_ltr',
	],
	'AL' => [
		'sq' => 'rs_comma_space_ltr',
	],
	'AM' => [
		'hy' => 'rs_comma_space_ltr',
	],
	'AO' => [
		'pt-AO' => 'rs_comma_space_ltr',
	],
	'AR' => [
		'es-AR' => 'ls_comma_dot_ltr',
	],
	'AS' => [
		'en-AS' => 'lx_dot_comma_ltr',
	],
	'AT' => [
		'de-AT' => 'rs_comma_space_ltr',
		'hr'    => 'rs_comma_dot_ltr',
		'hu'    => 'rs_comma_space_ltr',
		'sl'    => 'rs_comma_dot_ltr',
	],
	'AU' => [
		'en-AU' => 'lx_dot_comma_ltr',
	],
	'AW' => [
		'nl-AW'  => 'ls_comma_dot_ltr',
		'pap-AW' => 'ls_dot_comma_ltr',
	],
	'AX' => [
		'sv-AX' => 'rs_comma_space_ltr',
	],
	'AZ' => [
		'az'      => 'rs_comma_dot_ltr',
		'az-Cyrl' => 'rs_comma_dot_ltr',
	],
	'BA' => [
		'bs'      => 'rs_comma_dot_ltr',
		'bs-Cyrl' => 'rs_comma_dot_ltr',
		'hr-BA'   => 'rs_comma_dot_ltr',
		'sr'      => 'rs_comma_dot_ltr',
		'sr-Latn' => 'rs_comma_dot_ltr',
	],
	'BB' => [
		'en-BB' => 'lx_dot_comma_ltr',
	],
	'BD' => [
		'bn' => 'rx_dot_comma_ltr',
	],
	'BE' => [
		'de-BE' => 'rs_comma_dot_ltr',
		'fr-BE' => 'rs_comma_space_ltr',
		'nl-BE' => 'ls_comma_dot_ltr',
	],
	'BF' => [
		'fr-BF' => 'rs_comma_space_ltr',
	],
	'BG' => [
		'bg' => 'rs_comma_space_ltr',
	],
	'BH' => [
		'ar-BH' => 'rs_comma_dot_rtl',
	],
	'BI' => [
		'en-BI' => 'lx_dot_comma_ltr',
		'fr-BI' => 'rs_comma_space_ltr',
		'rn'    => 'rx_comma_dot_ltr',
	],
	'BJ' => [
		'fr-BJ' => 'rs_comma_space_ltr',
	],
	'BL' => [
		'fr-BL' => 'rs_comma_space_ltr',
	],
	'BM' => [
		'en-BM' => 'lx_dot_comma_ltr',
	],
	'BN' => [
		'ms-Arab' => 'lx_dot_comma_rtl',
		'ms-BN'   => 'lx_comma_dot_ltr',
	],
	'BO' => [
		'es-BO' => 'lx_comma_dot_ltr',
		'qu-BO' => 'ls_comma_dot_ltr',
	],
	'BQ' => [
		'nl-BQ' => 'ls_comma_dot_ltr',
	],
	'BR' => [
		'pt'  => 'ls_comma_dot_ltr',
		'vec' => 'ls_dot_comma_ltr',
	],
	'BS' => [
		'en-BS' => 'lx_dot_comma_ltr',
	],
	'BT' => [
		'dz' => 'lx_dot_comma_ltr',
	],
	'BV' => [
		'und' => 'ls_dot_comma_ltr',
	],
	'BW' => [
		'en-BW' => 'lx_dot_comma_ltr',
		'tn-BW' => 'lx_dot_space_ltr',
	],
	'BY' => [
		'be'    => 'rs_comma_space_ltr',
		'ru-BY' => 'rs_comma_space_ltr',
	],
	'BZ' => [
		'en-BZ' => 'lx_dot_comma_ltr',
	],
	'CA' => [
		'en-CA'   => 'lx_dot_comma_ltr',
		'fr-CA'   => 'rs_comma_space_ltr',
		'iu'      => 'ls_dot_comma_ltr',
		'iu-Latn' => 'ls_dot_comma_ltr',
	],
	'CC' => [
		'en-CC' => 'lx_dot_comma_ltr',
	],
	'CD' => [
		'fr-CD' => 'rs_comma_space_ltr',
		'ln'    => 'rs_comma_dot_ltr',
		'sw-CD' => 'ls_comma_dot_ltr',
	],
	'CF' => [
		'fr-CF' => 'rs_comma_space_ltr',
		'sg'    => 'lx_comma_dot_ltr',
	],
	'CG' => [
		'fr-CG' => 'rs_comma_space_ltr',
	],
	'CH' => [
		'de-CH' => 'rs_dot_apos_ltr',
		'fr-CH' => 'rs_comma_space_ltr',
		'gsw'   => 'rs_dot_apos_ltr',
		'it-CH' => 'rs_dot_apos_ltr',
		'rm'    => 'rs_dot_apos_ltr',
	],
	'CI' => [
		'fr-CI' => 'rs_comma_space_ltr',
	],
	'CK' => [
		'en-CK' => 'lx_dot_comma_ltr',
	],
	'CL' => [
		'es-CL' => 'lx_comma_dot_ltr',
	],
	'CM' => [
		'en-CM' => 'lx_dot_comma_ltr',
		'fr-CM' => 'rs_comma_space_ltr',
	],
	'CN' => [
		'bo'      => 'ls_dot_comma_ltr',
		'ko'      => 'lx_dot_comma_ltr',
		'mn-Mong' => 'ls_dot_comma_ltr',
		'ug'      => 'lx_dot_comma_rtl',
		'zh'      => 'lx_dot_comma_ltr',
	],
	'CO' => [
		'es-CO' => 'lx_comma_dot_ltr',
	],
	'CR' => [
		'es-CR' => 'lx_comma_space_ltr',
	],
	'CU' => [
		'es-CU' => 'lx_dot_comma_ltr',
	],
	'CV' => [
		'pt-CV' => 'rs_comma_space_ltr',
	],
	'CW' => [
		'nl-CW' => 'ls_comma_dot_ltr',
		'pap'   => 'ls_dot_comma_ltr',
	],
	'CX' => [
		'en-CX' => 'lx_dot_comma_ltr',
	],
	'CY' => [
		'el-CY' => 'rs_comma_dot_ltr',
		'tr-CY' => 'lx_comma_dot_ltr',
	],
	'CZ' => [
		'cs' => 'rs_comma_space_ltr',
	],
	'DE' => [
		'de'  => 'rs_comma_dot_ltr',
		'frr' => 'ls_dot_comma_ltr',
	],
	'DG' => [
		'en-DG' => 'lx_dot_comma_ltr',
	],
	'DJ' => [
		'ar-DJ' => 'rs_comma_dot_rtl',
		'fr-DJ' => 'rs_comma_space_ltr',
	],
	'DK' => [
		'da' => 'rs_comma_dot_ltr',
		'de' => 'rs_comma_dot_ltr',
		'kl' => 'lx_comma_dot_ltr',
	],
	'DM' => [
		'en-DM' => 'lx_dot_comma_ltr',
	],
	'DO' => [
		'es-DO' => 'lx_dot_comma_ltr',
	],
	'DZ' => [
		'ar-DZ' => 'rx_comma_dot_rtl',
		'fr-DZ' => 'rs_comma_space_ltr',
	],
	'EA' => [
		'es-EA' => 'rs_comma_dot_ltr',
	],
	'EC' => [
		'es-EC' => 'lx_comma_dot_ltr',
		'qu-EC' => 'ls_dot_comma_ltr',
	],
	'EE' => [
		'et' => 'rs_comma_space_ltr',
	],
	'EG' => [
		'ar-EG' => 'rs_comma_dot_rtl',
	],
	'EH' => [
		'ar-EH' => 'rx_dot_comma_rtl',
	],
	'ER' => [
		'ar-ER' => 'rs_comma_dot_rtl',
		'en-ER' => 'lx_dot_comma_ltr',
		'ti-ER' => 'lx_dot_comma_ltr',
	],
	'ES' => [
		'ast'   => 'rs_comma_dot_ltr',
		'ca'    => 'rs_comma_dot_ltr',
		'es'    => 'rs_comma_dot_ltr',
		'eu'    => 'rs_comma_dot_ltr',
		'gl'    => 'rs_comma_dot_ltr',
		'oc-ES' => 'ls_dot_comma_ltr',
	],
	'ET' => [
		'am' => 'lx_dot_comma_ltr',
	],
	'FI' => [
		'fi'    => 'rs_comma_space_ltr',
		'sms'   => 'ls_dot_comma_ltr',
		'sv-FI' => 'rs_comma_space_ltr',
	],
	'FJ' => [
		'en-FJ' => 'lx_dot_comma_ltr',
	],
	'FK' => [
		'en-FK' => 'lx_dot_comma_ltr',
	],
	'FM' => [
		'en-FM' => 'lx_dot_comma_ltr',
	],
	'FO' => [
		'fo' => 'rs_comma_dot_ltr',
	],
	'FR' => [
		'fr' => 'rs_comma_space_ltr',
	],
	'GA' => [
		'fr-GA' => 'rs_comma_space_ltr',
	],
	'GB' => [
		'cy'    => 'lx_dot_comma_ltr',
		'en-GB' => 'lx_dot_comma_ltr',
		'ga-GB' => 'lx_dot_comma_ltr',
		'gd'    => 'lx_dot_comma_ltr',
	],
	'GD' => [
		'en-GD' => 'lx_dot_comma_ltr',
	],
	'GE' => [
		'ab' => 'ls_dot_comma_ltr',
		'ka' => 'rs_comma_space_ltr',
		'os' => 'ls_comma_space_ltr',
	],
	'GF' => [
		'fr-GF' => 'rs_comma_space_ltr',
	],
	'GG' => [
		'en-GG' => 'lx_dot_comma_ltr',
	],
	'GH' => [
		'ak'    => 'lx_dot_comma_ltr',
		'ee'    => 'lx_dot_comma_ltr',
		'en-GH' => 'lx_dot_comma_ltr',
		'gaa'   => 'ls_dot_comma_ltr',
	],
	'GI' => [
		'en-GI' => 'lx_dot_comma_ltr',
	],
	'GL' => [
		'kl' => 'lx_comma_dot_ltr',
	],
	'GM' => [
		'en-GM' => 'lx_dot_comma_ltr',
	],
	'GN' => [
		'fr-GN' => 'rs_comma_space_ltr',
	],
	'GP' => [
		'fr-GP' => 'rs_comma_space_ltr',
	],
	'GQ' => [
		'es-GQ' => 'rs_comma_dot_ltr',
		'fr-GQ' => 'rs_comma_space_ltr',
		'pt-GQ' => 'rs_comma_space_ltr',
	],
	'GR' => [
		'el' => 'rs_comma_dot_ltr',
	],
	'GS' => [
		'und' => 'ls_dot_comma_ltr',
	],
	'GT' => [
		'es-GT' => 'lx_dot_comma_ltr',
		'quc'   => 'ls_dot_comma_ltr',
	],
	'GU' => [
		'en-GU' => 'lx_dot_comma_ltr',
	],
	'GW' => [
		'pt-GW' => 'rs_comma_space_ltr',
	],
	'GY' => [
		'en-GY' => 'lx_dot_comma_ltr',
	],
	'HK' => [
		'en-HK'   => 'lx_dot_comma_ltr',
		'zh-Hant' => 'lx_dot_comma_ltr',
	],
	'HM' => [
		'und' => 'ls_dot_comma_ltr',
	],
	'HN' => [
		'es-HN' => 'lx_dot_comma_ltr',
	],
	'HR' => [
		'hr'  => 'rs_comma_dot_ltr',
		'it'  => 'rs_comma_dot_ltr',
		'vec' => 'ls_dot_comma_ltr',
	],
	'HT' => [
		'fr-HT' => 'rs_comma_space_ltr',
	],
	'HU' => [
		'hu' => 'rs_comma_space_ltr',
	],
	'IC' => [
		'es-IC' => 'rs_comma_dot_ltr',
	],
	'ID' => [
		'id' => 'lx_comma_dot_ltr',
	],
	'IE' => [
		'en-IE' => 'lx_dot_comma_ltr',
		'ga'    => 'lx_dot_comma_ltr',
	],
	'IL' => [
		'ar-IL' => 'rs_comma_dot_rtl',
		'he'    => 'rs_dot_comma_rtl',
	],
	'IM' => [
		'en-IM' => 'lx_dot_comma_ltr',
		'gv'    => 'lx_dot_comma_ltr',
	],
	'IN' => [
		'as'      => 'lx_dot_comma_ltr',
		'bn-IN'   => 'lx_dot_comma_ltr',
		'en-IN'   => 'lx_dot_comma_ltr',
		'gu'      => 'lx_dot_comma_ltr',
		'hi'      => 'lx_dot_comma_ltr',
		'kn'      => 'lx_dot_comma_ltr',
		'kok'     => 'lx_dot_comma_ltr',
		'ks'      => 'lx_comma_dot_rtl',
		'mai'     => 'ls_dot_comma_ltr',
		'ml'      => 'lx_dot_comma_ltr',
		'mr'      => 'lx_dot_comma_ltr',
		'ne-IN'   => 'ls_dot_comma_ltr',
		'or'      => 'lx_dot_comma_ltr',
		'pa'      => 'ls_dot_comma_ltr',
		'sa'      => 'ls_dot_comma_ltr',
		'sat'     => 'ls_dot_comma_ltr',
		'sd'      => 'rs_comma_dot_rtl',
		'sd-Deva' => 'ls_dot_comma_ltr',
		'ta'      => 'lx_dot_comma_ltr',
		'te'      => 'lx_dot_comma_ltr',
		'ur-IN'   => 'lx_comma_dot_rtl',
	],
	'IO' => [
		'en-IO' => 'lx_dot_comma_ltr',
	],
	'IQ' => [
		'ar-IQ'   => 'rs_comma_dot_rtl',
		'az-Arab' => 'ls_comma_dot_rtl',
		'ckb'     => 'rs_comma_dot_rtl',
	],
	'IR' => [
		'fa' => 'ls_comma_dot_rtl',
	],
	'IS' => [
		'is' => 'rs_comma_dot_ltr',
	],
	'IT' => [
		'fr'  => 'rs_comma_space_ltr',
		'it'  => 'rs_comma_dot_ltr',
		'vec' => 'ls_dot_comma_ltr',
	],
	'JE' => [
		'en-JE' => 'lx_dot_comma_ltr',
	],
	'JM' => [
		'en-JM' => 'lx_dot_comma_ltr',
	],
	'JO' => [
		'ar-JO' => 'rs_comma_dot_rtl',
	],
	'JP' => [
		'ja' => 'lx_dot_comma_ltr',
	],
	'KE' => [
		'en-KE' => 'lx_dot_comma_ltr',
		'sw-KE' => 'ls_dot_comma_ltr',
	],
	'KG' => [
		'ky'    => 'rs_comma_space_ltr',
		'ru-KG' => 'rs_comma_space_ltr',
	],
	'KH' => [
		'km' => 'rx_comma_dot_ltr',
	],
	'KI' => [
		'en-KI' => 'lx_dot_comma_ltr',
	],
	'KM' => [
		'ar-KM' => 'rs_comma_dot_rtl',
		'fr-KM' => 'rs_comma_space_ltr',
	],
	'KN' => [
		'en-KN' => 'lx_dot_comma_ltr',
	],
	'KP' => [
		'ko-KP' => 'lx_dot_comma_ltr',
	],
	'KR' => [
		'ko' => 'lx_dot_comma_ltr',
	],
	'KW' => [
		'ar-KW' => 'rs_comma_dot_rtl',
	],
	'KY' => [
		'en-KY' => 'lx_dot_comma_ltr',
	],
	'KZ' => [
		'kk'    => 'rs_comma_space_ltr',
		'ru-KZ' => 'rs_comma_space_ltr',
	],
	'LA' => [
		'lo' => 'lx_comma_dot_ltr',
	],
	'LB' => [
		'ar-LB' => 'rs_comma_dot_rtl',
	],
	'LC' => [
		'en-LC' => 'lx_dot_comma_ltr',
	],
	'LI' => [
		'de-LI'  => 'rs_dot_apos_ltr',
		'gsw-LI' => 'rs_dot_apos_ltr',
	],
	'LK' => [
		'si'    => 'lx_dot_comma_ltr',
		'ta-LK' => 'lx_dot_comma_ltr',
	],
	'LR' => [
		'en-LR' => 'lx_dot_comma_ltr',
	],
	'LS' => [
		'en-LS' => 'lx_dot_comma_ltr',
		'st-LS' => 'lx_comma_space_ltr',
	],
	'LT' => [
		'lt' => 'rs_comma_space_ltr',
	],
	'LU' => [
		'de-LU' => 'rs_comma_dot_ltr',
		'fr-LU' => 'rs_comma_dot_ltr',
		'lb'    => 'rs_comma_dot_ltr',
	],
	'LV' => [
		'lv' => 'rs_comma_space_ltr',
	],
	'LY' => [
		'ar-LY' => 'rx_comma_dot_rtl',
	],
	'MA' => [
		'ar-MA' => 'rx_comma_dot_rtl',
		'fr-MA' => 'rs_comma_dot_ltr',
		'tzm'   => 'rs_comma_space_ltr',
	],
	'MC' => [
		'fr-MC' => 'rs_comma_space_ltr',
	],
	'MD' => [
		'ro-MD' => 'rs_comma_dot_ltr',
	],
	'ME' => [
		'sr-Latn' => 'rs_comma_dot_ltr',
	],
	'MF' => [
		'fr-MF' => 'rs_comma_space_ltr',
	],
	'MG' => [
		'en-MG' => 'lx_dot_comma_ltr',
		'fr-MG' => 'rs_comma_space_ltr',
		'mg'    => 'lx_dot_comma_ltr',
	],
	'MH' => [
		'en-MH' => 'lx_dot_comma_ltr',
	],
	'MK' => [
		'mk'    => 'rs_comma_dot_ltr',
		'sq-MK' => 'rs_comma_space_ltr',
	],
	'ML' => [
		'fr-ML' => 'rs_comma_space_ltr',
	],
	'MM' => [
		'my' => 'ls_dot_comma_ltr',
	],
	'MN' => [
		'mn' => 'ls_dot_comma_ltr',
	],
	'MO' => [
		'pt-MO'   => 'rs_comma_space_ltr',
		'zh-Hant' => 'lx_dot_comma_ltr',
	],
	'MP' => [
		'en-MP' => 'lx_dot_comma_ltr',
	],
	'MQ' => [
		'fr-MQ' => 'rs_comma_space_ltr',
	],
	'MR' => [
		'ar-MR' => 'rs_comma_dot_rtl',
	],
	'MS' => [
		'en-MS' => 'lx_dot_comma_ltr',
	],
	'MT' => [
		'en-MT' => 'lx_dot_comma_ltr',
		'mt'    => 'lx_dot_comma_ltr',
	],
	'MU' => [
		'en-MU' => 'lx_dot_comma_ltr',
		'fr-MU' => 'rs_comma_space_ltr',
	],
	'MV' => [
		'dv' => 'ls_dot_comma_rtl',
	],
	'MW' => [
		'en-MW' => 'lx_dot_comma_ltr',
		'ny'    => 'ls_dot_comma_ltr',
	],
	'MX' => [
		'es-MX' => 'lx_dot_comma_ltr',
		'vec'   => 'ls_dot_comma_ltr',
	],
	'MY' => [
		'ms' => 'lx_dot_comma_ltr',
	],
	'MZ' => [
		'pt-MZ' => 'rs_comma_space_ltr',
	],
	'NA' => [
		'en-NA' => 'lx_dot_comma_ltr',
	],
	'NC' => [
		'fr-NC' => 'rs_comma_space_ltr',
	],
	'NE' => [
		'fr-NE' => 'rs_comma_space_ltr',
	],
	'NF' => [
		'en-NF' => 'lx_dot_comma_ltr',
	],
	'NG' => [
		'en-NG' => 'lx_dot_comma_ltr',
		'yo'    => 'lx_dot_comma_ltr',
	],
	'NI' => [
		'es-NI' => 'lx_dot_comma_ltr',
	],
	'NL' => [
		'fy' => 'ls_comma_dot_ltr',
		'nl' => 'ls_comma_dot_ltr',
	],
	'NO' => [
		'nb' => 'ls_comma_space_ltr',
		'nn' => 'rs_comma_space_ltr',
		'no' => 'ls_comma_space_ltr',
		'se' => 'rs_comma_space_ltr',
	],
	'NP' => [
		'ne' => 'ls_dot_comma_ltr',
	],
	'NR' => [
		'en-NR' => 'lx_dot_comma_ltr',
	],
	'NU' => [
		'en-NU' => 'lx_dot_comma_ltr',
	],
	'NZ' => [
		'en-NZ' => 'lx_dot_comma_ltr',
		'mi'    => 'ls_dot_comma_ltr',
	],
	'OM' => [
		'ar-OM' => 'rs_comma_dot_rtl',
	],
	'PA' => [
		'es-PA' => 'lx_dot_comma_ltr',
	],
	'PE' => [
		'es-PE' => 'lx_dot_comma_ltr',
		'qu'    => 'ls_dot_comma_ltr',
	],
	'PF' => [
		'fr-PF' => 'rs_comma_space_ltr',
	],
	'PG' => [
		'en-PG' => 'lx_dot_comma_ltr',
		'tpi'   => 'rs_dot_comma_ltr',
	],
	'PH' => [
		'ceb'   => 'lx_dot_comma_ltr',
		'en-PH' => 'lx_dot_comma_ltr',
		'fil'   => 'lx_dot_comma_ltr',
	],
	'PK' => [
		'en-PK' => 'lx_dot_comma_ltr',
		'ur'    => 'lx_dot_comma_rtl',
	],
	'PL' => [
		'de' => 'rs_comma_dot_ltr',
		'lt' => 'rs_comma_space_ltr',
		'pl' => 'rs_comma_space_ltr',
	],
	'PM' => [
		'fr-PM' => 'rs_comma_space_ltr',
	],
	'PN' => [
		'en-PN' => 'lx_dot_comma_ltr',
	],
	'PR' => [
		'en-PR' => 'lx_dot_comma_ltr',
		'es-PR' => 'lx_dot_comma_ltr',
	],
	'PS' => [
		'ar-PS' => 'rs_comma_dot_rtl',
	],
	'PT' => [
		'pt-PT' => 'rs_comma_space_ltr',
	],
	'PW' => [
		'en-PW' => 'lx_dot_comma_ltr',
	],
	'PY' => [
		'es-PY' => 'lx_comma_dot_ltr',
		'gn'    => 'ls_dot_comma_ltr',
	],
	'QA' => [
		'ar-QA' => 'rs_comma_dot_rtl',
	],
	'RE' => [
		'fr-RE' => 'rs_comma_space_ltr',
	],
	'RO' => [
		'ro' => 'rs_comma_dot_ltr',
	],
	'RS' => [
		'hr'      => 'rs_comma_dot_ltr',
		'hu'      => 'rs_comma_space_ltr',
		'ro'      => 'rs_comma_dot_ltr',
		'sk'      => 'rs_comma_space_ltr',
		'sr'      => 'rs_comma_dot_ltr',
		'sr-Latn' => 'rs_comma_dot_ltr',
		'uk'      => 'rs_comma_space_ltr',
	],
	'RU' => [
		'az-Cyrl' => 'rs_comma_dot_ltr',
		'ba'      => 'ls_dot_comma_ltr',
		'ce'      => 'rs_dot_comma_ltr',
		'mdf'     => 'ls_dot_comma_ltr',
		'myv'     => 'ls_dot_comma_ltr',
		'ru'      => 'rs_comma_space_ltr',
		'sah'     => 'rs_comma_space_ltr',
		'tt'      => 'rs_comma_space_ltr',
	],
	'RW' => [
		'en-RW' => 'lx_dot_comma_ltr',
		'fr-RW' => 'rs_comma_space_ltr',
		'rw'    => 'ls_comma_dot_ltr',
	],
	'SA' => [
		'ar-SA' => 'rs_comma_dot_rtl',
	],
	'SB' => [
		'en-SB' => 'lx_dot_comma_ltr',
	],
	'SC' => [
		'en-SC' => 'lx_dot_comma_ltr',
		'fr-SC' => 'rs_comma_space_ltr',
	],
	'SD' => [
		'ar-SD' => 'rs_comma_dot_rtl',
		'en-SD' => 'lx_dot_comma_ltr',
	],
	'SE' => [
		'fi' => 'rs_comma_space_ltr',
		'sv' => 'rs_comma_space_ltr',
	],
	'SG' => [
		'en-SG' => 'lx_dot_comma_ltr',
		'ms-SG' => 'lx_dot_comma_ltr',
		'ta-SG' => 'lx_dot_comma_ltr',
		'zh'    => 'lx_dot_comma_ltr',
	],
	'SH' => [
		'en-SH' => 'lx_dot_comma_ltr',
	],
	'SI' => [
		'sl'  => 'rs_comma_dot_ltr',
		'vec' => 'ls_dot_comma_ltr',
	],
	'SJ' => [
		'nb-SJ' => 'ls_comma_space_ltr',
	],
	'SK' => [
		'sk' => 'rs_comma_space_ltr',
	],
	'SL' => [
		'en-SL' => 'lx_dot_comma_ltr',
	],
	'SM' => [
		'it-SM' => 'rs_comma_dot_ltr',
	],
	'SN' => [
		'dyo'   => 'rs_comma_space_ltr',
		'ff'    => 'rs_comma_space_ltr',
		'fr-SN' => 'rs_comma_space_ltr',
		'wo'    => 'ls_comma_dot_ltr',
	],
	'SO' => [
		'ar-SO' => 'rs_comma_dot_rtl',
		'so'    => 'lx_dot_comma_ltr',
	],
	'SR' => [
		'nl-SR' => 'ls_comma_dot_ltr',
	],
	'SS' => [
		'en-SS' => 'lx_dot_comma_ltr',
	],
	'ST' => [
		'pt-ST' => 'rs_comma_space_ltr',
	],
	'SV' => [
		'es-SV' => 'lx_dot_comma_ltr',
	],
	'SX' => [
		'en-SX' => 'lx_dot_comma_ltr',
		'nl-SX' => 'ls_comma_dot_ltr',
	],
	'SY' => [
		'ar-SY' => 'rs_comma_dot_rtl',
		'fr-SY' => 'rs_comma_space_ltr',
	],
	'SZ' => [
		'en-SZ' => 'lx_dot_comma_ltr',
		'ss-SZ' => 'lx_comma_space_ltr',
	],
	'TA' => [
		'en' => 'lx_dot_comma_ltr',
	],
	'TC' => [
		'en-TC' => 'lx_dot_comma_ltr',
	],
	'TD' => [
		'ar-TD' => 'rs_comma_dot_rtl',
		'fr-TD' => 'rs_comma_space_ltr',
	],
	'TF' => [
		'fr' => 'rs_comma_space_ltr',
	],
	'TG' => [
		'fr-TG' => 'rs_comma_space_ltr',
	],
	'TH' => [
		'th' => 'lx_dot_comma_ltr',
	],
	'TJ' => [
		'tg' => 'rs_comma_space_ltr',
	],
	'TK' => [
		'en-TK' => 'lx_dot_comma_ltr',
	],
	'TL' => [
		'pt-TL' => 'rs_comma_space_ltr',
	],
	'TM' => [
		'tk' => 'rs_comma_space_ltr',
	],
	'TN' => [
		'ar-TN' => 'rx_comma_dot_rtl',
		'fr-TN' => 'rs_comma_space_ltr',
	],
	'TO' => [
		'en-TO' => 'lx_dot_comma_ltr',
		'to'    => 'ls_dot_comma_ltr',
	],
	'TR' => [
		'tr' => 'lx_comma_dot_ltr',
	],
	'TT' => [
		'en-TT' => 'lx_dot_comma_ltr',
	],
	'TV' => [
		'en-TV' => 'lx_dot_comma_ltr',
	],
	'TW' => [
		'zh-Hant' => 'lx_dot_comma_ltr',
	],
	'TZ' => [
		'en-TZ' => 'lx_dot_comma_ltr',
		'sw'    => 'ls_dot_comma_ltr',
	],
	'UA' => [
		'ru-UA' => 'rs_comma_space_ltr',
		'uk'    => 'rs_comma_space_ltr',
	],
	'UG' => [
		'en-UG' => 'lx_dot_comma_ltr',
		'sw-UG' => 'ls_dot_comma_ltr',
	],
	'UM' => [
		'en-UM' => 'lx_dot_comma_ltr',
	],
	'US' => [
		'en'    => 'lx_dot_comma_ltr',
		'es-US' => 'lx_dot_comma_ltr',
		'haw'   => 'lx_dot_comma_ltr',
	],
	'UY' => [
		'es-UY' => 'ls_comma_dot_ltr',
	],
	'UZ' => [
		'uz'      => 'lx_comma_space_ltr',
		'uz-Cyrl' => 'rs_comma_space_ltr',
	],
	'VA' => [
		'it-VA' => 'rs_comma_dot_ltr',
	],
	'VC' => [
		'en-VC' => 'lx_dot_comma_ltr',
	],
	'VE' => [
		'es-VE' => 'lx_comma_dot_ltr',
	],
	'VG' => [
		'en-VG' => 'lx_dot_comma_ltr',
	],
	'VI' => [
		'en-VI' => 'lx_dot_comma_ltr',
	],
	'VN' => [
		'vi' => 'rs_comma_dot_ltr',
	],
	'VU' => [
		'en-VU' => 'lx_dot_comma_ltr',
		'fr-VU' => 'rs_comma_space_ltr',
	],
	'WF' => [
		'fr-WF' => 'rs_comma_space_ltr',
	],
	'WS' => [
		'en-WS' => 'lx_dot_comma_ltr',
	],
	'XK' => [
		'sq-XK'   => 'rs_comma_space_ltr',
		'sr'      => 'rs_comma_dot_ltr',
		'sr-Latn' => 'rs_comma_dot_ltr',
	],
	'YE' => [
		'ar-YE' => 'rs_comma_dot_rtl',
	],
	'YT' => [
		'fr-YT' => 'rs_comma_space_ltr',
	],
	'ZA' => [
		'af'    => 'lx_comma_space_ltr',
		'en-ZA' => 'lx_dot_comma_ltr',
		'nr'    => 'lx_comma_space_ltr',
		'nso'   => 'lx_dot_space_ltr',
		'ss'    => 'lx_comma_space_ltr',
		'st'    => 'lx_comma_space_ltr',
		'tn'    => 'lx_dot_space_ltr',
		'ts'    => 'ls_comma_space_ltr',
		've'    => 'lx_comma_space_ltr',
		'xh'    => 'lx_dot_space_ltr',
		'zu'    => 'lx_dot_comma_ltr',
	],
	'ZM' => [
		'en-ZM' => 'lx_dot_comma_ltr',
	],
	'ZW' => [
		'en-ZW' => 'lx_dot_comma_ltr',
		'nd'    => 'lx_dot_comma_ltr',
		'sn'    => 'lx_dot_comma_ltr',
	],
	'ZZ' => [
		'en-ZW' => 'lx_dot_comma_ltr',
		'nd'    => 'lx_dot_comma_ltr',
		'sn'    => 'lx_dot_comma_ltr',
	],
];
