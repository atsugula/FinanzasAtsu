// Nombre de los inputs
const input1 = "paid";
const input2 = "amount";
const input3 = "payable";
// Función para verificar si un valor es un número
function esNumero(valor) {
    return !isNaN(parseFloat(valor)) && isFinite(valor);
}

// Función para restar los dos valores
function restar() {
    // Obtener los valores de los inputs
    var input_paid = document.getElementById(input1).value; // Valor que estoy registrando a pagar
    var input_amount = document.getElementById(input2).value; // Valor de la deuda

    // Verificar si ambos valores son numéricos
    if (esNumero(input_paid) && esNumero(input_amount)) {
        // Restar los valores y mostrar el resultado
        var resultado = parseFloat(input_amount) - parseFloat(input_paid);
        document.getElementById(input3).value = resultado;
    } else {
        // Mostrar un mensaje de error si alguno de los valores no es un número
        document.getElementById(input3).value = "Ingrese valores numéricos válidos";
    }
}

// Agregar evento de teclado a los inputs
document.getElementById(input1).addEventListener("keyup", restar);