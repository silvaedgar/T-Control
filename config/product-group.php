<?php

return [
    'layout' => ['activePage' => 'maintenance', 'titlePage' => 'Modulo Grupos de Productos'],
    'header' => ['title' => 'Grupo de Producto', 'subTitle' => 'Detalle del Grupo', 'form' => 'maintenance.product-groups.form',
                'buttons' => ['title' => 'Crear Grupo', 'url' => 'maintenance.productgroups.create', 'icon' => 'person_add']],   
    'table' => ['header' => ['Item', 'Descripción', 'Acción'], 'include' => 'maintenance.product-groups.table'],
];
