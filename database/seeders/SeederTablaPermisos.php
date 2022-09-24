<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

//spatie
use Spatie\Permission\Models\Permission;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin']);

        Permission::create(['name' => 'ver-usuario'])->syncRoles([$role1]); 
        Permission::create(['name' => 'crear-usuario'])->syncRoles([$role1]); 
        Permission::create(['name' => 'editar-usuario'])->syncRoles([$role1]); 
        Permission::create(['name' => 'borrar-usuario'])->syncRoles([$role1]); 
        Permission::create(['name' => 'ver-rol'])->syncRoles([$role1]); 
        Permission::create(['name' => 'crear-rol'])->syncRoles([$role1]); 
        Permission::create(['name' => 'editar-rol'])->syncRoles([$role1]); 
        Permission::create(['name' => 'borrar-rol'])->syncRoles([$role1]); 
        Permission::create(['name' => 'ver-estados'])->syncRoles([$role1]); 
        Permission::create(['name' => 'crear-estados'])->syncRoles([$role1]); 
        Permission::create(['name' => 'editar-estados'])->syncRoles([$role1]); 
        Permission::create(['name' => 'borrar-estados'])->syncRoles([$role1]); 
        Permission::create(['name' => 'ver-marcas'])->syncRoles([$role1]); 
        Permission::create(['name' => 'crear-marcas'])->syncRoles([$role1]); 
        Permission::create(['name' => 'editar-marcas'])->syncRoles([$role1]); 
        Permission::create(['name' => 'borrar-marcas'])->syncRoles([$role1]); 
        Permission::create(['name' => 'ver-tiposequipos'])->syncRoles([$role1]); 
        Permission::create(['name' => 'crear-tiposequipos'])->syncRoles([$role1]); 
        Permission::create(['name' => 'editar-tiposequipos'])->syncRoles([$role1]); 
        Permission::create(['name' => 'borrar-tiposequipos'])->syncRoles([$role1]); 
        Permission::create(['name' => 'ver-tiposaccesorios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'crear-tiposaccesorios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'editar-tiposaccesorios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'borrar-tiposaccesorios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'ver-tiposervicios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'crear-tiposervicios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'editar-tiposervicios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'borrar-tiposervicios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'ver-servicios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'crear-servicios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'editar-servicios'])->syncRoles([$role1]); 
        Permission::create(['name' => 'borrar-servicios'])->syncRoles([$role1]); 

    }
}
