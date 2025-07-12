<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions Mapping
    |--------------------------------------------------------------------------
    |
    | خريطة الأدوار إلى الصلاحيات. تأكد أن مفاتيح المصفوفة هنا
    | هي أسماء الأدوار المسجلة في Spatie (admin, supervisor, accountant, pos).
    |
    */

    'admin' => [
        'view_dashboard',
        'manage_admins',
        'manage_accountants',
        'manage_pos',
        'view_full_reports',
        'system_config',
        'export_reports',
    ],

    'supervisor' => [
        'view_dashboard',
        'view_assigned_pos',
        'view_sales_reports',
        'view_recharge_reports',
        'manage_assigned_pos',
    ],

    'accountant' => [
        'view_dashboard',
        'create_pos_account',
        'recharge_pos_balance',
        'manage_invoices',
        'view_financial_reports',
        'export_financial_reports',
    ],

    'pos' => [
        'view_dashboard',
        'generate_new_card',
        'recharge_existing_card',
        'view_own_sales',
        'print_cards',
    ],

];
