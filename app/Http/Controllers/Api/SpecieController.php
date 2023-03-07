<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SpecieResource;
use App\Http\Resources\SpecieCareGuideResource;
use App\Http\Resources\SpecieArticleSectionResource;
use App\Http\Resources\SpecieCommentResource;
use App\Http\Resources\SpecieCommentReviewResource;
use App\Models\Species;
use App\Models\SpeciesArticleSection;
use App\Models\SpeciesCareGuide;
use App\Models\SpeciesComment;
use App\Models\SpeciesCommentReview;

class SpecieController extends Controller
{
    /**
     * Create a new Specie instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Species = Species::orderBy('created_at', 'DESC')->paginate(100);
        return SpecieResource::collection($Species)
            ->additional([
                'message' => 'Species listing',
                'status' => 1,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {

        $Specie = Species::where('id', $id)->get();

        return SpecieResource::collection($Specie)
            ->additional([
                'message' => 'Specie detail!',
                'status' => 1,
            ]);

        return response("Specie detail!", 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function articleSection($id)
    {
        $specieArticle = SpeciesArticleSection::where('id', $id)->get();
        return SpecieArticleSectionResource::collection($specieArticle)
            ->additional([
                'message' => 'Specie article section detail!',
                'status' => 1,
            ]);

        return response("Specie article section", 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function careGuide($id)
    {
        $careGuide = SpeciesCareGuide::where('id', $id)->get();
        return SpecieCareGuideResource::collection($careGuide)
            ->additional([
                'message' => 'specie care guide detail!',
                'status' => 1,
            ]);

        return response("specie care guide", 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comment($id)
    {
        $specieComment = SpeciesComment::where('id', $id)->get();
        return SpecieCommentResource::collection($specieComment)
            ->additional([
                'message' => 'Specie comment!',
                'status' => 1,
            ]);

        return response("Specie comment", 404);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function commentReview($id)
    {
        $specieComment = SpeciesCommentReview::where('id', $id)->get();
        return SpecieCommentReviewResource::collection($specieComment)
            ->additional([
                'message' => 'Comment review!',
                'status' => 1,
            ]);

        return response("Specie comment", 404);
    }
}
