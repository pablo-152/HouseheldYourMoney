<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">
    
<!-- application/views/login_view.php -->

<nav id="sidebar">
    <div class="shadow-bottom"></div>
    <ul class="list-unstyled menu-categories" id="accordionExample">
        <?php
        // Creamos un arreglo para almacenar los módulos y submódulos
        $modulos = [];
        $submodulos = [];
        $subsubmodulos = [];

        // Organizar los elementos en módulos, submódulos y sub-submódulos
        foreach ($navegacion as $item) {
            if ($item->id_padre_navegacion == NULL) {
                $modulos[] = $item; // Módulos principales
            } elseif (isset($item->id_padre_navegacion) && $item->id_padre_navegacion != NULL) {
                // Submódulos y sub-submódulos, lo asignamos según el id_padre_navegacion
                if (in_array($item->id_padre_navegacion, array_column($modulos, 'id_navegacion'))) {
                    $submodulos[] = $item; // Submódulos
                } else {
                    $subsubmodulos[] = $item; // Sub-submódulos
                }
            }
        }
        // Mostrar los módulos principales
        foreach ($modulos as $modulo):
        ?>
            <li class="menu">
                <a href="#Modulo<?php echo $modulo->id_navegacion; ?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span><?php echo $modulo->titulo_navegacion; ?></span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                
                <ul class="collapse submenu list-unstyled" id="Modulo<?php echo $modulo->id_navegacion; ?>" data-parent="#accordionExample">
                    <?php foreach ($submodulos as $submodulo):
                        if ($submodulo->id_padre_navegacion == $modulo->id_navegacion):
                    ?>
                        <li>
                            <a href="#SubModulo<?php echo $submodulo->id_navegacion; ?>" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                <?php echo $submodulo->titulo_navegacion; ?>
                            
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" 
                                stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </a>
                            <!-- Sub-submódulos -->
                            <ul class="collapse list-unstyled sub-submenu" id="SubModulo<?php echo $submodulo->id_navegacion; ?>" data-parent="#SubModulo<?php echo $submodulo->id_navegacion; ?>">
                                <?php foreach ($subsubmodulos as $subsubmodulo):
                                    if ($subsubmodulo->id_padre_navegacion == $submodulo->id_navegacion):
                                ?>
                                    <li>
                                        <a href="#"><?php echo $subsubmodulo->titulo_navegacion; ?></a>
                                    </li>
                                    
                                <?php endif; endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>


</div>
<div id="content" class="main-content">
<!--  END SIDEBAR  -->