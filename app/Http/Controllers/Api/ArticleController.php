<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleFaqsResource;
use App\Models\Article;
use App\Models\ArticleFaq;


class ArticleController extends Controller
{
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
        $Articles = Article::orderBy('created_at', 'DESC')->paginate(100);
        return ArticleResource::collection($Articles)
            ->additional([
                'message' => 'Species listing',
                'status' => 1,
            ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function faqs(Request $request)
    {
        $Articles = ArticleFaq::orderBy('created_at', 'DESC')->paginate(100);
        return ArticleFaqsResource::collection($Articles)
            ->additional([
                'message' => 'Species listing',
                'status' => 1,
            ]);
    }


}
