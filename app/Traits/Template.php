<?php

namespace App\Traits;

use App\Models\User;
use App\Models\V1\Expense;
use Illuminate\Http\Request;
use App\Models\V1\PaymentsHistory;

trait Template
{
    //script para subir o mover la imagen
    public static function moveImage(Request $request, $filename, $endFilename){

        if (!empty($request->hasFile($filename))) {
            $image = $request->file($filename);
            $basePath = "img/config/";
            $file = $endFilename ?? $filename;
            $path = "$basePath$file." . $image->guessExtension();
            if (file_exists($path)) unlink($path);
            $route = public_path($basePath);
            $image->move($route, $file . '.' . $image->guessExtension());
            return $path;
        } else {
            return 'NULL';
        }
    }

    //Traer usuario logeado
    public function traerUsuario($id){
        $user = User::find($id);
        if(empty($user)){
            return null;
        }
        return $user;
    }

    //Traemos el nombre del tenant
    public function traerNombre(){
        $url = $_SERVER['HTTP_HOST'];
        $partes = explode('.', $url);
        return $partes[0];
    }

    //Preguntar configuracion de usuario logeado
    public function traerConfiguracion($user){
        $respuesta = $user['config'] == '1' ? true : false;
        return $respuesta;
    }

    //Se elimina imagenes
    public static function deleteImage($path){
        if (file_exists($path)) unlink($path);
    }
    /**
    * It takes a JSON string and returns an array
    *
    * @param lista The list of items you want to decode.
    *
    * @return the decoded list.
    */
    public function decodificar($lista){
        $listaDecodificada = json_decode($lista, true);
        return $listaDecodificada;
    }

    public function payment_update($data){
        // Buscamos status del expense
        $expense = Expense::find($data['expense_id']);
        // Buscamos los pagos
        $payments = PaymentsHistory::where('expense_id', $expense->id)->get();
        // Monto pagado
        $count_payment = 0;
        // Sumamos todos los pagos
        foreach ($payments as $key => $payment) {
            $count_payment += $payment->paid;
        }
        // Restamos y hacemos cuanto se quedo debiendo
        $balance_due = $expense->amount - $count_payment;
        // Si el monto es mayor, quiere decir que hay saldo pendiente por pagar, lo ponemos en proceso de pago
        if ($expense->amount > $balance_due) {
            $expense->status = config('status.ENPROC');
            $expense->save();
        }
        // Si el monto restante es menor, quiere decir que ya pagamos, lo ponemos en aprobado
        if (($expense->amount <= $balance_due || $balance_due == 0) && count($payments) > 0) {
            $expense->status = config('status.APR');
            $expense->save();
        }
        // Si se modifica desde el original y no hay pagos, dejamos el status por defecto
        if (!empty($data['status_origin'])) {
            if ($data['status_origin'] == 'home' && count($payments) == 0) {
                $expense->status = $data['status'];
                $expense->save();
            }
        }

        return [
            'balance_due' => $balance_due,
            'count_payment' => $count_payment,
            'expense_amount' => $expense->amount,
        ];

    }

}
