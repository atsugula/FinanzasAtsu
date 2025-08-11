@if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <p>{{ __($message) }}</p>
    </div>
@endif
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ __($message) }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger shadow-sm rounded-3 p-3" style="color: #fff; background: linear-gradient(135deg, #e53935, #e35d5b);">
        <h5 class="mb-3 fw-bold">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
            {{ __('Ups, encontramos algunos problemas:') }}
        </h5>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li style="color: #fff;">{{ __($error) }}</li>
            @endforeach
        </ul>
    </div>
@endif
