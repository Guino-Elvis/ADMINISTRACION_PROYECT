<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Empresa']);
        $role3 = Role::create(['name' => 'Cliente']);
        $role4 = Role::create(['name' => 'Developer']);
        $role5 = Role::create(['name' => 'QA']);
        $role6 = Role::create(['name' => 'UI/UX']);
        $role7 = Role::create(['name' => 'Scrum Master']);
        $role8 = Role::create(['name' => 'Product Owner']);



        //autenticacion
        Permission::create(['name' => 'auth.logout', 'description' => 'Cerrar sesión'])->syncRoles([$role1, $role2, $role3, $role4]);
        Permission::create(['name' => 'auth.me', 'description' => 'Ver usuario autenticado'])->syncRoles([$role1, $role2, $role3, $role4]);
        Permission::create(['name' => 'auth.refresh', 'description' => 'Refrescar token de sesión'])->syncRoles([$role1, $role2, $role3, $role4]);
        //roles
        Permission::create(['name' => 'admin.roles', 'description' => 'Ver listado de roles'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.roles.edit', 'description' => 'Editar roles'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.roles.create', 'description' => 'Crear roles'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.roles.assign.permission', 'description' => 'Asignar permisos al rol'])->syncRoles([$role1, $role2]);
        //compañias
        Permission::create(['name' => 'admin.companies', 'description' => 'Ver listado de empresa'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.companies.create', 'description' => 'Crear empresa'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.companies.edit', 'description' => 'Editar empresa'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.companies.delete', 'description' => 'Eliminar empresa'])->syncRoles([$role1, $role2]);
        //usuarios por compañia
        Permission::create(['name' => 'admin.usercompanies', 'description' => 'Ver listado usuarios por empresa'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.usercompanies.create', 'description' => 'Crear usuarios por empresa'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.usercompanies.edit', 'description' => 'Editar usuarios por empresa'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.usercompanies.delete', 'description' => 'Eliminar usuarios por empresa'])->syncRoles([$role1, $role2]);
        //usuarios
        Permission::create(['name' => 'admin.users', 'description' => 'Ver listado de usuarios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.users.create', 'description' => 'Crear usuarios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.users.edit', 'description' => 'Editar usuarios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.users.delete', 'description' => 'Eliminar usuarios'])->syncRoles([$role1, $role2]);
        //proyectos
        Permission::create(['name' => 'admin.projects', 'description' => 'Ver listado de proyectos'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.projects.create', 'description' => 'Crear proyectos'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.projects.edit', 'description' => 'Editar proyectos'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'admin.projects.delete', 'description' => 'Eliminar proyectos'])->syncRoles([$role1, $role2]);


        // Miembros de proyecto
        Permission::create(['name' => 'project.members.manage', 'description' => 'Gestionar miembros del proyecto'])->syncRoles([$role1, $role2]);

        // Tareas
        Permission::create(['name' => 'tasks.view', 'description' => 'Ver tareas'])->syncRoles([$role1, $role2, $role4, $role5, $role6, $role7]);
        Permission::create(['name' => 'tasks.create', 'description' => 'Crear tareas'])->syncRoles([$role1, $role2, $role4, $role6]);
        Permission::create(['name' => 'tasks.edit', 'description' => 'Editar tareas'])->syncRoles([$role1, $role2, $role4, $role6]);
        Permission::create(['name' => 'tasks.delete', 'description' => 'Eliminar tareas'])->syncRoles([$role1, $role2]);

        // Comentarios de tareas
        Permission::create(['name' => 'tasks.comment', 'description' => 'Comentar en tareas'])->syncRoles([$role1, $role2, $role4, $role5, $role6, $role7]);

        // Sprints
        Permission::create(['name' => 'sprints.view', 'description' => 'Ver sprints'])->syncRoles([$role1, $role2, $role4, $role5, $role6, $role7]);
        Permission::create(['name' => 'sprints.create', 'description' => 'Crear sprints'])->syncRoles([$role1, $role2, $role7]);
        Permission::create(['name' => 'sprints.edit', 'description' => 'Editar sprints'])->syncRoles([$role1, $role2, $role7]);
        Permission::create(['name' => 'sprints.delete', 'description' => 'Eliminar sprints'])->syncRoles([$role1]);

        // Documentos / Archivos
        Permission::create(['name' => 'documents.view', 'description' => 'Ver archivos'])->syncRoles([$role1, $role2, $role4, $role5, $role6]);
        Permission::create(['name' => 'documents.upload', 'description' => 'Subir archivos'])->syncRoles([$role1, $role2, $role4, $role5, $role6]);
        Permission::create(['name' => 'documents.delete', 'description' => 'Eliminar archivos'])->syncRoles([$role1, $role2]);

        // Repositorios
        Permission::create(['name' => 'repositories.view', 'description' => 'Ver repositorios'])->syncRoles([$role1, $role2, $role4, $role5]);
        Permission::create(['name' => 'repositories.link', 'description' => 'Vincular repositorio'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'repositories.unlink', 'description' => 'Desvincular repositorio'])->syncRoles([$role1]);

        // Commits y pushes
        Permission::create(['name' => 'commits.view', 'description' => 'Ver commits'])->syncRoles([$role1, $role2, $role4, $role5]);
        Permission::create(['name' => 'pushes.view', 'description' => 'Ver pushes'])->syncRoles([$role1, $role2, $role4, $role5]);

        // Estadísticas
        Permission::create(['name' => 'dashboard.view', 'description' => 'Ver panel de control'])->syncRoles([$role1, $role2, $role7, $role8]);
    }
}
