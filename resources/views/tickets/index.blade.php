@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tickets</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('tickets.create') !!}">Adicionar</a>
            <br><br>
            <a class="btn btn-danger pull-right" style="margin-top: -10px;margin-bottom: 5px" href="/massiveDelete">Deletar Massivo</a>
        </h1>
    
    </section>
   
@endsection

