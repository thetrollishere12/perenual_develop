<?php

namespace App\Orchid\Screens\Article;

use Orchid\Screen\Screen;

use App\Orchid\Layouts\Article\FaqListLayout;
use App\Models\ArticleFaq;
use Orchid\Screen\Actions\Link;

class FaqListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'article_faqs' => ArticleFaq::paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Article Frequently Asked Questions';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
             Link::make('Create new')
                ->icon('pencil')
                ->route('platform.article.faq.edit')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            FaqListLayout::class
        ];
    }
}
