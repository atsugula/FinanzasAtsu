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
            // Si los vas a dejar visibles en menú (si no, muévelos a Ajustes)
            [
                'text' => 'Importar',
                'route' => 'transactions.import',
                'icon' => 'fa fa-file-import',
                'page' => 0,
            ],
            [
                'text' => 'Exportar',
                'route' => 'transactions.export',
                'icon' => 'fa fa-file-export',
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
        // si realmente es opcional en tu MVP, puedes apagarlo con una flag:
        // 'visible' => true,
    ],

    [
        'text' => 'Ajustes',
        'route' => 'settings.edit',
        'icon' => 'fa fa-cog',
        'page' => 0,
    ],

    // Si necesitas perfil sí o sí en menú lateral:
    // [
    //     'text'  => 'Perfil',
    //     'route' => 'profile',
    //     'icon'  => 'ni ni-single-02',
    //     'page'  => 0,
    // ],
];
