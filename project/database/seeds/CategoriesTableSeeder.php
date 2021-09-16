<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Html', 'Css', 'Php', 'Javascript'];

        foreach ($categories as $category) {
            // istanza
            $newCategory = new Category();

            // dati
            $newCategory->name = $category;
            $newCategory->slug = Str::slug($category, '-', 'Vue CLi');
            
            // salvare
            $newCategory->save();
        }

    }
}
