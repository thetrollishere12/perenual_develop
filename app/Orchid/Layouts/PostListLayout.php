<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Article;

class PostListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'articles';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title')
                ->render(function (Article $article) {
                    return Link::make($article->title)
                        ->route('platform.article.edit', $article);
                }),
            TD::make('description', 'Description'),
            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];

    }
}
