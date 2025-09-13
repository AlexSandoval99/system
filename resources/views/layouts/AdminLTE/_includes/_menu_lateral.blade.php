<style>
    .main-sidebar {
        position: fixed; /* Fija la posición del sidebar */
        top: 0;
        left: 0;
        height: 100vh; /* Ocupa toda la altura de la pantalla */
        overflow-y: auto; /* Habilita el scroll vertical */
        overflow-x: hidden; /* Desactiva el scroll horizontal */
        background-color: #222d32; /* Fondo del sidebar */
        scrollbar-width: thin; /* Para navegadores como Firefox */
        scrollbar-color: #444 transparent; /* Color de la barra y el fondo */
    }

    /* Estilo específico para navegadores WebKit (Chrome, Edge, Safari) */
    .main-sidebar::-webkit-scrollbar {
        width: 6px; /* Ancho del scroll vertical */
    }

    .main-sidebar::-webkit-scrollbar-thumb {
        background-color: #444; /* Color del scroll */
        border-radius: 3px; /* Bordes redondeados */
    }

    .main-sidebar::-webkit-scrollbar-track {
        background-color: transparent; /* Fondo del track */
    }

    .sidebar {
        padding-bottom: 20px; /* Espacio adicional para un mejor diseño */
    }

    .sidebar-menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar-menu > li {
        margin: 5px 0;
    }
</style>

<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" data-widget="tree">
			<li class="header" style="color:#fff;"> MENU <i class="fa fa-level-down"></i></li>
			<li class=" ">
				<a href="{{ route('home') }}" title="Dashboard"><i class="fa fa-dashboard"></i> <span>Tablero</span></a>
			</li>

			@if(Request::segment(1) === 'profile')
			<li class="{{ Request::segment(1) === 'profile' ? 'active' : null }}">
				<a href="{{ route('profile') }}" title="Profile"><i class="fa fa-user"></i> <span> PERFILES</span></a>
			</li>

			@endif
            @auth
                <li class="treeview">
                    <a href="#"><i class="fa fa-gear"></i><span>Configuracion</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                    <ul class="treeview-menu">
                        {{-- @if (Auth::user()->can('root-dev', '')) --}}
                            <li class="{{ Request::segment(1) === 'config' && Request::segment(2) === null ? 'active' : null }}">
                                <a href="{{ route('config') }}" title="App Config">
                                 <span> Configuracion</span>
                                </a>
                            </li>
                        {{-- @endif --}}
                        <li class="user">
                            <a href="{{ route('user') }}" title="Users">
                             <span> Usuarios</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('nationalities') }}" title="Marca">
                             <span>Nacionalidad</span>
                            </a>
                        </li>
                        <li class="cliente">
                            <a href="{{ route('cliente') }}" title="Cliente">
                                <span> Cliente</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('provider') }}" title="Proveedor">
                                <span> Proveedor</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-box"></i><span>Caja</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                    <ul class="treeview-menu">
                        <li class="provider">
                            <a href="{{ route('cash_boxes.index') }}" title="Marca">
                                <span>Caja</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('voucher_boxes.index') }}" title="Marca">
                                <span>Punto Expedicion</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('stampeds.index') }}" title="Marca">
                                <span>Timbrado</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('cash_box_balances.index') }}" title="Marca">
                                <span>Apertura de Caja</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-product-hunt"></i><span>Producto</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                    <ul class="treeview-menu">
                        <li class="articulo">
                            <a href="{{ route('articulo') }}" title="Articulo">
                                <span> Articulo</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('brand') }}" title="Marca">
                                <span>Marca</span>
                            </a>
                        </li>
                        <li class="Materia Prima">
                            <a href="{{ route('raw-materials') }}" title="Materia Prima">
                                <span>Materia Prima</span>
                            </a>
                        </li>
                        <li class="stage">
                            <a href="{{ route('production-stage') }}" title="Etapa">
                                <span> Etapa Produccion</span>
                            </a>
                        </li>
                        <li class="stage">
                            <a href="{{ route('production-quality') }}" title="Etapa">
                                <span> Calidad Produccion</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-shopping-cart"></i><span>Compras</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                    <ul class="treeview-menu">
                        <li class="provider">
                            <a href="{{ route('wish-purchase') }}" title="Pedido de Compras">
                                <span>Pedido de Compras</span>
                            </a>
                            <a href="{{ route('purchase-order') }}" title="Orden de Compras">
                                <span>Orden de Compras</span>
                            </a>
                            <a href="{{ route('purchase') }}" title="Factura Compras">
                                <span>Factura Compras</span>
                            </a>
                            <a href="{{ route('inventories') }}" title="Inventario">
                                <span>Inventario</span>
                            </a>
                            <a href="{{ route('reports.stock-product-purchases') }}" title="Existencia">
                                <span>Existencia</span>
                            </a>
                            <a href="{{ route('reports.purchases_report') }}" title="Libro Compra">
                                <span>Libro Compra</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa-wrench"></i><span>Produccion</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                    <ul class="treeview-menu">
                        <li class="provider">
                            <a href="{{ route('budget-production') }}" title="Presupuesto">
                                <span>Presupuesto</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('production-order') }}" title="Presupuesto">
                                <span>Orden de Produccion</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('production-control') }}" title="Presupuesto">
                                <span>Control de Produccion</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('production-control-quality') }}" title="Presupuesto">
                                <span>Control de Calidad</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('losses') }}" title="Presupuesto">
                                <span>Mermas</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('production-cost') }}" title="Costo Produccion">
                                <span>Costo Produccion</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class="fa fa fa-shopping-basket"></i><span>Ventas</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                    <ul class="treeview-menu">
                        <li class="provider">
                            <a href="{{ route('wish-production') }}" title="Pedido Ventas">
                                <span>Pedidos</span>
                            </a>
                        </li>
                        <li class="provider">
                            <a href="{{ route('vouchers') }}" title="Factura Ventas">
                                <span>Factura Ventas</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endauth

	</section>
</aside>
