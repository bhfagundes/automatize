<div class="table-responsive">
    <table class="table" id="tickets-table">
        <thead>
            <tr>
                <th>Number</th>
        <th>Res Date</th>
        <th>Priority</th>
        <th>Cr Date</th>
        <th>Up Date</th>
        <th>Conf Item</th>
        <th>Assign</th>
        <th>Status</th>
        <th>Cl Code</th>
        <th>Cl Date</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($tickets as $tickets)
            <tr>
                <td>{!! $tickets->NUMBER !!}</td>
            <td>{!! $tickets->RES_DATE !!}</td>
            <td>{!! $tickets->PRIORITY !!}</td>
            <td>{!! $tickets->CR_DATE !!}</td>
            <td>{!! $tickets->UP_DATE !!}</td>
            <td>{!! $tickets->CONF_ITEM !!}</td>
            <td>{!! $tickets->ASSIGN !!}</td>
            <td>{!! $tickets->STATUS !!}</td>
            <td>{!! $tickets->CL_CODE !!}</td>
            <td>{!! $tickets->CL_DATE !!}</td>
                <td>
                    {!! Form::open(['route' => ['tickets.destroy', $tickets->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('tickets.show', [$tickets->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('tickets.edit', [$tickets->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
