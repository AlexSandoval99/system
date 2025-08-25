@extends('layouts.AdminLTE.index')
@section('title', 'Ventas')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="ibox-content pull-right">
                        <a href="{{ url('vouchers/previous-create') }}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> Agregar</a>
                    </div>
                </div>
                <div class="ibox-content pb-0">
                        <form method="GET">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <input type="text" class="form-control" name="s" placeholder="Buscar" value="{{ request()->s }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" class="form-control" name="voucher_number" placeholder="Nro.Factura" value="{{ request()->voucher_number }}">
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::select('voucher_box_id', $voucher_boxes, request()->voucher_box_id, ['placeholder' => 'Seleccione Punto Expedición', 'class' => 'form-control', 'select2-group']) }}
                                </div>
                                <div class="form-group col-md-3">
                                    <input type="text" class="form-control" placeholder="Introduzca Monto" name="amount" id="amount" value="{{old('amount',request()->amount)}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <select class="form-control" name="client_id" id="client_id"></select>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="submit" class="btn btn-primary" name="filter" value="1"><i class="fa fa-search"></i></button>
                                    @if(request()->filter)
                                        <a href="{{ url('voucher') }}" class="btn btn-warning"><i class="fa fa-times"></i></a>
                                    @endif
                                </div>
                            </div>
                        </form>
                </div>
                <div class="ibox-content table-responsive no-padding">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>UN</th>
                                <th>SU</th>
                                <th>Fecha</th>
                                <th>Condición</th>
                                <th>Nro.Factura</th>
                                <th>Ruc</th>
                                <th>Razón Social</th>
                                <th>Estado</th>
                                <th></th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                                <tr>
                                    <td><span class="fs-3 label label-{{ $voucher->enterprise->label }}">{{ $voucher->enterprise->abbreviation }}</span></td>
                                    <td>{{ $voucher->branch->abbreviation }}</td>
                                    <td>{{ $voucher->date->format('d/m/Y') }}</td>
                                    <td>{{ config('constants.invoice_condition.'. $voucher->voucher_condition) }}</td>
                                    <td>{{ $voucher->voucher_fullnumber }}</td>
                                    <td>{{ $voucher->ruc }}</td>
                                    <td>{{ $voucher->razon_social }}</td>
                                    <td><span class="label label-{{ config('constants.voucher-status-label.' . $voucher->status) }}">{{ config('constants.voucher-status.' . $voucher->status) }}</span></td>
                                    <td>{{ $voucher->currency->abbreviation }}</td>
                                    <td class="text-right">{{ number_format($voucher->amount, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        @if($voucher->stamped_id)
                                            @if(($voucher->stamped->method_print==2 ||$voucher->stamped->method_print==3) && auth()->user()->can('sending-invoices-email'))
                                                <a href="javascript:;" onclick="modalClientSendInvoice_open({{ $voucher->id }}, '{{ $voucher->voucher_fullnumber }}', '{{ $voucher->amount }}', '{{ $voucher->razon_social }}', '{{ $voucher->ruc }}', {{ $voucher->stamped->method_print }}, '{{ $voucher->client->fullname }}', '{{ $voucher->client->document_number }}', '{{ $voucher->client->emails }}');"><i class="fa fa-envelope"></i></a>
                                            @endif
                                        @endif
                                        <a href="{{ url('vouchers/' . $voucher->id . '/pdf') }}" target="_blank"><i class="fa fa-file-pdf"></i></a>
                                        <a href="{{ url('vouchers/' . $voucher->id) }}"><i class="fa fa-info-circle"></i></a>
                                            <a href="{{ url('vouchers/' . $voucher->id . '/edit') }}"><i class="fa fa-pencil-alt"></i></a>
                                        @if($voucher->status==1 && !$voucher->where('invoice_id', $voucher->id)->exists())
                                            <a href="{{ url('vouchers/' . $voucher->id . '/cancel') }}">A</a>
                                            <a href="{{ url('vouchers/' . $voucher->id . '/delete') }}"><i class="fa fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $vouchers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
@section('layout_css')
    <link rel="stylesheet" href="{{  cached_asset('css/bootstrap-select.min.css') }}">
    <style>
    </style>
@endsection

@section('layout_js')
    <script src="{{ cached_asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();

            $("#client_id").select2({
                language: 'es',
                placeholder: 'Seleccione Clientes',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ url('ajax/clients') }}',
                    dataType: 'json',
                    // cache: true,
                    method: 'GET',
                    delay: 250,
                    data: function (params){
                        console.log(params);
                        return {
                            q: params.term,
                        };
                    },
                    processResults: function (data, params){
                        return {
                            results: data.items
                        };
                    }
                },
                escapeMarkup: function (markup){ return markup; },
                templateResult: function (repo){
                    if (repo.loading) return repo.text;

                    var markup = repo.fullname + "<br>" +
                            "<i class='fa fa-id-card'></i> " + repo.document_number;

                    return markup;
                },
                templateSelection: function (repo){
                    return repo.text;
                }
            });
        });
    </script>
@endsection
