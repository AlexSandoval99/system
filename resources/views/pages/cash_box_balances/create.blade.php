@extends('layouts.AdminLTE.index')
@section('title', 'Agregar Apertura de Caja')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Agregar Saldo Inicial de Caja</h5>
            </div>
            {{ Form::open(['id' => 'form']) }}
                <div class="ibox-content">
                    @include('partials.messages')
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Caja</label>
                            {{ form::select('cash_box_id', $cash_boxes, old('cash_box_id'), ['class' => 'form-control selectpicker','data-live-Search'=> 'true', 'id' => 'cash_box_id']) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Ultimo Saldo Caja</label>
                            <input type="text" name="last_cash_balance" class="form-control" id="last_cash_balance" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Monto Saldo Inicial</label>
                            <input type="text" name="amount" class="form-control" id="amount" period-data-mask readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-7">
                            <label>Observación</label>
                            <textarea class="form-control" name="observation">{{ old('observation') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="ibox-footer">
                    <input type="submit" class="btn btn-sm btn-success" value="Guardar">
                    <a href="{{ url('cash_box_balances') }}" class="btn btn-sm btn-danger">Cancelar</a>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
@section('layout_js')
    <script>
        $(document).ready(function ()
        {
            $('#form').submit(function(e)
            {
                $('input[type="submit"]').prop('disabled', true);
                e.preventDefault();
                $.ajax({
                    url: '{{ route('cash_box_balances.store') }}',
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data) {
                        redirect ("{{ url('/') }}");
                    },
                    error: function(data){
                        laravelErrorMessages(data);
                        $('input[type="submit"]').prop('disabled', false);
                    }
                });
            });

            $("select[name='cash_box_id']").on('change', function(){
                changeLastCashBalance();
            });
        });

        changeLastCashBalance();
        function changeLastCashBalance()
        {
            var cash_box_id = $("#cash_box_id").val();
            $("#last_cash_balance").val('');

            if(cash_box_id > 0)
            {
                $.ajax({
                    url: "{{ url('ajax/last-cash-balance') }}",
                    type: "GET",
                    data: {
                        cash_box_id : $("#cash_box_id").val()
                    },
                    success: function(data) {
                        if( data.count > 0 )
                        {
                            $("#last_cash_balance").val(data.date+' - '+$.number(data.residue, 0, ',', '.'));
                        }
                        $("#amount").val(data.residue);
                    }
                });
            }
        }
    </script>
@endsection
