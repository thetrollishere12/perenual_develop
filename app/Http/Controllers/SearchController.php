<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Species;
use App\Models\ArticleFaq;
use App\Models\Article;
use App\Models\SpeciesArticleSection;
use App\Models\SpeciesIssue;
use App\Models\SpeciesCareGuide;
use App\Models\Product;
use App\Models\PropagationMethod;

class SearchController extends Controller
{
    
    public function article_index(){
        return view('search.article.index');
    }

    public function article_show($id){

        $queries = Article::find($id);

        if (!$queries) {
            return redirect('article');
        }

        $queries->childs = $queries->childs()->get();

        $queries->seen();

        $product = Product::where('name','LIKE','%'.'2'.'%')->take(6)->get();

        product_details($product);
        
        $queries->products = $product;

        $queries->faq = ArticleFaq::where('tags','like','%'.$queries->tags[0].'%')->limit(8)->get();

        $queries->related = Species::inRandomOrder()->limit(6)->get();

        $queries->related_articles = Article::where('title','LIKE','%'.$queries->tags[0].'%')->where('description','LIKE','%'.$queries->tags[0].'%')->orWhere('description','LIKE','%'.$queries->tags[0].'%')->inRandomOrder()->limit(6)->get();

        return view('search.article.show',['queries'=>$queries]);

    }

    public function species_index(){
        return view('search.species.index');
    }

    public function species_show($id){

        $species = Species::find($id);

        if (!$species) {
            return redirect('plant-species-database-search-finder');
        }

        $species->seen();

        // Load related products
        $species->products = Product::where('name','LIKE','%'.$species->common_name.'%')->take(6)->get();
        product_details($species->products);

        // Load care guide
        $species->care_guide = SpeciesCareGuide::where('species_id',$species->id)->first();
        if ($species->care_guide) {
            $species->care_guide = $species->care_guide->section(null)->get()->unique('type');
        }

        // Load article, faq and related articles
        $species->article = SpeciesArticleSection::where('common_name',$species->common_name)->get();
        $species->faq = ArticleFaq::where('tags','like','%'.$species->common_name.'%')->limit(8)->get();

        $species->related_articles = Article::where('title','LIKE','%'.$species->common_name.'%')
            ->where('description','LIKE','%'.$species->common_name.'%')
            ->orWhere('description','LIKE','%'.$species->common_name.'%')
            ->inRandomOrder()->limit(6)->get();

        // Load related species
        $species->related = Species::where('family', 'LIKE', '%'.$species->family.'%')
            ->orWhere('type', 'LIKE', '%'.$species->type.'%')
            ->inRandomOrder()->limit(6)
            ->get();

        // Handle pest susceptibility
        if($species->pest_susceptibility){
            $species->pest_susceptibility = array_map(function ($pest) {
                return SpeciesIssue::where('common_name',trim($pest))->first();
            }, $species->pest_susceptibility);
        }

        return view('search.species.show', ['queries' => $species]);

    }

    public  function disease_index(){
        return view('search.disease.index');
    }


    public function disease_show($id){
        
        $queries = SpeciesIssue::find($id);

        if (!$queries) {
            return redirect('pest-disease-search-finder');
        }

        $queries->seen();

        $product = Product::where('name','LIKE','%'.'2'.'%')->take(6)->get();

        product_details($product);
        
        $queries->species_susceptibility = Species::where('pest_susceptibility','LIKE','%'.$queries->common_name.'%')
        ->orWhere(function($q) use($queries){

            foreach ($queries->host as $host) {
                $q->orWhere('common_name','LIKE','%'.$host.'%')
                ->orWhere('other_name','LIKE','%'.$host.'%')
                ->orWhere('type','LIKE','%'.$host.'%');
            }

        })
        ->get();

        $queries->products = $product;

        $queries->care_guide = SpeciesCareGuide::where('common_name',$queries->name)->first();

        $queries->article = SpeciesArticleSection::where('common_name',$queries->name)->get();

        $queries->faq = ArticleFaq::where('tags','like','%'.$queries->name.'%')->limit(8)->get();

        $queries->related = SpeciesIssue::inRandomOrder()->limit(6)->get();

        $queries->related_articles = Article::where('title','LIKE','%'.$queries->name.'%')->where('description','LIKE','%'.$queries->name.'%')->orWhere('description','LIKE','%'.$queries->name.'%')->inRandomOrder()->limit(6)->get();

        return view('search.disease.show',['queries'=>$queries]);

    }

