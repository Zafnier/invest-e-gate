<?php

namespace Database\Seeders;

use App\Models\CategoryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category_types = array(
            array('id' => '1','name' => 'Charity','data' => '{"language":{"en":{"name":"Charity"},"es":{"name":null},"ar":{"name":null}}}','slug' => 'faq-charity','type' => '1','status' => '1','created_at' => '2023-03-02 10:48:59','updated_at' => '2024-03-19 11:54:25'),
            array('id' => '2','name' => 'Donation','data' => '{"language":{"en":{"name":"Donation"},"es":{"name":null},"ar":{"name":null}}}','slug' => 'faq-donation','type' => '1','status' => '1','created_at' => '2023-03-02 10:57:52','updated_at' => '2024-03-19 11:54:25'),
            array('id' => '3','name' => 'Medical & Aid Kit','data' => '{"language":{"en":{"name":"Medical & Aid Kit"},"es":{"name":"Botiqu\\u00edn m\\u00e9dico y de ayuda"},"ar":{"name":"\\u0637\\u0642\\u0645 \\u0637\\u0628\\u064a \\u0648\\u0645\\u0633\\u0627\\u0639\\u062f\\u0627\\u062a"}}}','slug' => 'medical-aid-kit','type' => '1','status' => '1','created_at' => '2023-03-02 10:58:30','updated_at' => '2024-03-21 05:21:18'),
            array('id' => '4','name' => 'Volenteer Team','data' => '{"language":{"en":{"name":"Volenteer Team"},"es":{"name":"Equipo de voluntarios"},"ar":{"name":"\\u0627\\u0644\\u0641\\u0631\\u064a\\u0642 \\u0627\\u0644\\u062a\\u0637\\u0648\\u0639\\u064a"}}}','slug' => 'volenteer-team','type' => '1','status' => '1','created_at' => '2023-03-02 10:58:41','updated_at' => '2024-03-21 05:20:45'),
            array('id' => '5','name' => 'Food and Water','data' => '{"language":{"en":{"name":"Food and Water"},"es":{"name":"Comida y agua"},"ar":{"name":"\\u0627\\u0644\\u063a\\u0630\\u0627\\u0621 \\u0648\\u0627\\u0644\\u0645\\u0627\\u0621"}}}','slug' => 'food-and-water','type' => '1','status' => '1','created_at' => '2023-03-02 10:58:51','updated_at' => '2024-03-21 05:20:22'),
            array('id' => '6','name' => 'Help Hoomeless People','data' => '{"language":{"en":{"name":"Help Hoomeless People"},"es":{"name":"Ayudar a las personas sin hogar"},"ar":{"name":"\\u062a\\u0627\\u0643\\u0631 \\u0643\\u0631\\u0648\\u0633\\u062a\\u0627\\u0643\\u0631 \\u0643\\u0631\\u0648\\u0633"}}}','slug' => 'help-hoomeless-people','type' => '1','status' => '1','created_at' => '2023-03-02 10:58:58','updated_at' => '2024-03-21 05:19:49'),
            array('id' => '7','name' => 'Charity','data' => '{"language":{"en":{"name":"Charity"},"es":{"name":"Caridad"},"ar":{"name":"\\u0635\\u062f\\u0642\\u0629"}}}','slug' => 'charity','type' => '2','status' => '1','created_at' => '2023-03-02 11:35:07','updated_at' => '2024-03-21 04:55:30'),
            array('id' => '8','name' => 'Donation','data' => '{"language":{"en":{"name":"Donation"},"es":{"name":"Donaci\\u00f3n"},"ar":{"name":"\\u0647\\u0628\\u0629"}}}','slug' => 'donation','type' => '2','status' => '1','created_at' => '2023-03-02 11:35:22','updated_at' => '2024-03-21 04:55:04'),
            array('id' => '9','name' => 'Medical & Aid Kit','data' => '{"language":{"en":{"name":"Medical & Aid Kit"},"es":{"name":"Botiqu\\u00edn m\\u00e9dico y de ayuda"},"ar":{"name":"\\u0637\\u0642\\u0645 \\u0637\\u0628\\u064a \\u0648\\u0645\\u0633\\u0627\\u0639\\u062f\\u0627\\u062a"}}}','slug' => 'event-medical-aid-kit','type' => '2','status' => '1','created_at' => '2023-03-02 11:35:39','updated_at' => '2024-03-21 05:15:11'),
            array('id' => '10','name' => 'Volenteer Team','data' => '{"language":{"en":{"name":"Volenteer Team"},"es":{"name":"Equipo de voluntarios"},"ar":{"name":"\\u0627\\u0644\\u0641\\u0631\\u064a\\u0642 \\u0627\\u0644\\u062a\\u0637\\u0648\\u0639\\u064a"}}}','slug' => 'volenteer-team','type' => '2','status' => '1','created_at' => '2023-03-02 11:35:53','updated_at' => '2024-03-21 04:54:11'),
            array('id' => '11','name' => 'Food and Water','data' => '{"language":{"en":{"name":"Food and Water"},"es":{"name":"Comida y agua"},"ar":{"name":"\\u0627\\u0644\\u063a\\u0630\\u0627\\u0621 \\u0648\\u0627\\u0644\\u0645\\u0627\\u0621"}}}','slug' => 'food-and-water','type' => '2','status' => '1','created_at' => '2023-03-02 11:36:06','updated_at' => '2024-03-21 04:53:05'),
            array('id' => '12','name' => 'Help Hoomeless People','data' => '{"language":{"en":{"name":"Help Hoomeless People"},"es":{"name":"Ayudar a las personas sin hogar"},"ar":{"name":"\\u0645\\u0633\\u0627\\u0639\\u062f\\u0629 \\u0627\\u0644\\u0645\\u0634\\u0631\\u062f\\u064a\\u0646"}}}','slug' => 'event-help-hoomeless-people','type' => '2','status' => '1','created_at' => '2023-03-02 11:36:32','updated_at' => '2024-03-21 05:16:42')

        );

        CategoryType::insert($category_types);
    }
}
