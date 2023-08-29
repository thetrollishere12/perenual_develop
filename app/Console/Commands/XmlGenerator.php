<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Store;

use App\Models\Species;
use App\Models\ArticleFaq;
use App\Models\SpeciesArticleSection;
use App\Models\SpeciesIssue;
use App\Models\SpeciesCareGuide;



class XmlGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:xmlGenerator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Xml for website';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $db = Product::all();

        $sitemap = Sitemap::create();

        foreach ($db as $product) {
            $sitemap->add(Url::create('/marketplace/product/'.$product->sku)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.7));
        }

        $sitemap->writeToDisk('public', 'sitemap/products-v2.xml');



        $db = Store::all();

        $sitemap = Sitemap::create();

        foreach ($db as $query) {
            $sitemap->add(Url::create('/shop/profile/'.$query->id.'/'.$query->name)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.7));
        }

        $sitemap->writeToDisk('public', 'sitemap/stores-v2.xml');




        $db = Species::all();

        $sitemap = Sitemap::create();

        foreach ($db as $query) {
            $sitemap->add(Url::create('/plant-species-database-search-finder/species/'.$query->id)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.7));
        }

        $sitemap->writeToDisk('public', 'sitemap/species-v2.xml');





        $db = SpeciesCareGuide::all();

        $sitemap = Sitemap::create();


        foreach ($db as $query) {
            $sitemap->add(Url::create('/plant-species-database-search-finder/species/'.$query->id.'/guide')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.7));
        }

        $sitemap->writeToDisk('public', 'sitemap/species_guide.xml');





        // $db = ArticleFaq::all();

        // $sitemap = Sitemap::create();

        // foreach ($db as $query) {
        //     $sitemap->add(Url::create('/article-faq-finder/question/'.$query->id)
        //     ->setLastModificationDate(Carbon::yesterday())
        //     ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        //     ->setPriority(0.7));
        // }

        // $sitemap->writeToDisk('public', 'sitemap/article_faq.xml');



        $db = SpeciesIssue::all();

        $sitemap = Sitemap::create();

        foreach ($db as $query) {
            $sitemap->add(Url::create('/pest-disease-search-finder/pest-disease/'.$query->id)
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.7));
        }

        $sitemap->writeToDisk('public', 'sitemap/pest_disease.xml');




























    }
}