    public  function guide_index(){
        return view('search.guide.index');
    }

    public function guide_show($id){

        $guide = SpeciesCareGuide::find($id);

        if (!$guide) {
            return redirect('plant-database-search-guide');
        }

        $guide->seen();

        $queries = Species::where(function($q) use($guide){
            foreach ($guide->scientific_name as $value) {
                $q->orWhere('scientific_name','LIKE','%'.$value.'%');
            }
        })->first();

        $product = Product::where('name','LIKE','%'.'2'.'%')->take(6)->get();

        product_details($product);
        
        $queries->products = $product;

        $queries->care_guide = $guide->section(null)->get()->unique('type');

        $queries->article = SpeciesArticleSection::where('common_name',$queries->common_name)->get();

        $queries->faq = ArticleFaq::where('tags','like','%'.$queries->common_name.'%')->limit(8)->get();

        $queries->related = Species::where('family','LIKE','%'.$queries->family.'%')->orWhere('type','LIKE','%'.$queries->type.'%')->inRandomOrder()->limit(6)->get();

        $queries->related_articles = Article::where('title','LIKE','%'.$queries->common_name.'%')->where('description','LIKE','%'.$queries->common_name.'%')->orWhere('description','LIKE','%'.$queries->common_name.'%')->inRandomOrder()->limit(6)->get();

        if ($queries->pest_susceptibility) {
            
        foreach($queries->pest_susceptibility as $pest){
            $issue[] = SpeciesIssue::where('common_name',trim($pest))->first();
        }

        $queries->pest_susceptibility = $issue;

        }

        return view('search.guide.show',['queries'=>$queries]);

    }


    public  function faq_index(){
        return view('search.faq.index');
    }


    public function faq_show($id){

        $queries = ArticleFaq::find($id);

        if (!$queries) {
            return redirect('article-faq-finder');
        }

        $queries->seen();

        $product = Product::where('name','LIKE','%'.'2'.'%')->take(6)->get();

        product_details($product);
        
        $queries->products = $product;

        // $queries->species = Species::where('common_name',$queries->common_name)->first();

        // $queries->article = SpeciesArticleSection::where('common_name',$queries->common_name)->get();

        $queries->faq = ArticleFaq::where('tags','like','%'.$queries->common_name.'%')->limit(8)->get();

        $queries->related = Species::inRandomOrder()->limit(6)->get();

        $queries->related_articles = Article::where('title','LIKE','%'.$queries->common_name.'%')->where('description','LIKE','%'.$queries->common_name.'%')->orWhere('description','LIKE','%'.$queries->common_name.'%')->inRandomOrder()->limit(6)->get();

        return view('search.faq.show',['queries'=>$queries]);

    }

    public  function propagation_index(){
        return view('search.propagation.index');
    }

    public function propagation_show($id){

        $queries = PropagationMethod::find($id);

        if (!$queries) {
            return redirect('plant-propagation-methods');
        }

        $queries->seen();

        $product = Product::where('name','LIKE','%'.'2'.'%')->take(6)->get();

        product_details($product);
        
        $queries->products = $product;

        

        $queries->species_valid = Species::where('propagation','LIKE','%'.$queries->name.'%')
        ->limit(12)->get();


        $queries->faq = PropagationMethod::where('tags','like','%'.$queries->name.'%')->limit(8)->get();

        $queries->related = PropagationMethod::inRandomOrder()->limit(6)->get();

        $queries->related_articles = Article::where('title','LIKE','%'.$queries->name.'%')->where('description','LIKE','%'.$queries->name.'%')->orWhere('description','LIKE','%'.$queries->name.'%')->inRandomOrder()->limit(6)->get();

        return view('search.propagation.show',['queries'=>$queries]);

    }

}
