<?php
namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            [
                "name" => "View User Dashboard",
                "slug" => "view-user-dashboard"
            ],
            [
                "name" => "View Rider Dashboard",
                "slug" => "view-rider-dashboard"
            ],
            [
                "name" => "View Admin Dashboard",
                "slug" => "view-admin-dashboard"
            ],
            [
                "name" => "View Developer Dashboard",
                "slug" => "view-developer-dashboard"
            ]
        ]);
    }
}
