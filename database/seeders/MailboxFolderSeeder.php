<?php

namespace Database\Seeders;

use App\Models\MailboxFolder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailboxFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('seed_data.mailbox_folders') as $value) {
            MailboxFolder::create([
                'title' => $value['title'],
                'icon' => $value['icon']
            ]);
        }
    }
}
