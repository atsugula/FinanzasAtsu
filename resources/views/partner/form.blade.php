<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('type_document', __('Type Document')) }}
                    {{ Form::select('type_document', $type_documents, $partner->type_document, ['class' => 'form-control select2' . ($errors->has('type_document') ? ' is-invalid' : ''), 'placeholder' => __('Select the partner')]) }}
                    {!! $errors->first('type_document', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('document_number', __('Document Number')) }}
                    {{ Form::number('document_number', $partner->document_number, ['class' => 'form-control' . ($errors->has('document_number') ? ' is-invalid' : ''), 'placeholder' => __('Document Number')]) }}
                    {!! $errors->first('document_number', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('company_name', __('Company Name')) }}
                    {{ Form::text('company_name', $partner->company_name, ['class' => 'form-control' . ($errors->has('company_name') ? ' is-invalid' : ''), 'placeholder' => __('Company Name')]) }}
                    {!! $errors->first('company_name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('phone', __('Phone')) }}
                    {{ Form::number('phone', $partner->phone, ['class' => 'form-control' . ($errors->has('phone') ? ' is-invalid' : ''), 'placeholder' => __('Phone')]) }}
                    {!! $errors->first('phone', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> 
            
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('email', __('Email')) }}
                    {{ Form::email('email', $partner->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => __('Email')]) }}
                    {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

        </div>
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
    
</div>
