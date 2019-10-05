<!-- Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('NUMBER', 'Number:') !!}
    {!! Form::text('NUMBER', null, ['class' => 'form-control']) !!}
</div>

<!-- Res Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('RES_DATE', 'Res Date:') !!}
    {!! Form::date('RES_DATE', null, ['class' => 'form-control','id'=>'RES_DATE']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#RES_DATE').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Priority Field -->
<div class="form-group col-sm-6">
    {!! Form::label('PRIORITY', 'Priority:') !!}
    {!! Form::text('PRIORITY', null, ['class' => 'form-control']) !!}
</div>

<!-- Cr Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CR_DATE', 'Cr Date:') !!}
    {!! Form::date('CR_DATE', null, ['class' => 'form-control','id'=>'CR_DATE']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#CR_DATE').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Up Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('UP_DATE', 'Up Date:') !!}
    {!! Form::date('UP_DATE', null, ['class' => 'form-control','id'=>'UP_DATE']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#UP_DATE').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Conf Item Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CONF_ITEM', 'Conf Item:') !!}
    {!! Form::text('CONF_ITEM', null, ['class' => 'form-control']) !!}
</div>

<!-- Assign Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ASSIGN', 'Assign:') !!}
    {!! Form::text('ASSIGN', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('STATUS', 'Status:') !!}
    {!! Form::text('STATUS', null, ['class' => 'form-control']) !!}
</div>

<!-- Cl Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CL_CODE', 'Cl Code:') !!}
    {!! Form::text('CL_CODE', null, ['class' => 'form-control']) !!}
</div>

<!-- Cl Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('CL_DATE', 'Cl Date:') !!}
    {!! Form::date('CL_DATE', null, ['class' => 'form-control','id'=>'CL_DATE']) !!}
</div>

@section('scripts')
    <script type="text/javascript">
        $('#CL_DATE').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endsection

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('tickets.index') !!}" class="btn btn-default">Cancelar</a>
</div>
