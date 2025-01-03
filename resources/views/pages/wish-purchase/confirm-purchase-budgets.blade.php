    @extends('layouts.AdminLTE.index')
    @section('title', 'Aprobación de Presupuestos Cargados')
    @section('layout_css')
        <style>
            .button-container {
                text-align: center;
                border: 2px solid #126f16;
            }
            .button-container a {
                border: 1px solid #126f16;
            }
            .image-box {
                margin-bottom: 30px;
                border: 3px solid #126f16;
                display: inline-block;
            }
            .image-box img {
                width: 400px;
                height: 500px;
            }
            p {
                font-size: 14px;
            }
        </style>
    @endsection
    @section('content')
            <div class="ibox-content">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Detalles de Solicitud N# {{$wish_purchase->number}}</h3>
                            <div class="row">
                                <div class="col-md-2">
                                    <p>Fecha:</p>
                                    <p>Solicitante: </p>
                                    <p>Sucursal: </p>
                                </div>
                                <div class="col-md-10">
                                    <p>{{$wish_purchase->date}}</p>
                                    <p> {{$wish_purchase->requested_by}}</p>
                                    <p> {{$wish_purchase->branch->name}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3>Productos Solicitados Según Solicitud Nro #{{$wish_purchase->number}}</h3>
                            <div class="table">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="10%">#</th>
                                            <th width="80%">Producto</th>
                                            <th width="10%">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($wish_purchase->wish_purchase_details as $wish_purchase_detail)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$wish_purchase_detail->description}}</td>
                                                <td>{{$wish_purchase_detail->quantity}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br><br><br>
                    <h3><b>Presupuestos Cargados:</b></h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wish_purchase->budget_purchases()->where(function($query){ $query->where('status', 2)->orWhere('status', 1); })->get() as $index => $purchase_budget)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ url('budget/'.$purchase_budget->id.'/view-purchase-budgets') }}">
                                            {{ $purchase_budget->name.' - '.$purchase_budget->ruc }}
                                        </a>
                                    </td>
                                    <td>
                                        {{number_format($purchase_budget->budget_purchase_details()->sum('total_price'),0,',','.')}}
                                    </td>
                                    <td>
                                        {{-- <div class="button-container"> --}}
                                            <a href="javascript:;" class="btn btn-success btn-sm"
                                            onclick="approved_restocking({{ $purchase_budget->id }}, 1)"
                                            id="approve-link{{ $purchase_budget->id }}"
                                            title="Autorizar Presupuesto">
                                                Autorizar
                                            </a>
                                            <a href="javascript:;" class="btn btn-danger btn-sm"
                                            onclick="approved_restocking({{ $purchase_budget->id }}, 2)"
                                            id="reject-link{{ $purchase_budget->id }}"
                                            title="Rechazar Solicitud">
                                                Rechazar
                                            </a>
                                        {{-- </div> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    @endsection
    @section('layout_js')
        <script>
            function approved_restocking(id,type) {
                $(document).on('click', '#approve-link'+id, function(event) {
                    text = type == 1 ?  'Aprobar' : 'Rechazar';
                    var url     = 'wish-purchase';

                    event.preventDefault(); // Prevenir que el enlace se siga en su ruta
                    swal({
                        title: "¿Desea "+text+" el presupuesto de compra?",
                        icon: "info",
                        buttons: ["Cancelar", "Sí,"+text],
                        dangerMode: type == 1 ? false : true,
                    })
                    .then((willApprove) => {
                        if (willApprove) {
                            window.location.href = `{{ url('wish-purchase-budgets/${id}/confirm-purchase-budgets?type=${type}&url=${url}')}}`;
                        }
                    });
                });
            }
        </script>
    @endsection
