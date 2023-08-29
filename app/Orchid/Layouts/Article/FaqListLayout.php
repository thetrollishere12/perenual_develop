<?php

namespace App\Orchid\Layouts\Article;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

use App\Models\ArticleFaq;
use Orchid\Screen\Actions\Link;
use Storage;

class FaqListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'article_faqs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make('Image')
                ->render(function (ArticleFaq $articlefaq){
                    return '<img style="width:70px;" src="'.Storage::disk('public')->url($articlefaq->image_path.'/medium.jpg').'"/>';                   
                }),
            TD::make('question', 'Question'),
            TD::make('answer', 'Answer')->width('300px'),
            TD::make('Tags')
                ->render(function(ArticleFaq $articlefaq){
                    $output = '';
                    foreach ($articlefaq->tags as $key => $tag) {
                        $output .= '<div style="border-radius: 0.375rem;background-color: rgb(99 102 241);color:white;padding-bottom:4px;padding-top:4px;padding-left:3px;padding-right:3px;margin:2px;">'.$tag.'</div>';
                    }
                    return $output;
                }),
            TD::make('seen', 'Seen'),

            TD::make('helpful', 'Helpful'),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),

            TD::make('edit')
                ->render(function (ArticleFaq $articlefaq) {
                    return Link::make('Edit')
                        ->route('platform.article.faq.edit', $articlefaq);
                }),

        ];
    }
}
