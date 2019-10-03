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
    public function index(Request $request)
    {
        $tickets = $this->ticketsRepository->all();
        return view('tickets.index')
            ->with('tickets', $tickets);
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
        
        $data=Excel::import(new TicketsImport, $path);
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
