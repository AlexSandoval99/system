@extends('layouts.AdminLTE.index')
@section('title', 'Apertura de Caja')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="ibox-tools">
                    <a href="{{ url('cash_box_balances/create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar</a>
                </div>
            </div>
            <div class="ibox-content table-responsive no-padding">
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Caja</th>
                            <th>Monto</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cash_box_balances as $cash_box_balance)
                            <tr>
                                <td>{{ $cash_box_balance->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $cash_box_balance->cash_box->name }}</td>
                                <td class="text-right">{{ number_format($cash_box_balance->amount, 0, ',', '.') }}</td>
                                <td>{{ $cash_box_balance->user->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $cash_box_balances->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
