<?php

namespace App\Imports;

use App\Models\Tickets;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TicketsImport implements ToModel,WithChunkReading
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
            $row[1] =\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]);
        }
        
        $ticket->res_date = $row[1];
        $ticket->priority =$row[2];
        if(!is_null ($row[3]))
        {
            $row[3] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3]);
        }
  
        $ticket->cr_date =$row[3];
        if(!is_null ($row[4]))
        {
            $row[4] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]);
        }
       
        $ticket->up_date =$row[4];
        $ticket->conf_item =$row[5];
        $ticket->assign =$row[6];
        $ticket->status =$row[7];
        $ticket->cl_code =$row[8];
        if(!is_null ($row[9]))
        {
            $row[9] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9]);
        }
     
        $ticket->cl_date =$row[9];
        $ticket->save();
        
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
