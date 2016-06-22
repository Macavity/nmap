<?php

use Illuminate\Database\Seeder;

class ScanProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('scan_profiles')->insert([
            'label' => 'intense',
            'description' => 'Sample <intense> scan task, copied from zenmap',
            'command' => '-T4 -A -v',
        ]);
    }
}
