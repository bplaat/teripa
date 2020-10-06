<?php

function getIP(): string {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Validate

function request (string $key, $default = '') {
    return $_REQUEST[$key] ?? $default;
}

foreach ($_REQUEST as $key => $value) {
    Session::flash('old_' . $key, $value);
}

function old (string $key, $default = '') {
    return Session::get('old_' . $key, $default);
}

function validate (array $values) {
    $errors = [];

    foreach ($values as $key => $value) {
        $string = request($key);
        $rules = explode('|', $value);
        foreach ($rules as $rule) {
            if (substr($rule, 0, 1) == '@') {
                $error = call_user_func(substr($rule, 1), $key, $string);
                if (is_string($error)) {
                    $errors[] = $error;
                }
            } else {
                $parts = explode(':', $rule);
                $args = isset($parts[1]) ? explode(',', $parts[1]) : [];
                if ($parts[0] == 'required' && $string == '') {
                    $errors[] = 'The ' . $key . ' field is required';
                }
                if ($parts[0] == 'int' && !is_numeric($string) && $string != round($string)) {
                    $errors[] = 'The ' . $key . ' field must be a rounded number';
                }
                if ($parts[0] == 'float' && !is_numeric($string)) {
                    $errors[] = 'The ' . $key . ' field must be a number';
                }
                if ($parts[0] == 'email' && !filter_var($string, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'The ' . $key . ' field must be an email address';
                }
                if ($parts[0] == 'url' && !filter_var($string, FILTER_VALIDATE_URL)) {
                    $errors[] = 'The ' . $key . ' field must be an url';
                }
                if ($parts[0] == 'date' && strtotime($string) == false) {
                    $errors[] = 'The ' . $key . ' field must be an date';
                }
                if ($parts[0] == 'number_min' && $string < $args[0]) {
                    $errors[] = 'The ' . $key . ' field must be at least ' . $args[0] . ' or higher';
                }
                if ($parts[0] == 'number_max' && $string > $args[0]) {
                    $errors[] = 'The ' . $key . ' field must be a maximum of ' . $args[0] . ' or lower';
                }
                if ($parts[0] == 'number_between' && $string < $args[0] && $string > $args[1]) {
                    $errors[] = 'The ' . $key . ' field must be between ' . $args[0] . ' and ' . $args[1];
                }
                if ($parts[0] == 'confirmed' && $string != request($key . '_confirmation')) {
                    $errors[] = 'The ' . $key . ' fields must be the same';
                }
                if ($parts[0] == 'min' && strlen($string) < $args[0]) {
                    $errors[] = 'The ' . $key . ' field must be at least ' . $args[0] . ' characters long';
                }
                if ($parts[0] == 'max' && strlen($string) > $args[0]) {
                    $errors[] = 'The ' . $key . ' field can be a maximum of ' . $args[0] . ' characters';
                }
                if ($parts[0] == 'size' && strlen($string) != $args[0]) {
                    $errors[] = 'The ' . $key . ' field must be ' . $args[0] . ' characters long';
                }
                if ($parts[0] == 'same' && $string != request($args[0])) {
                    $errors[] = 'The ' . $key . ' field must be the same as the ' . $args[0] . ' field';
                }
                if ($parts[0] == 'different' && $string == request($args[0])) {
                    $errors[] = 'The ' . $key . ' field must be different as the ' . $args[0] . ' field';
                }
                if ($parts[0] == 'exists' && call_user_func($args[0] . '::select', [ ($args[1] ?? $key) => $string ])->rowCount() != 1) {
                    $errors[] = 'The ' . $key . ' field must refer to something that exists';
                }
                if ($parts[0] == 'unique' && call_user_func($args[0] . '::select', [ ($args[1] ?? $key) => $string ])->rowCount() != 0) {
                    $errors[] = 'The ' . $key . ' field must be unqiue';
                }
            }
        }
    }

    if (count($errors) > 0) {
        Session::flash('errors', $errors);
        Router::back();
    }
}

// View

function minify_html (string $data) {
    return preg_replace(
        [ '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s' ],
        [ '>', '<', '\\1' ],
        $data
    );
}

function view (string $_path, array $_data = []) {
    extract($_data);
    unset($_data);
    ob_start();
    eval('unset($_path) ?>' . preg_replace(
        ['/^\s*@view\((.*)\)/m', '/^\s*@(.*)/m', '/{{(.*)}}/U', '/{!!(.*)!!}/U'],
        ['<?php echo view($1) ?>', '<?php $1 ?>', '<?php echo htmlspecialchars($1, ENT_QUOTES, \'UTF-8\') ?>', '<?php echo $1 ?>'],
        file_get_contents(ROOT . '/views/' . str_replace('.', '/', $_path) . '.html')
    ));
    $html = ob_get_contents();
    ob_end_clean();
    return minify_html($html);
}

// Country codes
$countryCodes = [
    'AF' => 'Afghanistan',
    'AL' => 'Albania',
    'DZ' => 'Algeria',
    'AS' => 'American Samoa',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AI' => 'Anguilla',
    'AQ' => 'Antarctica',
    'AG' => 'Antigua and Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AW' => 'Aruba',
    'AU' => 'Australia',
    'AT' => 'Austria',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BE' => 'Belgium',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BM' => 'Bermuda',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia',
    'BA' => 'Bosnia and Herzegovina',
    'BW' => 'Botswana',
    'BV' => 'Bouvet Island',
    'BR' => 'Brazil',
    'BQ' => 'British Antarctic Territory',
    'IO' => 'British Indian Ocean Territory',
    'VG' => 'British Virgin Islands',
    'BN' => 'Brunei',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CA' => 'Canada',
    'CT' => 'Canton and Enderbury Islands',
    'CV' => 'Cape Verde',
    'KY' => 'Cayman Islands',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CX' => 'Christmas Island',
    'CC' => 'Cocos [Keeling] Islands',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo - Brazzaville',
    'CD' => 'Congo - Kinshasa',
    'CK' => 'Cook Islands',
    'CR' => 'Costa Rica',
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'CI' => 'Côte d’Ivoire',
    'DK' => 'Denmark',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'NQ' => 'Dronning Maud Land',
    'DD' => 'East Germany',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FK' => 'Falkland Islands',
    'FO' => 'Faroe Islands',
    'FJ' => 'Fiji',
    'FI' => 'Finland',
    'FR' => 'France',
    'GF' => 'French Guiana',
    'PF' => 'French Polynesia',
    'TF' => 'French Southern Territories',
    'FQ' => 'French Southern and Antarctic Territories',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia',
    'DE' => 'Germany',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GR' => 'Greece',
    'GL' => 'Greenland',
    'GD' => 'Grenada',
    'GP' => 'Guadeloupe',
    'GU' => 'Guam',
    'GT' => 'Guatemala',
    'GG' => 'Guernsey',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HM' => 'Heard Island and McDonald Islands',
    'HN' => 'Honduras',
    'HK' => 'Hong Kong SAR China',
    'HU' => 'Hungary',
    'IS' => 'Iceland',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran',
    'IQ' => 'Iraq',
    'IE' => 'Ireland',
    'IM' => 'Isle of Man',
    'IL' => 'Israel',
    'IT' => 'Italy',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JE' => 'Jersey',
    'JT' => 'Johnston Island',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => 'Laos',
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MO' => 'Macau SAR China',
    'MK' => 'Macedonia',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MH' => 'Marshall Islands',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'YT' => 'Mayotte',
    'FX' => 'Metropolitan France',
    'MX' => 'Mexico',
    'FM' => 'Micronesia',
    'MI' => 'Midway Islands',
    'MD' => 'Moldova',
    'MC' => 'Monaco',
    'MN' => 'Mongolia',
    'ME' => 'Montenegro',
    'MS' => 'Montserrat',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'MM' => 'Myanmar [Burma]',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NL' => 'Netherlands',
    'AN' => 'Netherlands Antilles',
    'NT' => 'Neutral Zone',
    'NC' => 'New Caledonia',
    'NZ' => 'New Zealand',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'NU' => 'Niue',
    'NF' => 'Norfolk Island',
    'KP' => 'North Korea',
    'VD' => 'North Vietnam',
    'MP' => 'Northern Mariana Islands',
    'NO' => 'Norway',
    'OM' => 'Oman',
    'PC' => 'Pacific Islands Trust Territory',
    'PK' => 'Pakistan',
    'PW' => 'Palau',
    'PS' => 'Palestinian Territories',
    'PA' => 'Panama',
    'PZ' => 'Panama Canal Zone',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'YD' => 'People\'s Democratic Republic of Yemen',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PN' => 'Pitcairn Islands',
    'PL' => 'Poland',
    'PT' => 'Portugal',
    'PR' => 'Puerto Rico',
    'QA' => 'Qatar',
    'RO' => 'Romania',
    'RU' => 'Russia',
    'RW' => 'Rwanda',
    'RE' => 'Réunion',
    'BL' => 'Saint Barthélemy',
    'SH' => 'Saint Helena',
    'KN' => 'Saint Kitts and Nevis',
    'LC' => 'Saint Lucia',
    'MF' => 'Saint Martin',
    'PM' => 'Saint Pierre and Miquelon',
    'VC' => 'Saint Vincent and the Grenadines',
    'WS' => 'Samoa',
    'SM' => 'San Marino',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'CS' => 'Serbia and Montenegro',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SK' => 'Slovakia',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'GS' => 'South Georgia and the South Sandwich Islands',
    'KR' => 'South Korea',
    'ES' => 'Spain',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SJ' => 'Svalbard and Jan Mayen',
    'SZ' => 'Swaziland',
    'SE' => 'Sweden',
    'CH' => 'Switzerland',
    'SY' => 'Syria',
    'ST' => 'São Tomé and Príncipe',
    'TW' => 'Taiwan',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania',
    'TH' => 'Thailand',
    'TL' => 'Timor-Leste',
    'TG' => 'Togo',
    'TK' => 'Tokelau',
    'TO' => 'Tonga',
    'TT' => 'Trinidad and Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TC' => 'Turks and Caicos Islands',
    'TV' => 'Tuvalu',
    'UM' => 'U.S. Minor Outlying Islands',
    'PU' => 'U.S. Miscellaneous Pacific Islands',
    'VI' => 'U.S. Virgin Islands',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'SU' => 'Union of Soviet Socialist Republics',
    'AE' => 'United Arab Emirates',
    'GB' => 'United Kingdom',
    'US' => 'United States',
    'ZZ' => 'Unknown or Invalid Region',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VA' => 'Vatican City',
    'VE' => 'Venezuela',
    'VN' => 'Vietnam',
    'WK' => 'Wake Island',
    'WF' => 'Wallis and Futuna',
    'EH' => 'Western Sahara',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe',
    'AX' => 'Åland Islands',
    '??' => '?'
];

function countryName(string $code) {
    global $countryCodes;
    return $countryCodes[$code] ?? $code;
}
