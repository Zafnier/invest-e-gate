<?php

namespace Database\Seeders;

use App\Models\Admin\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = array(
            array('id' => '1','name' => 'English','code' => 'en','status' => '1','last_edit_by' => '1','created_at' => NULL,'updated_at' => NULL,'dir' => 'ltr'),
            array('id' => '2','name' => 'Spanish','code' => 'es','status' => '0','last_edit_by' => '1','created_at' => NULL,'updated_at' => NULL,'dir' => 'ltr'),
            array('id' => '3','name' => 'Arabic','code' => 'ar','status' => '0','last_edit_by' => '1','created_at' => '2023-11-04 17:04:04','updated_at' => '2023-11-04 17:04:04','dir' => 'rtl')
        );

        Language::insert($languages);
    }
}
