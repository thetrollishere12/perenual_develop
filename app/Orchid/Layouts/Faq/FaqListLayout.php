<?php

namespace App\Orchid\Layouts\Faq;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use App\Models\Faq;

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
    protected $target = 'faqs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make('question', 'Question'),
            TD::make('answer', 'Answer'),
            TD::make('seen', 'Seen'),

            TD::make('helpful', 'Helpful'),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),

            TD::make('edit')
                ->render(function (Faq $faq) {
                    return Link::make('Edit')
                        ->route('platform.faq.edit', $faq);
                }),

        ];
    }
}
