<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('type', __('Type')) }}
                    {{ Form::select('type', $types, old('type', $transaction->type), [
                        'class' => 'form-control select2' . ($errors->has('type') ? ' is-invalid' : ''),
                        'id' => 'typeTransaction',
                        'onchange' => 'viewForms()',
                        'placeholder' => __('Select the goal')
                    ]) }}
                    {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('date', __('Date')) }}
                    {{ Form::date('date', old('date', $transaction->date), [
                        'class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''),
                        'placeholder' => __('Date')
                    ]) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('amount', __('Amount')) }}
                    {{ Form::number('amount', old('amount', $transaction->amount), [
                        'class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''),
                        'placeholder' => __('Amount')
                    ]) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row dynamic-form" id="formexpense">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('category', __('Category')) }}
                    {{ Form::select('category', $categories, old('category', $transaction->category), [
                        'class' => 'form-control select2' . ($errors->has('category') ? ' is-invalid' : ''),
                        'placeholder' => __('Select the category')
                    ]) }}
                    {!! $errors->first('category', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row dynamic-form" id="formincome">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('partner_id', __('Partner')) }}
                    {{ Form::select('partner_id', $partners, old('partner_id', $transaction->partner_id), [
                        'class' => 'form-control select2' . ($errors->has('partner_id') ? ' is-invalid' : ''),
                        'placeholder' => __('Select the partner')
                    ]) }}
                    {!! $errors->first('partner_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('source', __('Source')) }}
                    {{ Form::text('source', old('source', $transaction->source), [
                        'class' => 'form-control' . ($errors->has('source') ? ' is-invalid' : ''),
                        'placeholder' => __('Source')
                    ]) }}
                    {!! $errors->first('source', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row dynamic-form" id="formsaving">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('goal', __('Goal Name')) }}
                    {{ Form::text('goal', old('goal', $transaction->goal), [
                        'class' => 'form-control' . ($errors->has('goal') ? ' is-invalid' : ''),
                        'placeholder' => __('Goal')
                    ]) }}
                    {!! $errors->first('goal', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('goal_id', __('Goal')) }}
                    {{ Form::select('goal_id', $goals, old('goal_id', $transaction->goal_id), [
                        'class' => 'form-control select2' . ($errors->has('goal_id') ? ' is-invalid' : ''),
                        'placeholder' => __('Select the goal')
                    ]) }}
                    {!! $errors->first('goal_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('status_id', __('Status')) }}
                    {{ Form::select('status_id', $statuses, old('status_id', $transaction->status_id), [
                        'class' => 'form-control select2' . ($errors->has('status_id') ? ' is-invalid' : ''),
                        'placeholder' => __('Select the status')
                    ]) }}
                    {!! $errors->first('status_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{ Form::label('description', __('Description')) }}
                    {{ Form::textarea('description', old('description', $transaction->description), [
                        'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                        'placeholder' => __('Description')
                    ]) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
    </div>

    @include('layouts.btn-submit')
</div>

<script>
    function viewForms() {
        document.querySelectorAll('.dynamic-form').forEach(form => form.style.display = 'none');
        let type = document.getElementById('typeTransaction').value;
        if (type === "A") document.getElementById('formsaving').style.display = 'flex';
        if (type === "I") document.getElementById('formincome').style.display = 'flex';
        if (type === "E") document.getElementById('formexpense').style.display = 'flex';
    }
    document.addEventListener('DOMContentLoaded', viewForms);
</script>
