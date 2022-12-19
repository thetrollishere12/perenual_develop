<?php

namespace App\Orchid\Screens;

use App\Models\Article;
use App\Models\User;
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

class PostEditScreen extends Screen
{

    /**
     * @var Article
     */
    public $article;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Article $article): array
    {
        return [
            'article' => $article
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->article->exists ? 'Edit article' : 'Creating a new article';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create article')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->article->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->article->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->article->exists),
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
                Input::make('article.title')
                    ->title('Title')
                    ->placeholder('Attractive but mysterious title')
                    ->help('Specify a short descriptive title for this article.'),

                TextArea::make('article.description')
                    ->title('Description')
                    ->rows(3)
                    ->maxlength(200)
                    ->placeholder('Brief description for preview'),

                // Relation::make('article.title')
                //     ->title('Author')
                //     ->fromModel(User::class, 'name'),

                Picture::make('image')
                    ->storage('public'),

                // Quill::make('article.description')
                //     ->title('Main text'),

            ])
        ];
    }

    /**
     * @param article    $article
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Article $article, Request $request)
    {
        $article->fill($request->get('article'))->save();

        Alert::info('You have successfully created a article.');

        return redirect()->route('platform.article.list');
    }

    /**
     * @param Article $article
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Article $article)
    {
        $article->delete();

        Alert::info('You have successfully deleted the article.');

        return redirect()->route('platform.article.list');
    }

}
