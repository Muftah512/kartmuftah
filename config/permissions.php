<?php

return [
    'roles' => [
        'المدير العام' => [
            'view_dashboard',
            'manage_admins',
            'manage_accountants',
            'manage_pos',
            'view_full_reports',
            'system_config',
            'export_reports'
        ],
        'المشرف' => [
            'view_dashboard',
            'view_assigned_pos',
            'view_sales_reports',
            'view_recharge_reports',
            'manage_assigned_pos'
        ],
        'المحاسب' => [
            'view_dashboard',
            'create_pos_account',
            'recharge_pos_balance',
            'manage_invoices',
            'view_financial_reports',
            'export_reports'
        ],
        'نقطة البيع' => [
            'view_dashboard',
            'generate_new_card',
            'recharge_existing_card',
            'view_own_sales',
            'print_cards'
        ]
    ]
];