<?php

namespace App\Imports;

use App\Models\Tickets;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class TicketsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      
        
        $ticket = new Tickets();
        $ticket->number=$row[0];
        
        if(!is_null ($row[1]))
        {
            $row[1] = Carbon::parse( $row[1]);
        }
        dd($row[1] . " ". $row[3]);
        $ticket->res_date = $row[1];
        $ticket->priority =$row[2];
        if(!is_null ($row[3]))
        {
            $row[3] = Carbon::parse( $row[3]);
        }
        dd(2);
        $ticket->cr_date =$row[3];
        if(!is_null ($row[4]))
        {
            $row[4] = Carbon::parse( $row[4]);
        }
        dd(3);
        $ticket->up_date =$row[4];
        $ticket->conf_item =$row[5];
        $ticket->assign =$row[6];
        $ticket->status =$row[7];
        $ticket->cl_code =$row[8];
        if(!is_null ($row[9]))
        {
            $row[9] = Carbon::parse( $row[9]);
        }
        dd(4);
        $ticket->cl_date =$row[9];
        $ticket->save();
        
    }
}
