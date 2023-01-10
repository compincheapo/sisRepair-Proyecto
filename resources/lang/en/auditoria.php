<?php
return [
    'unavailable_audits' => 'No Article Audits available',


    'App\Models\Equipo' => [
        'updated'            => [
            'metadata' => 'On :audit_created_at, :user_name [:audit_ip_address] updated this record via :audit_url',
            'modified' => [
                'serie'   => 'El número de Serie ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'modelo' => 'El modelo ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'id_seccionestante' => 'El id_seccionestante ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'id_marca' => 'El id_marca ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'id_tipoequipo' => 'El id_tipoequipo ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'id_user' => 'El id_user ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
            ],
        ],
        'created' => [
            'id' => 'El ID ha sido creado: <strong>:new</strong>',
            'serie'   => 'El número de Serie ha sido creado: <strong>:new</strong>',
            'modelo' => 'El modelo ha sido creado: <strong>:new</strong>',
            'id_seccionestante' => 'El id_seccionestante ha sido creado <strong>:new</strong>',
            'id_marca' => 'El id_marca ha sido creado: <strong>:new</strong>',
            'id_tipoequipo' => 'El id_tipoequipo ha sido creado: <strong>:new</strong>',
            'id_user' => 'El id_user ha sido creado: <strong>:new</strong>',
        ],
    ],
    'App\Models\User' => [
        'updated'            => [
            'metadata' => 'On :audit_created_at, :user_name [:audit_ip_address] updated this record via :audit_url',
            'modified' => [
                'name'   => 'El nombre ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'lastname' => 'El apellido ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'username' => 'El nombre de usuario ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'email' => 'El email ha sido modificado de <strong>:old</strong> a <strong>:new</strong>',
                'updated_at' => 'La fecha de modificación ha cambiado de <strong>:old</strong> a <strong>:new</strong>',
            ],
        ],
        'created' => [
            'id' => 'El ID ha sido creado: <strong>:new</strong>',
            'name'   => 'El nombre ha sido creado: <strong>:new</strong>',
            'lastname' => 'El apellido ha sido creado: <strong>:new</strong>',
            'username' => 'El nombre de usuario ha sido creado: <strong>:new</strong>',
            'email' => 'El email ha sido creado: <strong>:new</strong>',
            'password' => 'El password ha sido creado: <strong>:new</strong>',
        ],
    ],

];