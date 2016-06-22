<?php

use Illuminate\Database\Seeder;

class ScanTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scan_tasks')->insert([
            'task_id' => '12345',
            'scan_profile_id' => 1,
            'target' => 'www.pape.de',
            'progress' => 'not-started',
        ]);
    }
}
