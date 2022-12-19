<?php

namespace App\Orchid\Screens\Faq;

use App\Models\Faq;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Picture;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class FaqEditScreen extends Screen
{

    /**
     * @var faq
     */
    public $faq;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Faq $faq): array
    {
        return [
            'faq' => $faq
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->faq->exists ? 'Edit Frequently Asked Questions' : 'Creating A New Frequently Asked Questions';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create faq')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->faq->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->faq->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->faq->exists),
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
                TextArea::make('faq.question')
                    ->title('Question')
                    ->rows(3)
                    ->maxlength(500)
                    ->placeholder('Brief question'),

                Quill::make('faq.answer')
                    ->title('Answer')
                    ->rows(3)
                    ->maxlength(500)
                    ->placeholder('Brief answer'),

            ])

        ];
    }


    public function createOrUpdate(Faq $faq, Request $request)
    {
        $faq->fill($request->get('faq'))->save();

        Alert::info('You have successfully created a faq.');

        return redirect()->route('platform.faq.list');
    }

    /**
     * @param Faq $faq
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Faq $faq)
    {
        $faq->delete();

        Alert::info('You have successfully deleted the faq.');

        return redirect()->route('platform.faq.list');
    }


}
