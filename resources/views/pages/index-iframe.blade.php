<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calculadora de IMC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <style>
        .float-left {
            float: left !important;
        }

        .float-right {
            float: right !important;
        }

        .float-none {
            float: none !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-2"></div>
            <div class="col-12 col-md-8">
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Calculadora de IMC</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="https://meducevacardiologia.blogspot.com/">
                                Volver</a>
                        </div>
                        <!-- <h1>Calculadora de IMC</h1> -->
                    </div>
                    <div class="card-body">
                        <form id="imcForm">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label for="peso">Peso (kg):</label>
                                    <input type="text" id="peso" name="weight" required class="form-control" />
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="altura">Altura (m):</label>
                                    <input type="text" id="altura" name="height" required class="form-control" />
                                </div>
                                <button type="submit" class="btn btn-primary btn-block mt-4">
                                    Calcular
                                </button>
                            </div>
                        </form>
                        <p class="result" id="result"></p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2"></div>
        </div>
    </div>

    <div class="container"></div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        document
            .getElementById("imcForm")
            .addEventListener("submit", function (event) {
                event.preventDefault();

                var weight = parseFloat(document.getElementById("peso").value);
                var height = parseFloat(document.getElementById("altura").value);

                var imc = weight / (height * height);

                var result = "";

                if (isNaN(imc)) {
                    result = "Por favor, ingresa valores válidos.";
                    return;
                }

                var interpretation;
                if (imc < 18.5) {
                    interpretation = "Infrapeso";
                } else if (imc >= 18.5 && imc < 25) {
                    interpretation = "Peso normal";
                } else {
                    interpretation = "Sobrepeso";
                }

                result =
                    "Tu IMC es: " +
                    imc.toFixed(2) +
                    ". Esto indica " +
                    interpretation +
                    ".";

                Swal.fire({
                    title: result,
                    showClass: {
                        popup: `
                            animate__animated
                            animate__fadeInUp
                            animate__faster
                        `,
                    },
                    hideClass: {
                        popup: `
                            animate__animated
                            animate__fadeOutDown
                            animate__faster
                        `,
                    },
                });

            });
    </script>
</body>

</html>