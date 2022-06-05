<?php
define("MODULE_KPI_USER_RULE", "KPI_RULE");
define("MODULE_KPI_DATA_STORE", "DATA_STORE");

define("ISSUE_STATUS_NAME", [
    "NEW" => "Mới",
    "IN-PROGRESS" => "Đang tiến hành",
    "SOLVED" => "Đã giải quyết",
    "OPENED" => "Mở lại ",
    "COMPLETED" => "Đã hoàn thành",
]);

define("IN-PROGRESS", "IN-PROGRESS");
define("COMPLETED", "COMPLETED");
define("OPENED", "OPENED");
define("SOLVED", "SOLVED");
define("HOURS", "H");
define("MINUTE", "M");
define("HIGHEST", "HIGHEST");
define("HIGH", "HIGH");
define("MEDIUM", "MEDIUM");
define("LOW", "LOW");
define("LOWEST", "LOWEST");
define("ISSUE_PRIORITY_NAME", [
    "HIGHEST" => "Rất cao",
    "HIGH" => "Cao",
    "MEDIUM" => "Trung bình",
    "LOW" => "Thấp",
    "LOWEST" => "Rất thấp",
]);
define("USER_TYPE_SELLER", "SELLER");
define("USER_TYPE_USER", "USER");
define("USER_TYPE_CUSTOMER", "CUSTOMER");

define("USER_ROLE_ADMIN", "ADMIN");

define("SETTING_TYPES", ["PUN", "REW"]);

define("ORDER_TYPE_NORMAL", "1");
define("ORDER_TYPE_CONSIGNMENT", "2");
define("ORDER_TYPE_REFUND", "3");
return [
    'URL_IMG' => 'Media/IMG',
    'STATUS' => [
        'GENRE' => [
            'M' => 'Male',
            'F' => 'Female',
            'O' => 'Other',
        ],
    ],
    'BLOOD_GROUP' => [
        'TYPE' => [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
        ]
    ],
    'SESSIONTIMEOUT' => [
        'SESSION' => [
            'M' => 'Buổi sáng',
            'A' => 'Buổi chiều',
            'E' => 'Buổi tối'
        ]
    ],
    'url' => [
        'url_source' => "http://2nong.vn/#/truy-xuat-nguon-goc/",
    ],
    'customer_password_default' => 'Ssc@123456',
    'EXCEL' => [
        'CHAR' => [
            "",
            "A",
            "B",
            "C",
            "D",
            "E",
            "F",
            "G",
            "H",
            "I",
            "J",
            "K",
            "L",
            "M",
            "N",
            "O",
            "P",
            "Q",
            "R",
            "S",
            "T",
            "U",
            "V",
            "W",
            "X",
            "Y",
            "Z",

            "AA",
            "AB",
            "AC",
            "AD",
            "AE",
            "AF",
            "AG",
            "AH",
            "AI",
            "AJ",
            "AK",
            "AL",
            "AM",
            "AN",
            "AO",
            "AP",
            "AQ",
            "AR",
            "AS",
            "AT",
            "AU",
            "AV",
            "AW",
            "AX",
            "AY",
            "AZ",

            "BA",
            "BB",
            "BC",
            "BD",
            "BE",
            "BF",
            "BG",
            "BH",
            "BI",
            "BJ",
            "BK",
            "BL",
            "BM",
            "BN",
            "BO",
            "BP",
            "BQ",
            "BR",
            "BS",
            "BT",
            "BU",
            "BV",
            "BW",
            "BX",
            "BY",
            "BZ",

            "CA",
            "CB",
            "CC",
            "CD",
            "CE",
            "CF",
            "CG",
            "CH",
            "CI",
            "CJ",
            "CK",
            "CL",
            "CM",
            "CN",
            "CO",
            "CP",
            "CQ",
            "CR",
            "CS",
            "CT",
            "CU",
            "CV",
            "CW",
            "CX",
            "CY",
            "CZ",
        ],
    ],

    // Foreign Tables
    'FT' => [
        'users' => [
            'profiles.user_id' => 'Profile',
            'user_companies.user_id' => 'User Company',
            'kpi_users.user_id' => 'Kpi User',
        ],
        'states' => ['companies.state_id' => 'Company'],
        'countries' => ['companies.country_id' => 'Company', 'states.country_id' => 'State'],
        'currencies' => ['companies.currency_id' => 'Company'],
        'permission_groups' => ['permissions.group_id' => 'Permission'],
        'permissions' => ['role_permissions.permission_id' => 'Role Permission'],
        'roles' => ['role_permissions.role_id' => 'Role Permission'],
    ],
];
