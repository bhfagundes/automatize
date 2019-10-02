<?php

namespace App\Repositories;

use App\Models\Tickets;
use App\Repositories\BaseRepository;

/**
 * Class TicketsRepository
 * @package App\Repositories
 * @version October 1, 2019, 4:35 pm UTC
*/

class TicketsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'NUMBER',
        'RES_DATE',
        'PRIORITY',
        'CR_DATE',
        'UP_DATE',
        'CONF_ITEM',
        'ASSIGN',
        'STATUS',
        'CL_CODE',
        'CL_DATE'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Tickets::class;
    }
}
