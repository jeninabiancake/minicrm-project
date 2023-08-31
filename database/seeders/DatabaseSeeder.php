<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(UsersTableSeeder::class);
        // inserting document types
        foreach (config('seed_data.document_types') as $value) {
            DB::table('document_type')->insert([
                'name' => $value
            ]);
        }
        // insert task status
        foreach (config('seed_data.task_statuses') as $value) {
            DB::table('task_status')->insert([
                'name' => $value
            ]);
        }
        // insert task types
        foreach (config('seed_data.task_types') as $value) {
            DB::table('task_type')->insert([
                'name' => $value
            ]);
        }
        // insert contact status
        foreach (config('seed_data.contact_status') as $value) {
            DB::table('contact_status')->insert([
                'name' => $value
            ]);
        }
        // insert the system main email into settings table
        foreach (config('seed_data.settings') as $key => $value) {
            DB::table('setting')->insert([
                'setting_key' => $key,
                'setting_value' => $value
            ]);
        }
        // insert sample user as the system admin
        DB::table('users')->insert([
           'name' => 'admin',
           'email' => 'admin@my-crm.com',
           'password' => Hash::make('myminicrm123'),
           'position_title' => 'sales manager',
           'is_admin' => 1,
           'is_active' => 1
        ]);
        // insert the initial permissions
        $permissions = [];
        foreach (config('seed_data.permissions') as $value) {
            $permissions[] = \Spatie\Permission\Models\Permission::create(['name' => $value]);
        }
    }
}
