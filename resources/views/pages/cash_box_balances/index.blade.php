@extends('layouts.AdminLTE.index')
@section('title', 'Apertura de Caja')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <div class="ibox-tools">
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalAperturaCaja">
                        <i class="fa fa-plus"></i> Agregar
                    </button>
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
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cash_box_balances as $cash_box_balance)
                            <tr>
                                <td>{{ $cash_box_balance->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $cash_box_balance->cash_box->name }}</td>
                                <td class="text-right">{{ number_format($cash_box_balance->amount, 0, ',', '.') }}</td>
                                <td>{{ $cash_box_balance->user->name }}</td>
                                <td>
                                    @if ($cash_box_balance->status == 1)
                                        <span class="badge badge-success">Abierto</span>
                                    @else
                                        <span class="badge badge-secondary">Cerrado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($cash_box_balance->status == 1)
                                        <a href="javascript:void(0);" class="text-danger" title="Cerrar Caja" onclick="cerrarCaja({{ $cash_box_balance->id }})">
                                            <i class="fa fa-lock"></i>
                                        </a>
                                    @endif
                                    @if($cash_box_balance->status == 2)
                                        @if(!$cash_box_balance->arqueo_id)
                                            <a href="javascript:void(0);" class="text-info" title="Arqueo" onclick="abrirModalArqueo({{ $cash_box_balance->id }})">
                                                    <i class="fa fa-cash-register"></i>
                                            </a>
                                        @endif
                                        <a href="javascript:void(0);" class="text-info" title="Recaudación" onclick="abrirModalDeposito({{ $cash_box_balance->id }})">
                                            <i class="fa-solid fa-piggy-bank"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $cash_box_balances->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
<!-- Modal Arqueo -->
<div class="modal fade" id="arqueoModal" tabindex="-1" role="dialog" aria-labelledby="arqueoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="arqueoModalLabel">Arqueo de Caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formArqueo" method="POST">
                @csrf
                <input type="hidden" name="cash_box_id" id="arqueo_cash_box_id">
                <div class="modal-body">
                    <h6><strong>Efectivo</strong></h6>
                    <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Denominación</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(config('constants.denominaciones_number') as $key => $den)
                                <tr>
                                    <td>{{ number_format($den, 0, ',', '.') }} Gs</td>
                                    <td>
                                        <input type="hidden" name="denominacion[]" value="{{ $den }}">
                                        <input type="number" min="0" class="form-control cantidad" name="cant_denominacion[]" data-den="{{ $den }}" value="0">
                                    </td>
                                    <td class="subtotal">0</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total Efectivo:</th>
                                <th id="totalEfectivo">0</th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>

                    <!-- TARJETA -->
                    <h6><strong>Tarjeta</strong></h6>
                    <div class="form-group">
                        <label for="tarjetaMonto">Monto Tarjeta (Gs):</label>
                        <input type="number" class="form-control" id="tarjetaMonto" name="tarjetaMonto" value="0" min="0">
                    </div>

                    <!-- CHEQUES -->
                    <h6><strong>Cheques</strong></h6>
                    <div class="form-group">
                        <label for="chequesMonto">Monto Cheques (Gs):</label>
                        <input type="number" class="form-control" id="chequesMonto" name="chequesMonto" value="0" min="0">
                    </div>

                    <!-- TOTAL GENERAL -->
                    <div class="form-group mt-3">
                        <label><strong>Total General (Efectivo + Tarjeta + Cheques):</strong></label>
                        <input type="text" readonly class="form-control" id="totalGeneral" value="0">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Arqueo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Recaudación -->
<div class="modal fade" id="depositoModal" tabindex="-1" role="dialog" aria-labelledby="depositoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="depositoModalLabel">Recaudación a Depositar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formDeposito" method="POST">
                @csrf
                <input type="hidden" name="cash_box_id" id="deposito_cash_box_id">
                <div class="modal-body">
                    <h6><strong>Efectivo</strong></h6>
                    <div class="form-group">
                        <label for="montoEfectivo">Monto Efectivo (Gs):</label>
                        <input type="number" class="form-control" id="montoEfectivo" name="montoEfectivo" min="0" value="0" required>
                    </div>
                    <h6><strong>Cheques</strong></h6>
                    <div class="form-group">
                        <label for="montoCheque">Monto Cheques (Gs):</label>
                        <input type="number" class="form-control" id="montoCheque" name="montoCheque" min="0" value="0" required>
                    </div>
                    <div class="form-group">
                        <label for="bancoDestino">Banco / Destino:</label>
                        {{Form ::select('bank_id', $banks, null, ['class' => 'form-control', 'id' => 'bank_id', 'placeholder' => 'Seleccione Banco', 'required'])}}
                    </div>
                    <div class="form-group">
                        <label><strong>Total a Depositar:</strong></label>
                        <input type="text" readonly class="form-control" id="totalDeposito" value="0">
                    </div>
                    <div class="form-group">
                        <label for="observacionDeposito">Observaciones:</label>
                        <textarea class="form-control" id="observacionDeposito" name="observacionDeposito" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Depósito</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Apertura de Caja -->
<div class="modal fade" id="modalAperturaCaja" tabindex="-1" role="dialog" aria-labelledby="modalAperturaCajaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalAperturaCajaLabel">
                <i class="fa fa-cash-register"></i> Agregar Saldo Inicial de Caja
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <form id="formApertura">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Caja</label>
                        {{ form::select('cash_box_id', $cash_boxes, old('cash_box_id'), ['class' => 'form-control selectpicker','placeholder'=>'Seleccione una Caja', 'data-live-search'=> 'true', 'id' => 'cash_box_id_modal']) }}
                    </div>
                    <div class="form-group col-md-4">
                        <label>Último Saldo Caja</label>
                        <input type="text" name="last_cash_balance" class="form-control" id="last_cash_balance_modal" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Monto Saldo Inicial</label>
                        <input type="text" name="amount" class="form-control" id="amount_modal" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label>Observación</label>
                    <textarea class="form-control" name="observation" id="observation_modal" rows="2"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
  </div>
