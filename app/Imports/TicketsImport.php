<?php

namespace App\Imports;

use App\Models\Tickets;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TicketsImport implements ToModel,WithChunkReading, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      
       
        $ticket = new Tickets();
        $ticket->number=$row['numero'];
        
        if(!is_null ($row['resolvido']))
        {
            $row['resolvido'] =\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['resolvido']);
        }
        
        $ticket->res_date = $row['resolvido'];
        $ticket->priority =$row['prioridade'];
        if(!is_null ($row['criado_em']))
        {
            $row['criado_em'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['criado_em']);
        }
  
        $ticket->cr_date =$row['criado_em'];
        if(!is_null ($row['atualizado_em']))
        {
            $row['atualizado_em'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['atualizado_em']);
        }
       
        $ticket->up_date =$row['atualizado_em'];
        $ticket->conf_item =$row['item_de_configuracao'];
        $ticket->assign =$row['atribuido_a'];
        $ticket->status =$row['estado'];
        $ticket->cl_code =$row['codigo_de_fechamento'];
        if(!is_null ($row['fechado']))
        {
            $row['fechado'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fechado']);
        }
     
        $ticket->cl_date =$row['fechado'];
        $ticket->CL_NOTES=$row['notas_de_confirmacao_do_fechamento'];
        $ticket->save();
        
    }
    public function headingRow(): int
    {
        return 1;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
