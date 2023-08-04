<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'type' => 'App\\Models\\FrontPage',
                'categoryable_id' => 12,
                'categoryable_type' => 'App\\Models\\Locations\\Division',
                'created_at' => '2023-06-02 16:15:10',
                'updated_at' => '2023-06-02 16:15:10',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'type' => 'App\\Models\\News',
                'categoryable_id' => 305,
                'categoryable_type' => 'App\\Models\\Locations\\City',
                'created_at' => '2023-06-02 16:15:10',
                'updated_at' => '2023-06-02 16:15:10',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'type' => 'App\\Models\\SystemAnnouncement',
                'categoryable_id' => 3,
                'categoryable_type' => 'App\\Models\\Post',
                'created_at' => '2023-06-02 16:20:59',
                'updated_at' => '2023-06-02 16:20:59',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'type' => 'App\\Models\\Meeting',
                'categoryable_id' => 305,
                'categoryable_type' => 'App\\Models\\Locations\\City',
                'created_at' => '2023-07-04 09:00:02',
                'updated_at' => '2023-07-04 09:00:02',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'type' => 'App\\Models\\Meeting',
                'categoryable_id' => 13,
                'categoryable_type' => 'App\\Models\\Locations\\Division',
                'created_at' => '2023-08-04 14:00:54',
                'updated_at' => '2023-08-04 14:00:54',
                'deleted_at' => '2023-08-04 14:00:54',
            ),
            5 => 
            array (
                'id' => 6,
                'type' => 'App\\Models\\Meeting',
                'categoryable_id' => 3,
                'categoryable_type' => 'App\\Models\\Locations\\Country',
                'created_at' => '2023-08-04 14:38:11',
                'updated_at' => '2023-08-04 14:38:11',
                'deleted_at' => '2023-08-04 14:38:11',
            ),
            6 => 
            array (
                'id' => 7,
                'type' => 'App\\Models\\News',
                'categoryable_id' => 13,
                'categoryable_type' => 'App\\Models\\Locations\\Division',
                'created_at' => '2023-08-04 16:18:51',
                'updated_at' => '2023-08-04 16:18:51',
                'deleted_at' => '2023-08-04 16:18:51',
            ),
            7 => 
            array (
                'id' => 8,
                'type' => 'App\\Models\\News',
                'categoryable_id' => 3,
                'categoryable_type' => 'App\\Models\\Locations\\Country',
                'created_at' => '2023-08-04 16:18:51',
                'updated_at' => '2023-08-04 16:18:51',
                'deleted_at' => '2023-08-04 16:18:51',
            ),
        ));
        
        
    }
}