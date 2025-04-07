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
                    $item->id_html = "DivModulo" . $item->id_navegacion;
                    $modulos[] = $item; // Módulos principales
                } elseif (isset($item->id_padre_navegacion) && $item->id_padre_navegacion != NULL) {
                    // Submódulos y sub-submódulos, lo asignamos según el id_padre_navegacion
                    if (in_array($item->id_padre_navegacion, array_column($modulos, 'id_navegacion'))) {
                        $item->id_html = "DivSubModulo" . $item->id_navegacion;
                        $submodulos[] = $item; // Submódulos
                    } else {
                        $item->id_html = "DivSubSubModulo" . $item->id_navegacion;
                        $subsubmodulos[] = $item; // Sub-submódulos
                    }
                }
            }

            // Mostrar los módulos principales
            foreach ($modulos as $modulo): ?>
                <li class="menu">
                    <a id="<?= $modulo->id_html; ?>"
                        <?php if (!empty($modulo->link_navegacion)): ?>
                        href="javascript:void(0);" onclick="Cambio_Navegacion('<?= $modulo->link_navegacion; ?>','<?= $modulo->id_html ?>',null,null; )"
                        <?php else: ?>
                        href="#Modulo<?php echo $modulo->id_navegacion; ?>"
                        data-toggle="collapse"
                        aria-expanded="false"
                        <?php endif; ?>
                        class="dropdown-toggle">
                        <div>
                            <?php echo $modulo->svg_navegacion; ?>
                            <span><?php echo $modulo->titulo_navegacion; ?></span>
                        </div>
                        <?php if (empty($modulo->link_navegacion)): ?>
                            <div>
                                <svg class="flecha" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </a>

                    <?php if (empty($modulo->link_navegacion)): ?>
                        <ul class="collapse submenu list-unstyled" id="Modulo<?php echo $modulo->id_navegacion; ?>" data-parent="#accordionExample">
                            <?php foreach ($submodulos as $submodulo):
                                if ($submodulo->id_padre_navegacion == $modulo->id_navegacion): ?>
                                    <li class="submenu">
                                        <a id="<?php echo $submodulo->id_html ?>"
                                            <?php if (!empty($submodulo->link_navegacion)): ?>
                                            href="javascript:void(0);" onclick="Cambio_Navegacion('<?= $submodulo->link_navegacion; ?>','<?= $modulo->id_html; ?>','<?= $submodulo->id_html; ?>',null )"
                                            <?php else: ?>
                                            href="#SubModulo<?php echo $submodulo->id_navegacion; ?>"
                                            data-toggle="collapse"
                                            aria-expanded="false"
                                            
                                            <?php endif; ?>
                                            class="dropdown-toggle">
                                            <div>
                                                <?php echo $submodulo->svg_navegacion; ?>
                                                <span><?php echo $submodulo->titulo_navegacion; ?></span>
                                            </div>
                                            <?php //echo $submodulo->titulo_navegacion; ?>
                                            <?php if (empty($submodulo->link_navegacion)): ?>
                                                <div>
                                                    <svg class="flecha" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-chevron-right">
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </a>

                                        <?php if (empty($submodulo->link_navegacion)): ?>
                                            <ul class="collapse list-unstyled sub-submenu" id="SubModulo<?php echo $submodulo->id_navegacion; ?>" data-parent="#SubModulo<?php echo $submodulo->id_navegacion; ?>">
                                                <?php foreach ($subsubmodulos as $subsubmodulo):
                                                    if ($subsubmodulo->id_padre_navegacion == $submodulo->id_navegacion):
                                                ?>
                                                    <li class="sub-submenu">
                                                        <a <?php if (!empty($subsubmodulo->link_navegacion)): ?>
                                                            href="javascript:void(0);" onclick="Cambio_Navegacion('<?= $subsubmodulo->link_navegacion; ?>','<?= $modulo->id_html; ?>','<?= $submodulo->id_html; ?>','<?= $subsubmodulo->id_html; ?>' )"
                                                            <?php else: ?>
                                                            href="#"
                                                            <?php endif; ?>
                                                            id="<?php echo $subsubmodulo->id_html ?>">
                                                            <div>
                                                            <?php echo $subsubmodulo->svg_navegacion; ?>
                                                            <span><?php echo $subsubmodulo->titulo_navegacion; ?></span>
                                                        </div>
                                                        </a>
                                                        
                                                    </li>
                                                <?php endif;
                                                endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                            <?php endif;
                            endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <script>
        function Cambio_Navegacion(url, Mid, MSid = null, MSSid = null) {
            let fullUrl = "<?= site_url(); ?>" + url;

            //alert(Mid+" "+MSid+" "+MSSid+" "); // Confirma que los datos son correctos antes de enviarlos
           
            $.ajax({
                type: "POST",
                url: fullUrl,
                data: {
                    'Mid': Mid,
                    'MSid': MSid,
                    'MSSid': MSSid,
                    'url' : url
                },
                success: function(resp) {
                    //location.href = fullUrl;
                    $('#modulos').html(resp);
                }
            });
        }
    </script>
</div>
<style>
    #sidebar ul.menu-categories li.menu ul.submenu > li a:before {
        content: none !important;
        background: none !important;
        position: static !important;
        height: 0 !important;
        width: 0 !important;
    }
</style>