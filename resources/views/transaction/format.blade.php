@section('js')
    <script>
        (function($) {
            $(function() {
                // --- Mostrar/Ocultar campo Goal según tipo ---
                var $typeSelect = $('#type');
                var $goalContainer = $('#goal_container');

                function toggleGoalField() {
                    if ($typeSelect.val() === 'saving') {
                        $goalContainer.removeClass('d-none');
                    } else {
                        $goalContainer.addClass('d-none');
                        // Limpiar selección si se oculta
                        $('#goal_id').val('').trigger('change');
                    }
                }

                $typeSelect.on('change', toggleGoalField);
                toggleGoalField(); // Ejecutar al inicio
                // --- Recurrence toggle (robusto) ---
                var $isRecurring = $('#is_recurring');
                var $recurrenceContainer = $('#recurrence_interval_container');

                function toggleRecurrenceField() {
                    if ($isRecurring.length && $recurrenceContainer.length) {
                        if ($isRecurring.is(':checked')) {
                            $recurrenceContainer.removeClass('d-none');
                        } else {
                            $recurrenceContainer.addClass('d-none');
                        }
                    }
                }
                $isRecurring.on('change', toggleRecurrenceField);
                toggleRecurrenceField();

                // --- Formato COP para monto ---
                // Elementos
                var $amountDisplay = $('#amount_display'); // visible (texto)
                var $amountHidden = $('#amount'); // hidden (numérico real enviado)

                // Funciones utilitarias
                function parseNumberFromString(str) {
                    if (typeof str !== 'string') return '';
                    // Quitar todo menos dígitos, signos y separador decimal
                    // Consideramos que el usuario puede poner . o , como decimal; convertimos a punto.
                    var cleaned = str.replace(/\./g, ''); // quitar separadores de miles con puntos
                    cleaned = cleaned.replace(/,/g, '.'); // convertir coma decimal a punto
                    cleaned = cleaned.replace(/[^\d\.\-]/g, ''); // quitar no dígitos excepto . y -
                    return cleaned;
                }

                var currencyFormatter = new Intl.NumberFormat('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    maximumFractionDigits: 0
                });

                function formatForDisplay(rawNum) {
                    if (rawNum === '' || rawNum === null || rawNum === undefined) return '';
                    var n = Number(rawNum);
                    if (isNaN(n)) return '';
                    // formateamos sin decimales (COP común)
                    return currencyFormatter.format(Math.round(n));
                }

                // Inicializar el display desde el hidden (si no fue rellenado por server)
                if ($amountHidden.length && $amountHidden.val() !== '' && ($amountDisplay.length &&
                        $amountDisplay.val() === '')) {
                    $amountDisplay.val(formatForDisplay($amountHidden.val()));
                }

                // Cuando el usuario hace focus en el campo visible, mostramos el número sin formato para editar
                $amountDisplay.on('focus', function() {
                    var raw = $amountHidden.val();
                    if (raw === '') {
                        $(this).val('');
                        return;
                    }
                    // mostrar número sin formato (texto simple)
                    var clean = parseNumberFromString(String(raw));
                    $(this).val(clean);
                });

                // Al teclear, actualizamos el hidden con el valor numérico (sin formato)
                $amountDisplay.on('input', function() {
                    var val = $(this).val();
                    var parsed = parseNumberFromString(val);
                    // si parsed es vacío lo convertimos a '', si no, lo convertimos a Number
                    if (parsed === '') {
                        $amountHidden.val('');
                    } else {
                        // Usamos parseFloat para permitir decimales si el usuario los escribe.
                        $amountHidden.val(parseFloat(parsed));
                    }
                });

                // Al perder focus, formateamos bonito para el usuario
                $amountDisplay.on('blur', function() {
                    var hiddenVal = $amountHidden.val();
                    if (hiddenVal === '' || hiddenVal === null) {
                        $(this).val('');
                    } else {
                        $(this).val(formatForDisplay(hiddenVal));
                    }
                });

                // Antes de enviar el formulario: validar mínimo 0 y asegurar hidden tiene valor numérico
                $amountDisplay.closest('form').on('submit', function(e) {
                    try {
                        var parsed = parseNumberFromString($amountDisplay.val());
                        var num = parsed === '' ? NaN : Number(parsed);
                        if (isNaN(num)) {
                            // si está vacío, lo dejamos pasar y que el backend valide required si aplica
                            // pero si el campo era required, evitamos enviar
                            if ($amountHidden.attr('required') || $amountDisplay.attr('required')) {
                                e.preventDefault();
                                alert('Por favor ingresa un monto válido.');
                            }
                            return;
                        }

                        // mínimo 0
                        if (num < 0) {
                            e.preventDefault();
                            alert('El monto debe ser mayor o igual a 0.');
                            return;
                        }

                        // Asignar al hidden el número "limpio" (con decimales si los tiene)
                        $amountHidden.val(num);
                    } catch (err) {
                        // si algo falla, prevenimos el submit para evitar datos corruptos
                        e.preventDefault();
                        console.error(err);
                        alert('Error validando el monto. Revisa la consola.');
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
