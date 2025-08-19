<?php

$userTransactionTypes = [
    'income'    => true,
    'expense'   => true,
    'debt_in'   => true,
    'debt_out'  => true,
];

return [
    [
        'text' => 'Profile',
        'route' => 'profile',
        'icon'  => 'ni ni-single-02',
        'page'  => 0,
    ],
    [
        'text' => 'Goals',
        'route' => 'goals.index',
        'icon'  => 'fa fa-bullseye',
        'page'  => 0,
    ],
    [
        'text' => 'Categories',
        'route' => 'categories.index',
        'icon'  => 'fa fa-tags',
        'page'  => 0,
    ],
    [
        'text'    => 'Transactions',
        'icon'    => 'fa fa-exchange-alt',
        'submenu' => array_filter([
            $userTransactionTypes['income'] ? [
                'text'  => 'Incomes',
                'route' => 'transactions.index',
                'params'=> ['type' => 'income'],
                'icon'  => 'fa fa-arrow-down',
                'page'  => 0,
            ] : null,
            [
                'text'  => 'Add Transaction',
                'route' => 'transactions.create',
                'icon'  => 'fa fa-plus-circle',
            ],
            /* [
                'text'  => 'Import',
                'route' => 'transactions.import',
                'icon'  => 'fa fa-file-import',
            ],
            [
                'text'  => 'Export',
                'route' => 'transactions.export',
                'icon'  => 'fa fa-file-export',
            ], */
        ]),
    ],
];
