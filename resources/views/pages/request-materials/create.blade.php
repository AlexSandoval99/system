@extends('layouts.AdminLTE.index')
@section('title', 'Nuevo Pedido de Materia Prima')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-primary">
            <div class="card-header">
                <h5>Agregar Pedido de Materia Prima</h5>
            </div>
            <div class="card-body">

                <!-- Datos principales -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="solicitado">Solicitado por</label>
                        <input type="text" class="form-control" id="solicitado" value="Alex Sandoval" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="sucursal">Sucursal</label>
                        <select class="form-control" id="sucursal">
                            <option value="1">Central</option>
                            <option value="2">Sucursal 2</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <hr>
                <h5>Items a Solicitar</h5>

                <!-- Campos para cargar item -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="producto">Producto</label>
                        <select class="form-control" id="producto">
                            <option value="">Seleccione Materia Prima</option>
                            <option value="1">Tela Algodón</option>
                            <option value="2">Cinta Elástica</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" min="1">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-success w-100" id="btnAgregar">
                            <i class="fa fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>

                <!-- Tabla de detalles -->
                <table class="table table-bordered" id="tablaDetalles">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center">No hay items cargados</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Observación -->
                <div class="mb-3">
                    <label for="observacion">Observación</label>
                    <textarea id="observacion" class="form-control" rows="3"></textarea>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-success me-2">Guardar</button>
                    <button type="button" class="btn btn-danger">Cancelar</button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Script para manejar items -->
<script>
    let contador = 0;

    document.getElementById("btnAgregar").addEventListener("click", function () {
        let producto = document.getElementById("producto");
        let cantidad = document.getElementById("cantidad").value;

        if (producto.value === "" || cantidad <= 0) {
            alert("Debe seleccionar un producto y una cantidad válida");
            return;
        }

        let tabla = document.querySelector("#tablaDetalles tbody");

        if (tabla.children[0].children.length === 1) {
            tabla.innerHTML = ""; // limpiar "No hay items"
        }

        contador++;
        let fila = `
            <tr>
                <td>${contador}</td>
                <td>${producto.options[producto.selectedIndex].text}</td>
                <td>${cantidad}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button>
                </td>
            </tr>
        `;
        tabla.insertAdjacentHTML("beforeend", fila);

        // limpiar campos
        producto.value = "";
        document.getElementById("cantidad").value = "";
    });

    function eliminarFila(boton) {
        let fila = boton.closest("tr");
        fila.remove();
    }
</script>
@endsection
