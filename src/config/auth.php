<?php
return [
    'defaults' => [
    ],

    'guards' => [
        'admin_api' => [
            'driver' => 'passport',
            'provider' => 'admins',
        ],
        'company_api' => [
            'driver' => 'passport',
            'provider' => 'company',
        ],
        'employee_api' => [
            'driver' => 'passport',
            'provider' => 'employee',
        ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Admin::class
        ],
        'company' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Company::class
        ],
        'employee' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Employee::class
        ]
    ]
];
?>