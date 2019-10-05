<!-- Number Field -->
<div class="form-group col-sm-6">
    {!! Form::label('excel', 'Arquivo Xlsx:') !!}
   {!! Form::file('excel')!!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('tickets.index') !!}" class="btn btn-default">Cancelar</a>
</div>
