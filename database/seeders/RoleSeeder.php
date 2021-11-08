<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            [
                "name" => "User",
                "slug" => "user"
            ],
            [
                "name" => "Rider",
                "slug" => "rider"
            ],
            [
                "name" => "Admin",
                "slug" => "admin"
            ],
            [
                "name" => "Developer",
                "slug" => "developer"
            ]
        ]);

        $userRole = Role::user()->firstOrFail();
        $userPermissions = Permission::whereIn('slug', ['view-user-dashboard'])->get()->pluck('id')->toArray();
        $userRole->permissions()->sync($userPermissions);

        $riderRole = Role::rider()->firstOrFail();
        $riderPermissions = Permission::whereIn('slug', ['view-rider-dashboard'])->get()->pluck('id')->toArray();
        $riderRole->permissions()->sync($riderPermissions);

        $adminRole = Role::admin()->firstOrFail();
        $adminPermissions = Permission::whereIn('slug', [
            'view-admin-dashboard',
            'view-user-dashboard',
            'view-rider-dashboard',
        ])->get()->pluck('id')->toArray();
        $adminRole->permissions()->sync($adminPermissions);

        $developerRole = Role::developer()->firstOrFail();
        $developerPermissions = Permission::whereIn('slug', [
            'view-developer-dashboard', 
            'view-user-dashboard',
            'view-rider-dashboard',
            'view-admin-dashboard',
        ])->get()->pluck('id')->toArray();
        $developerRole->permissions()->sync($developerPermissions);

    }
}
