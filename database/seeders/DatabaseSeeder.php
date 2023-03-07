<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Faq::factory(100)->create();
        // \App\Models\Category::factory(100)->create();
        // \App\Models\Subcategory::factory(100)->create();
        // \App\Models\Subset::factory(100)->create();

        // \App\Models\Product::factory(100)->create();
        // \App\Models\ProductDetail::factory(30)->create();
        // \App\Models\ProductElement::factory(100)->create();

        // \App\Models\ArticleFaq::factory(100)->create();
        // \App\Models\Article::factory(100)->create();

        \App\Models\SpeciesArticleSection::factory(100)->create();

    }
}
