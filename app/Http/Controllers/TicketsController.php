<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketsRequest;
use App\Http\Requests\UpdateTicketsRequest;
use App\Repositories\TicketsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Models\Tickets;
use App\Imports\TicketsImport;
use Maatwebsite\Excel\Facades\Excel;
use Khill\Lavacharts\Lavacharts;
use App\Models\CountryUser;
use Redmine\Client as Client;

class TicketsController extends AppBaseController
{
    /** @var  TicketsRepository */
    private $ticketsRepository;

    public function __construct(TicketsRepository $ticketsRepo)
    {
        $this->ticketsRepository = $ticketsRepo;
    }

    /**
     * Display a listing of the Tickets.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function geoChart()
    {
        $lava = new Lavacharts; // See note below for Laravel


		$popularity = $lava->DataTable();

		$data = CountryUser::select("name as 0","total_users as 1")->get()->toArray();


		$popularity->addStringColumn('Country')

		           ->addNumberColumn('Popularity')

		           ->addRows($data);


        $lava->GeoChart('Popularity', $popularity);
        ##passar variavel lava como parametro
        /* na view usamos : 
            <div id="pop-div" style="width:800px;border:1px solid black"></div>

           <?= $lava->render('GeoChart', 'Popularity', 'pop-div') ?>
        */
        return ;
    }
    public function pieChart()
    {
        $lava = new Lavacharts;

        $reasons = $lava->DataTable();

        $reasons->addStringColumn('Reasons')
                ->addNumberColumn('Percent')
                ->addRow(array('Check Reviews', 5))
                ->addRow(array('Watch Trailers', 2))
                ->addRow(array('See Actors Other Work', 4))
                ->addRow(array('Settle Argument', 89));


        $donutchart = $lava->DonutChart('IMDB', $reasons, [
                        'title' => 'Reasons I visit IMDB'
                    ]);
        ##passar variavel lava como parametro
        ##na view usamos
        /*
               <div id="chart-div"></div>
      {!! $lava->render('DonutChart', 'IMDB', 'chart-div') !!}

        */
    }
    public function lineChart()
    {
        $data = \Lava::DataTable();
        $data->addDateColumn('Day of Month')
                    ->addNumberColumn('Projected')
                    ->addNumberColumn('Official');

        // Random Data For Example
        for ($a = 1; $a < 30; $a++)
        {
            $rowData = [
            "2014-8-$a", rand(800,1000), rand(800,1000)
            ];

            $data->addRow($rowData);
        }

        \Lava::LineChart('Stocks', $data, [
        'title' => 'Stock Market Trends'
        ]);
        /* 
            na view:
            <div id="stocks-div"></div>
            @linechart('Stocks', 'stocks-div')

        */
    }
    public function columnChart()
    {
        $data = \Lava::DataTable();
        $data->addDateColumn('Day of Month')
                    ->addNumberColumn('Projected')
                    ->addNumberColumn('Official');

        // Random Data For Example
        for ($a = 1; $a < 30; $a++)
        {
            $rowData = [
            "2014-8-$a", rand(800,1000), rand(800,1000)
            ];

            $data->addRow($rowData);
        }

        \Lava::ColumnChart('Stocks', $data, [
        'title' => 'Stock Market Trends'
        ]);
        ## na view
        /*  @columnchart('Stocks', 'stocks-div')
        Para todos os tipos podemos ver
        http://lavacharts.com/api/3.0/Khill/Lavacharts/Charts/ColumnChart.html
        */
    }
    public function index(Request $request)
    {
        $client = new Client('http://10.30.22.55','bhfagundes','beiPhone7*');
        $mes = date('m')-1;
        $ano=date('Y');
        $tickets_cancelados = Tickets::where('CL_CODE','=','Cancelado')
                                    ->whereIn('STATUS',['Encerrado','Fechado'])
                                    ->whereRaw("MONTH(cr_date)={$mes}")
                                    ->whereRaw("YEAR(cr_date)={$ano}")->get();
        //Buscar os PRB'S DE PERNAMBUCO 
      // dd($client->issue->all(['project_id' => '6'
       // ]));
       $redmine= $client->issue->all();

        for($i=0; $i<sizeof($redmine['issues']);$i++)
        {
           if(($redmine['issues'][$i]['project']['name'] =='FCA – Pernambuco' || $redmine['issues'][$i]['project']['name'] =='FIAPE')
                && ($redmine['issues'][$i]['status']['name'] == 'Under analysis' || $redmine['issues'][$i]['status']['name'] == 'UAT'
                || $redmine['issues'][$i]['status']['name'] == 'To deploy' || $redmine['issues'][$i]['status']['name']=='Internal tests'))
           {
                //echo ('Pernambuco ' . $i .' '. ':' . $redmine['issues'][$i]['custom_fields'][5]['value'] . '<br>');
           }
        }
        $tickets_prb = Tickets::whereRaw("MONTH(cr_date)={$mes}")
                            ->whereRaw("YEAR(cr_date)={$ano}")
                            ->where('PRB_CODE', '!=', '') 
                            ->get();

        $tickets_gerais =  Tickets::whereRaw("MONTH(cr_date)={$mes}")
                            ->whereRaw("YEAR(cr_date)={$ano}")
                            ->whereIn('STATUS',['Encerrado','Fechado'])
                            ->where('PRB_CODE', '=', '')
                            ->get();
        
        $tickets_analise =  Tickets::whereRaw("MONTH(cr_date)={$mes}")
                            ->whereRaw("YEAR(cr_date)={$ano}")
                            ->whereIn('STATUS',['Pendente','Atendimento'])
                            ->get();                   

       $data = \Lava::DataTable();
       $data->addStringColumn('Analise Mês anterior')
       ->addNumberColumn('Total')
       ->addRoleColumn('string', 'style')
       ->addRoleColumn('string', 'annotation');
       $data->addRows([
        ['Cancelados',  sizeof($tickets_cancelados), 'blue'],
        ['PRB', sizeof($tickets_prb), 'orange'],
        ['Gerais',   sizeof($tickets_gerais), 'red'],
        ['Em análise',   sizeof($tickets_analise), 'green']
    ]);      
   
        \Lava::ColumnChart('DATA', $data, [
            'title' => "Análise Mensal (Mês anterior)",
            'position'=> "center",
            'legend' => 'none',
            'vAxis' => [
                'title'=>'Total'
            ],
            'height' => 400,
            'width' => 700
        ]);

        ## ANÁLISE MÊS ATUAL
        $mes = date('m');
        $ano=date('Y');
        $tickets_cancelados = Tickets::where('CL_CODE','=','Cancelado')
                                    ->whereIn('STATUS',['Encerrado','Fechado'])
                                    ->whereRaw("MONTH(cr_date)={$mes}")
                                    ->whereRaw("YEAR(cr_date)={$ano}")->get();

        
        $tickets_prb = Tickets::whereRaw("MONTH(cr_date)={$mes}")
                            ->whereRaw("YEAR(cr_date)={$ano}")
                            ->where('PRB_CODE', '!=', '') 
                            ->get();

        $tickets_gerais =  Tickets::whereRaw("MONTH(cr_date)={$mes}")
                            ->whereRaw("YEAR(cr_date)={$ano}")
                            ->whereIn('STATUS',['Encerrado','Fechado'])
                            ->where('PRB_CODE', '=', '')
                            ->get();
        
        $tickets_analise =  Tickets::whereRaw("MONTH(cr_date)={$mes}")
                            ->whereRaw("YEAR(cr_date)={$ano}")
                            ->whereIn('STATUS',['Pendente','Atendimento'])
                            ->get();                   

       $data_atual = \Lava::DataTable();
       $data_atual->addStringColumn('Analise')
       ->addNumberColumn('Total')
       ->addRoleColumn('string', 'style')
       ->addRoleColumn('string', 'annotation');
       $data_tickets= [ "Analise", sizeof($tickets_cancelados),sizeof($tickets_prb),sizeof($tickets_gerais) ];
       $data_atual->addRows([
        ['Cancelados',  sizeof($tickets_cancelados), 'blue'],
        ['PRB', sizeof($tickets_prb), 'orange'],
        ['Gerais',   sizeof($tickets_gerais), 'red'],
        ['Em análise',   sizeof($tickets_analise), 'green']
    ]);      
   
        \Lava::ColumnChart('DATAATUAL', $data_atual, [
            'title' => "Análise Mensal (Mês atual)",
            'position'=> "center",
            'legend' => 'none',
            'vAxis' => [
                'title'=>'Total'
            ],
            'height' => 400,
            'width' => 700
        ]);
  
    ## week(data) = (week(now()-1)
         ## ANÁLISE semana ATUAL
         $mes = date('m');
         $ano=date('Y');
         $tickets_cancelados = Tickets::where('CL_CODE','=','Cancelado')
                                     ->whereIn('STATUS',['Encerrado','Fechado'])
                                     ->whereRaw("YEARWEEK(cr_date) = YEARWEEK(NOW())-1")
                                     ->whereRaw("YEAR(cr_date)={$ano}")->get();
 
         
         $tickets_prb = Tickets::whereRaw("YEARWEEK(cr_date) = YEARWEEK(NOW())-1")
                             ->whereRaw("YEAR(cr_date)={$ano}")
                             ->where('PRB_CODE', '!=', '') 
                             ->get();
         $tickets_gerais =  Tickets::whereRaw("YEARWEEK(cr_date) = YEARWEEK(NOW())-1")
                             ->whereRaw("YEAR(cr_date)={$ano}")
                             ->whereIn('STATUS',['Encerrado','Fechado'])
                             ->where('PRB_CODE', '=', '')
                             ->get();
         
         $tickets_analise =  Tickets::whereRaw("YEARWEEK(cr_date) = YEARWEEK(NOW())-1")
                             ->whereRaw("YEAR(cr_date)={$ano}")
                             ->whereIn('STATUS',['Pendente','Atendimento'])
                             ->get();                   
 
        $data_semana_atual = \Lava::DataTable();
        $data_semana_atual->addStringColumn('Analise')
        ->addNumberColumn('Total')
        ->addRoleColumn('string', 'style')
        ->addRoleColumn('string', 'annotation');
        $data_tickets= [ "Analise", sizeof($tickets_cancelados),sizeof($tickets_prb),sizeof($tickets_gerais) ];
        $data_semana_atual->addRows([
         ['Cancelados',  sizeof($tickets_cancelados), 'blue'],
         ['PRB', sizeof($tickets_prb), 'orange'],
         ['Gerais',   sizeof($tickets_gerais), 'red'],
         ['Em análise',   sizeof($tickets_analise), 'green']
     ]);      
    
         \Lava::ColumnChart('DATASEMANAATUAL', $data_semana_atual, [
             'title' => "Análise Semana anterior",
             'position'=> "center",
             'legend' => 'none',
             'vAxis' => [
                 'title'=>'Total'
             ],
             'height' => 400,
             'width' => 700
         ]);
        ##Backlog anual
         $mes = date('m');
         $ano=date('Y')-1;
        
        $encerrados_backlog=Tickets::whereRaw("MONTH(cr_date)<={$mes}")
                ->whereRaw("YEAR(cr_date)<={$ano}")
                ->whereIn('STATUS',['Encerrado','Fechado'])
                ->get();
        $abertos_backlog=Tickets::whereRaw("MONTH(cr_date)<={$mes}")
                ->whereRaw("YEAR(cr_date)<={$ano}")
                ->whereNotIn('STATUS',['Cancelado'])
                ->get();        
        ## $backlog_anual = new Lavacharts;
         ##$backlog = $backlog_anual->DataTable();
        $dados_printar=[];
         $backlog_anual = \Lava::DataTable();
         $backlog_anual->addStringColumn('Analise')
                        ->addNumberColumn('Abertos')
                        ->addNumberColumn('Resolvidos');
        $k=0;
        for($i=1; $i<=12;$i++)
        {
            if($mes == 1)
            {
                $mes = 12;
                $ano--;
            }
            else
            {
                $mes--;
            }
            
            $tickets_abertos= Tickets::whereRaw("MONTH(cr_date)={$mes}")
                                      ->whereRaw("YEAR(cr_date)={$ano}")->get();    
                                                    
            $tickets_fechados= Tickets::whereRaw("MONTH(CL_DATE)={$mes}")
                                        ->whereRaw("YEAR(CL_DATE)={$ano}")->get();
            
            $dados_printar[$i]['abertos']=sizeof($tickets_abertos);
            $dados_printar[$i]['fechados']=sizeof($tickets_fechados);
            $dados_printar[$i]['data']=$mes."/".$ano;
            $k++;
        }
        
        ##teste combochart
       
        for ($i=12;$i>=1;$i--)
        {
            $backlog_anual->addRow([$dados_printar[$i]['data'], $dados_printar[$i]['abertos'],  $dados_printar[$i]['fechados']]);                     

        }

            \Lava::ComboChart('BACKLOGANUAL', $backlog_anual, [
            'title' => 'BackLog Anual',
            'titleTextStyle' => [
                'color'    => 'rgb(123, 65, 89)',
                'fontSize' => 16
            ],
            'legend' => [
                'position' => 'in'
            ],
            'backgroundColor' => '#000',
           
            'seriesType' => 'bars',
            'series' => [
                1 => ['type' => 'line']
            ]
        ]);
        
        $tickets = $this->ticketsRepository->all();
        return view('tickets.index',compact('tickets'));
    }

    /**
     * Show the form for creating a new Tickets.
     *
     * @return Response
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created Tickets in storage.
     *
     * @param CreateTicketsRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $path = $request->file('excel')->getRealPath();
        $data=Excel::import(new TicketsImport, $request->file('excel'));
        /*dd($data);
        $tickets = $this->ticketsRepository->create($input);

        Flash::success('Tickets saved successfully.');
        */
        return redirect(route('tickets.index'));
    }

    /**
     * Display the specified Tickets.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tickets = $this->ticketsRepository->find($id);

        if (empty($tickets)) {
            Flash::error('Tickets not found');

            return redirect(route('tickets.index'));
        }

        return view('tickets.show')->with('tickets', $tickets);
    }

    /**
     * Show the form for editing the specified Tickets.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tickets = $this->ticketsRepository->find($id);

        if (empty($tickets)) {
            Flash::error('Tickets not found');

            return redirect(route('tickets.index'));
        }

        return view('tickets.edit')->with('tickets', $tickets);
    }

    /**
     * Update the specified Tickets in storage.
     *
     * @param int $id
     * @param UpdateTicketsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicketsRequest $request)
    {
        $tickets = $this->ticketsRepository->find($id);

        if (empty($tickets)) {
            Flash::error('Tickets not found');

            return redirect(route('tickets.index'));
        }

        $tickets = $this->ticketsRepository->update($request->all(), $id);

        Flash::success('Tickets updated successfully.');

        return redirect(route('tickets.index'));
    }

    /**
     * Remove the specified Tickets from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function massiveDelete()
    {
       Tickets::truncate();
        return redirect(route('tickets.index'));
    }
    public function destroy($id)
    {
        
        $tickets = $this->ticketsRepository->find($id);

        if (empty($tickets)) {
            Flash::error('Tickets not found');

            return redirect(route('tickets.index'));
        }
        $this->ticketsRepository->delete($id);

        Flash::success('Tickets deleted successfully.');

        return redirect(route('tickets.index'));
    }
}
