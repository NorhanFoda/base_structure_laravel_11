<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $permissions = Permission::where('guard_name', 'sanctum')->get();
        $role = Role::findOrCreate('admin', 'sanctum');
        $role->givePermissionTo($permissions);
        $user = User::where('email' , 'admin@admin.com')->first();
        if (!$user){
            $user = User::create([
                'name' => 'admin',
                // 'identification' => '123456789012345',
                'email' => 'admin@admin.com',
                // 'phone' => 12345678910,
                'password' => Hash::make('123456')
                
            ]);
        }
        $user->assignRole($role);

        // for ($i = 10002; $i <= 20000; $i++) {
        //     $user = User::create([
        //         'name' => 'user '.$i,
        //         // 'identification' => rand(100000000000000, 199999999999999),
        //         'email' => 'user_'.$i.'@user.com',
        //         // 'phone' => rand(1000000000, 19999999999),
        //         'password' => Hash::make('123456')
                
        //     ]);
            
        //     $user->assignRole($role);

        // }
    }
}
