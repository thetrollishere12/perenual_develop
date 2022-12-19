<?php

namespace App\Orchid\Screens\Faq;

use Orchid\Screen\Screen;
use App\Orchid\Layouts\Faq\FaqListLayout;
use App\Models\Faq;
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
            'faqs' => Faq::paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Frequently Asked Questions';
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
                ->route('platform.faq.edit')
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
