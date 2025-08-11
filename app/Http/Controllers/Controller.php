<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Evitar que un usuario acceda a registros ajenos.
     */
    public function authorizeByCreator($model)
    {
        if (auth()->id() !== $model->created_by) {
            abort(403, 'No tienes permiso para acceder a esto.');
        }
    }

}
