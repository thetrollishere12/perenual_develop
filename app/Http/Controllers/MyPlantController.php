<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyPlant;
use App\Models\Species;
use App\Models\Product;
use App\Models\SpeciesCareGuide;
use App\Models\SpeciesArticleSection;
use App\Models\ArticleFaq;
use App\Models\Article;
use App\Models\SpeciesIssue;



class MyPlantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        

        return view('profile.user.my-plants.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('profile.user.my-plants.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MyPlant  $myPlant
     * @return \Illuminate\Http\Response
     */
    public function show(MyPlant $myPlant)
    {

        $queries = Species::where('scientific_name','LIKE','%'.$myPlant->species.'%')->first();

        $product = Product::where('name','LIKE','%'.$queries->common_name.'%')->take(6)->get();

        product_details($product);
        
        $queries->products = $product;

        $queries->care_guide = SpeciesCareGuide::where('common_name',$queries->common_name)->first();

        $queries->article = SpeciesArticleSection::where('common_name',$queries->common_name)->get();

        $queries->faq = ArticleFaq::where('tags','like','%'.$queries->common_name.'%')->limit(8)->get();

        $queries->related = Species::where('family','LIKE','%'.$queries->family.'%')->orWhere('type','LIKE','%'.$queries->type.'%')->inRandomOrder()->limit(6)->get();

        $queries->related_articles = Article::where('title','LIKE','%'.$queries->common_name.'%')->where('description','LIKE','%'.$queries->common_name.'%')->orWhere('description','LIKE','%'.$queries->common_name.'%')->inRandomOrder()->limit(6)->get();

        if($queries->pest_susceptibility){
            foreach($queries->pest_susceptibility as $pest){
                $issue[] = SpeciesIssue::where('common_name',trim($pest))->first();
            }

            $queries->pest_susceptibility = $issue;

        }

        return view('profile.user.my-plants.show',['plant'=>$myPlant,'queries'=>$queries]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MyPlant  $myPlant
     * @return \Illuminate\Http\Response
     */
    public function edit(MyPlant $myPlant)
    {
        return view('profile.user.my-plants.edit',['plant'=>$myPlant]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MyPlant  $myPlant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MyPlant $myPlant)
    {
        //
    }

}
