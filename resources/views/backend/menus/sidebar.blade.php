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
                    <a href="{{ route('index.ordenes.canceladas') }}" target="frameprincipal" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ordenes Canceladas</p>
                    </a>
                </li>



                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-edit"></i>
                        <p>
                            Motoristas
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('index.nuevo.motorista.direccion') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Direcciones Cliente</p>
                            </a>
                        </li>
                    </ul>


                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('index.nuevo.motorista') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo Motorista</p>
                            </a>
                        </li>
                    </ul>

                </li>





                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-edit"></i>
                            <p>
                                Servicios
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('index.bloques') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Servicios</p>
                                </a>
                            </li>

                           <!-- <li class="nav-item">
                                <a href="{{ route('index.sliders') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Slider</p>
                                </a>
                            </li>
                            -->

                        </ul>
                    </li>


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-edit"></i>
                        <p>
                            Clientes
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('index.clientes.registrados.hoy') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrados Hoy</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('index.clientes.listado') }}" target="frameprincipal" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listado de Clientes</p>
                            </a>
                        </li>


                    </ul>
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
                                <a href="{{ route('index.zonas') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Zonas</p>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('index.horario') }}" target="frameprincipal" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Horario</p>
                                </a>
                            </li>
                        </ul>

                    </li>


                    <li class="nav-item">
                        <a href="{{ route('index.estadisticas') }}" target="frameprincipal" class="nav-link">
                            <i class="fas fa-edit nav-icon"></i>
                            <p>Estadísticas</p>
                        </a>
                    </li>


            <!-- fin del acordeon -->
            </ul>
        </nav>




    </div>
</aside>






