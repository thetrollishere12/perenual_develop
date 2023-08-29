<?php

namespace App\Orchid\Screens\Article;

use Orchid\Screen\Screen;
use App\Models\ArticleFaq;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Actions\Button;

use Orchid\Support\Facades\Layout;

class FaqEditScreen extends Screen
{

    public $articlefaq;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(ArticleFaq $articlefaq): iterable
    {
        return [
            'articlefaq' => $articlefaq
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->articlefaq->exists ? 'Edit Article Frequently Asked Questions' : 'Creating A New Article Frequently Asked Questions';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create articlefaq')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->articlefaq->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->articlefaq->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->articlefaq->exists),
        
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
            Layout::rows([
                TextArea::make('articlefaq.question')
                    ->title('Question')
                    ->rows(3)
                    ->maxlength(500)
                    ->placeholder('Brief question'),

                Quill::make('articlefaq.answer')
                    ->title('Answer')
                    ->rows(3)
                    ->maxlength(500)
                    ->placeholder('Brief answer'),

            ])
        ];
    }



    public function createOrUpdate(ArticleFaq $articlefaq, Request $request)
    {
        $articlefaq->fill($request->get('articlefaq'))->save();

        Alert::info('You have successfully created a faq.');

        return redirect()->route('platform.article.faq.list');
    }

    /**
     * @param Faq $faq
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(ArticleFaq $articlefaq)
    {
        $articlefaq->delete();

        Alert::info('You have successfully deleted the faq.');

        return redirect()->route('platform.article.faq.list');
    }



}
