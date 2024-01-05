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
