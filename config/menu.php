<?php

return [
    [
        'text' => 'Dashboard',
        'route' => 'dashboard',
        'icon' => 'ni ni-tv-2',
        'page' => 0,
    ],
    [
        'text' => 'Movimientos',
        'icon' => 'fa fa-exchange-alt',
        'submenu' => [
            [
                'text' => 'Ver movimientos',
                'route' => 'transactions.index',
                'icon' => 'fa fa-list',
                'page' => 0,
            ],
            [
                'text' => 'Agregar movimiento',
                'route' => 'transactions.create',
                'icon' => 'fa fa-plus-circle',
                'page' => 0,
            ],
        ],
    ],
    [
        'text' => 'Cuentas',
        'route' => 'accounts.index',
        'icon' => 'fa fa-wallet',
        'page' => 0,
    ],
    [
        'text' => 'Categorías',
        'route' => 'categories.index',
        'icon' => 'fa fa-tags',
        'page' => 0,
    ],
    [
        'text' => 'Ajustes',
        'icon' => 'fa fa-cog',
        'submenu' => [
            [
                'text' => 'Preferencias',
                'route' => 'settings.edit',
                'icon' => 'fa fa-sliders-h',
                'page' => 0,
            ],
            /* [
                'text' => 'Exportar CSV',
                'route' => 'settings.export.csv',
                'icon' => 'fa fa-file-export',
                'page' => 0,
            ],
            [
                'text' => 'Importar CSV',
                'route' => 'settings.edit', // la subida está dentro de Ajustes (form)
                'icon' => 'fa fa-file-import',
                'page' => 0,
            ], */
        ],
    ],
];
