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
                                    @if($cash_box_balance->status == 2)
                                        <a href="#" class="text-info" title="Arqueo" data-toggle="modal" data-target="#arqueoModal">
                                            <i class="fa fa-cash-register"></i>
                                        </a>
                                        <a href="#" class="text-info" title="Recaudación" data-toggle="modal" data-target="#depositoModal">
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
        <div class="modal-body">

          <!-- EFECTIVO -->
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
                @foreach([100, 500, 1000, 10000, 100000] as $den)
                <tr>
                  <td>{{ number_format($den, 0, ',', '.') }} Gs</td>
                  <td>
                    <input type="number" min="0" class="form-control cantidad" data-den="{{ $den }}" value="0">
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
        <div class="modal-body">

          <!-- EFECTIVO -->
          <h6><strong>Efectivo</strong></h6>
          <div class="form-group">
            <label for="montoEfectivo">Monto Efectivo (Gs):</label>
            <input type="number" class="form-control" id="montoEfectivo" name="montoEfectivo" min="0" value="0" required>
          </div>

          <!-- CHEQUES -->
          <h6><strong>Cheques</strong></h6>
          <div class="form-group">
            <label for="montoCheque">Monto Cheques (Gs):</label>
            <input type="number" class="form-control" id="montoCheque" name="montoCheque" min="0" value="0" required>
          </div>

          <!-- BANCO O DESTINO -->
          <div class="form-group">
            <label for="bancoDestino">Banco / Destino:</label>
            <input type="text" class="form-control" id="bancoDestino" name="bancoDestino" placeholder="Ej: Banco Itaú" required>
          </div>

          <!-- TOTAL AUTOMÁTICO -->
          <div class="form-group">
            <label><strong>Total a Depositar:</strong></label>
            <input type="text" readonly class="form-control" id="totalDeposito" value="0">
          </div>

          <!-- OBSERVACIONES -->
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
        });
    </script>
@endsection