</div>


@endsection
@section('layout_js')
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            const inputsBilletes = document.querySelectorAll(".cantidad");
            const tarjetaInput = document.getElementById("tarjetaMonto");
            const chequesInput = document.getElementById("chequesMonto");
            const totalEfectivoEl = document.getElementById("totalEfectivo");
            const totalGeneralEl = document.getElementById("totalGeneral");

            function calcularTotales() {
                let totalEfectivo = 0;

                inputsBilletes.forEach(inp => {
                    const den = parseInt(inp.dataset.den);
                    const cant = parseInt(inp.value) || 0;
                    const subtotal = den * cant;
                    inp.closest("tr").querySelector(".subtotal").textContent = subtotal.toLocaleString('es-ES');
                    totalEfectivo += subtotal;
                });

                totalEfectivoEl.textContent = totalEfectivo.toLocaleString('es-ES');

                const totalTarjeta = parseInt(tarjetaInput.value) || 0;
                const totalCheques = parseInt(chequesInput.value) || 0;
                const totalGeneral = totalEfectivo + totalTarjeta + totalCheques;

                totalGeneralEl.value = totalGeneral.toLocaleString('es-ES');
            }

            inputsBilletes.forEach(inp => inp.addEventListener("input", calcularTotales));
            tarjetaInput.addEventListener("input", calcularTotales);
            chequesInput.addEventListener("input", calcularTotales);

            //recaudacion
            const montoEfectivo = document.getElementById("montoEfectivo");
            const montoCheque = document.getElementById("montoCheque");
            const totalDeposito = document.getElementById("totalDeposito");

            function calcularDeposito() {
                const efectivo = parseInt(montoEfectivo.value) || 0;
                const cheque = parseInt(montoCheque.value) || 0;
                const total = efectivo + cheque;
                totalDeposito.value = total.toLocaleString('es-ES');
            }

            montoEfectivo.addEventListener("input", calcularDeposito);
            montoCheque.addEventListener("input", calcularDeposito);
        });

        function cerrarCaja(id) {
            swal({
                title: "¿Cerrar Caja?",
                text: "Una vez cerrada, no se podrán registrar más movimientos en esta caja.",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Cancelar",
                        visible: true,
                        className: "btn btn-secondary"
                    },
                    confirm: {
                        text: "Aceptar y Finalizar",
                        visible: true,
                        className: "btn btn-danger"
                    }
                },
                dangerMode: true,
            })
            .then((willClose) => {
                if (willClose) {
                    // Redirige al controlador de cierre
                    window.location.href = "{{ url('cash_box_balances') }}/" + id + "/close";
                }
            });
        }

        function abrirModalArqueo(id) {
            $('#arqueo_cash_box_id').val(id);
            $('#arqueoModal').modal('show');
        }

        function abrirModalDeposito(id) {
            $('#deposito_cash_box_id').val(id);
            $('#depositoModal').modal('show');
        }

        $('#formArqueo').on('submit', function(e){
            e.preventDefault();
            const idCaja = $('#arqueo_cash_box_id').val();

            $.ajax({
                url: '{{ url("cash_box_balances") }}/' + idCaja + '/arqueo',
                type: 'POST',
                data: $(this).serialize(),
                success: function(data){
                    swal("Éxito", data.message, "success");
                    $('#arqueoModal').modal('hide');
                    $('#formArqueo')[0].reset();
                },
                error: function(){
                    swal("Error", "No se pudo guardar el arqueo", "error");
                }
            });
        });

        $('#formDeposito').on('submit', function(e){
            e.preventDefault();
            const idCaja = $('#deposito_cash_box_id').val();

            $.ajax({
                url: '{{ url("cash_box_balances") }}/' + idCaja + '/deposito',
                type: 'POST',
                data: $(this).serialize(),
                success: function(data){
                    swal("Éxito", data.message, "success");
                    $('#depositoModal').modal('hide');
                    $('#formDeposito')[0].reset();
                },
                error: function(){
                    swal("Error", "No se pudo guardar el depósito", "error");
                }
            });
        });

        $(document).ready(function () {
            // Al cambiar la caja, obtiene el último saldo
            $('#cash_box_id_modal').on('change', function(){
                const id = $(this).val();
                if(id) {
                    $.get('{{ url("ajax/last-cash-balance") }}', { cash_box_id: id }, function(data) {
                        if(data.count > 0) {
                            $('#last_cash_balance_modal').val(data.date+' - '+$.number(data.residue, 0, ',', '.'));
                        } else {
                            $('#last_cash_balance_modal').val('Sin movimientos');
                        }
                        $('#amount_modal').val(data.residue);
                    });
                }
            });

            // Guardar el formulario
            $('#formApertura').on('submit', function(e){
                e.preventDefault();

                $.ajax({
                    url: '{{ route("cash_box_balances.store") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        $('#modalAperturaCaja').modal('hide');
                        swal({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message || 'Apertura de caja registrada correctamente',
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        var mensaje = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '';
                        swal({
                            icon: 'error',
                            title: 'Error',
                            text: mensaje ?? 'No se pudo guardar la apertura de caja',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            });
        });
    </script>
@endsection
