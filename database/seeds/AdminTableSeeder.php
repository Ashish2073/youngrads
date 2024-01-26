<?php

use Illuminate\Database\Seeder;
use App\Admin;
use App\Role;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::truncate();
        $role = Role::create([
            'name' => 'Admin',
            'guard_name' => 'admin'
        ]);
        if($admin->count() == 0) {
            $admin = Admin::create([
                'first_name' => 'Administrator',
                'email' => 'admin@app.com',
                'password' => bcrypt('password')
            ]);
            $admin->assignRole('Admin');
            $this->command->info('Created Admin: Email: admin@app.com Password: password');
        } else {
            $this->command->info('Admin user already created!');
        }
    }
}
