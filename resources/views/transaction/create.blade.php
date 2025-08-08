@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow">
    <h2 class="text-2xl font-bold mb-4">Agregar transacción</h2>

    <form method="POST" action="{{ route('transactions.store') }}">
        @csrf

        <!-- Tipo -->
        <label class="block mb-2 font-medium">Tipo</label>
        <select name="type" class="w-full border rounded p-2 mb-4" required>
            <option value="expense">Gasto</option>
            <option value="income">Ingreso</option>
        </select>

        <!-- Monto -->
        <label class="block mb-2 font-medium">Monto</label>
        <input type="number" step="0.01" name="amount" class="w-full border rounded p-2 mb-4" placeholder="Ej. 12000" required>

        <!-- Categoría -->
        <label class="block mb-2 font-medium">Categoría</label>
        <select name="category_id" class="w-full border rounded p-2 mb-4">
            @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>

        <!-- Fecha -->
        <label class="block mb-2 font-medium">Fecha</label>
        <input type="date" name="date" value="{{ now()->toDateString() }}" class="w-full border rounded p-2 mb-4">

        <!-- Nota -->
        <label class="block mb-2 font-medium">Nota (opcional)</label>
        <input type="text" name="note" class="w-full border rounded p-2 mb-4" placeholder="Descripción breve">

        <!-- Opciones avanzadas -->
        <button type="button" onclick="toggleAdvanced()" class="text-blue-600 mb-2">
            Mostrar opciones avanzadas
        </button>
        <div id="advanced" class="hidden">
            <label class="block mb-2 font-medium">¿Es recurrente?</label>
            <input type="checkbox" name="is_recurring" value="1" class="mb-4">

            <label class="block mb-2 font-medium">Intervalo de días (si recurrente)</label>
            <input type="number" name="recurring_interval_days" class="w-full border rounded p-2 mb-4" placeholder="Ej. 30">
        </div>

        <!-- Botón guardar -->
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Guardar transacción
        </button>
    </form>
</div>

<script>
function toggleAdvanced() {
    const adv = document.getElementById('advanced');
    adv.classList.toggle('hidden');
}
</script>
@endsection
