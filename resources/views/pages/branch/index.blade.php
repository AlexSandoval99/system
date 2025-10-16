@extends('layouts.AdminLTE.index')
@section('title', 'Sucursal ')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-tools">
                <div class="btn-group pull-right">
                    <a href="{{ url('branch/create') }}" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Sucursal</a>
                </div>
            </div>
            <table class="table table-striped table-condensed table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                        <tr>
                            <td>{{ $branch->name }}</td>
                            <td><span class="label label-{{ config('constants.status-label.' . $branch->status) }}">{{ config('constants.status.' . $branch->status) }}</td>
                            <td class="text-center">
                                <a href="{{ url('branch/' . $branch->id . '/edit') }}"target="_blank" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                <a href="{{ url('branch/' . $branch->id) }}"><i class="fa fa-info-circle"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
