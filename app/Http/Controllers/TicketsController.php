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

        $mes = date('m')-1;
        $ano=date('Y');
        $tickets_cancelados = Tickets::where('CL_CODE','=','Cancelado')
                                    ->whereRaw("MONTH(cr_date)={$mes}")
                                    ->whereRaw("YEAR(cr_date)={$ano}")->get();

        
        $tickets_prb = Tickets::whereRaw("MONTH(cr_date)={$mes}")
                            ->whereRaw("YEAR(cr_date)={$ano}")
                            ->whereRaw("PRB_CODE IS NOT NULL") 
                            ->get();

        $tickets_gerais =  Tickets::whereRaw("MONTH(cr_date)={$mes}")
                            ->whereRaw("YEAR(cr_date)={$ano}")
                            ->whereRaw("PRB_CODE IS  NULL")
                            ->where('CL_CODE','<>','Cancelado')
                            ->where('STATUS','=','Encerrado')
                            ->orWhere('STATUS','=','Fechado')
                            ->where('CL_CODE','<>','Cancelado')
                            ->get();
      
       $data = \Lava::DataTable();
       $data->addStringColumn('Analise') 
       ->addNumberColumn('Cancelados') 
       ->addNumberColumn('PRB') 
       ->addNumberColumn('Gerais');
       $data_tickets= [ "Analise", sizeof($tickets_cancelados),sizeof($tickets_prb),sizeof($tickets_gerais) ];
       $data->addRow($data_tickets);

        // Random Data For Example
        \Lava::ColumnChart('DATA', $data, [
            'title' => "Análise Mensal",
            'vAxis' => [
                'title'=>'Total'
            ],
            'height' => 400,
            'width' => 700
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
