@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tickets - {{$nome_cliente}}</h1>
    
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div id="stocks-div"></div>
            @columnchart('DATA', 'stocks-div')
            <br>
        <div id="stocks-div2"></div>  
            @columnchart('DATAATUAL', 'stocks-div2')  
            <BR>
        <div id="stocks-div3"></div>  
            @columnchart('DATASEMANAATUAL', 'stocks-div3') 
           
        <br>
        <div id="finances-div"></div>
        @combochart('BACKLOGANUAL', 'finances-div')
        
      
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                   
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

