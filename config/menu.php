<?php

return [
    [
        'text' => 'Profile',
        'route' => 'profile',
        'icon' => 'ni ni-single-02',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ],
    [
        'text' => 'Goals',
        'route' => 'goals.index',
        'icon' => 'fa fa-calendar',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ],
    [
        'text'    => 'Transactions',
        'icon'    => 'fa fa-archive',
        'submenu' => [
            [
                'text' => 'List',
                'route' => 'transactions.index',
                'icon' => 'fa fa-calendar',
                'page' => 0, // Para saber si la ruta es por parametro post o no
            ],
            [
                'text' => 'Create Transaction',
                'route' => 'transactions.create',
                'icon' => 'fa fa-plus-square',
            ],
            [
                'text' => 'Import transactions',
                'route' => 'transactions.import',
                'icon' => 'fa fa-plus-circle',
            ],
            [
                'text' => 'Export transactions',
                'route' => 'transactions.export',
                'icon' => 'fa fa-download',
            ],
        ],
    ],
    /* [
        'text' => 'Transactions',
        'route' => 'transactions.index',
        'icon' => 'fa fa-calendar',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ], */
    /* [
        'text' => 'Savings',
        'route' => 'savings.index',
        'icon' => 'fa fa-credit-card',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ], */
    [
        'text' => 'Partners',
        'route' => 'partners.index',
        'icon' => 'ni ni-badge',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ],
    /* [
        'text' => 'Incomes',
        'route' => 'incomes.index',
        'icon' => 'fa fa-money-bill',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ], */
    [
        'text' => 'Expenses Category',
        'route' => 'expenses-categories.index',
        'icon' => 'fa fa-th-large',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ],
    /* [
        'text' => 'Expenses',
        'route' => 'expenses.index',
        'icon' => 'fa fa-credit-card',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ], */
    [
        'text' => 'Pay expenses',
        'route' => 'payment-expenses.index',
        'icon' => 'ni ni-money-coins',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ],
    [
        'text' => 'Payments History',
        'route' => 'payments-histories.index',
        'icon' => 'fa fa-history',
        'page' => 0, // Para saber si la ruta es por parametro post o no
    ],
];
