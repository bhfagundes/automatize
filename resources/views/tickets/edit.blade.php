@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Tickets
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($tickets, ['route' => ['tickets.update', $tickets->id], 'method' => 'patch']) !!}

                        @include('tickets.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection