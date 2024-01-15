<?php

namespace App\Traits;

use \Illuminate\Support\Facades\Log;

trait Backup
{

    public static function generar(){

        // Variables para la conexion a la base de datos, mysqldump
        $db_host=env('DB_HOST', 'localhost');
        $db_name=env('DB_DATABASE', 'serviego');
        $db_user=env('DB_USERNAME', 'root');
        $db_password=env('DB_PASSWORD', '');

        // Para identificar nuestro respaldo
        $fecha = date('Ymd-His');

        // Ruta donde quedara almacenada
        $path = '../database/backups/';

        // Como se guarda nuestro respaldo
        $salida_sql = $path . $db_name . '_' . $fecha . '.sql';

        // Instrucciones
        $dump = "mysqldump -h $db_host -u $db_user --password=$db_password --opt $db_name > $salida_sql";

        // Ejecutamos nuestra instruccion
        // system($dump, $output);
        exec($dump, $output, $resultCode);

        // Manejo de errores
        if ($resultCode !== 0) {
            // Ocurrió un error
            Log::error("Error al generar el respaldo: $resultCode");
            return "Error al generar el respaldo: $resultCode";
        }

        return $output;

    }

}
