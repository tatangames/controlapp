<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" >
        <span class="brand-text font-weight-light">Panel Web</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


                <li class="nav-item">
                    <a href="{{ route('index.ordenes.pendientes') }}" target="frameprincipal" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ordenes Pendientes</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('index.ordenes.hoy') }}" target="frameprincipal" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ordenes Iniciadas Hoy</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('index.ordenes.todas') }}" target="frameprincipal" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Todas las Ordenes</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('index.bloques') }}" target="frameprincipal" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Servicios</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('index.clientes.listado') }}" target="frameprincipal" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Listado de Clientes</p>
                    </a>
                </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-edit"></i>
                            <p>
                                Configuración
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('index.horario') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Horario</p>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('index.cerrar.app') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cerrar App</p>
                                </a>
                            </li>
                        </ul>

                    </li>



            <!-- fin del acordeon -->
            </ul>
        </nav>




    </div>
</aside>






