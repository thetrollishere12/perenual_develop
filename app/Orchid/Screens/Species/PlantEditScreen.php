<?php

namespace App\Orchid\Screens\Species;

use App\Models\Species;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlantEditScreen extends Screen
{

    public $species;


    /**
     * Query data.
     *
     * @return array
     */
    public function query(Species $species): iterable
    {
        return [
            'species' => $species
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Plant Edit Screen';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create species')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->species->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->species->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->species->exists),
        
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {

        $rows = [
            Input::make('species.common_name')
                ->title('Common Name')
                ->placeholder('Common Name'),
        ];

        if ($this->species->scientific_name) {
            foreach($this->species->scientific_name as $key => $scientific_name) {
                if ($key == 0) {
                    $rows[] = Input::make('species.scientific_name.'.$key)
                    ->title('Scientific Name')
                    ->placeholder('Scientific Name');
                }else{
                    $rows[] = Input::make('species.scientific_name.'.$key);
                }
            }
        }

        if ($this->species->other_name) {
            foreach($this->species->other_name as $key => $other_name) {
                if ($key == 0) {
                    $rows[] = Input::make('species.other_name.'.$key)
                    ->title('Other Name')
                    ->placeholder('Other Name');
                }else{
                    $rows[] = Input::make('species.other_name.'.$key);
                }
                
            }
        }

        $rows[] = Input::make('species.family')
                ->title('Family')
                ->placeholder('Family Name');

        $rows[] = TextArea::make('species.description')
                ->title('Description')
                ->placeholder('Plant Description')
                ->rows(6);

        $rows[] = Input::make('species.type')
            ->title('Type')
            ->placeholder('Type');

        $rows[] = CheckBox::make('species.cones')
        ->title('Cones');
        
        $rows[] = CheckBox::make('species.flowers')
        ->title('Flower');

        $rows[] = CheckBox::make('species.fruits')
        ->title('Fruits');

        $rows[] = CheckBox::make('species.thorny')
        ->title('Thorny');

        $rows[] = CheckBox::make('species.invasive')
        ->title('Invasive');

        $rows[] = CheckBox::make('species.rare')
        ->title('Rare');

        $rows[] = CheckBox::make('species.tropical')
        ->title('Tropical');

        $rows[] = CheckBox::make('species.indoor')
        ->title('Indoor');

        if ($this->species->sunlight) {
            foreach($this->species->sunlight as $key => $sunlight) {
                if ($key == 0) {
                    $rows[] = Input::make('species.sunlight.'.$key)
                    ->title('Sunlight')
                    ->placeholder('Sunlight');
                }else{
                    $rows[] = Input::make('species.sunlight.'.$key);
                }
            }
        }












        return [
            Layout::rows($rows)
        ];
    }





    public function createOrUpdate(Species $species, Request $request)
    {
        $species->fill($request->get('species'))->save();

        Alert::info('You have successfully created a species.');

        return redirect()->route('platform.species.plant.list');
    }

    /**
     * @param Faq $faq
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Species $species)
    {
        $species->delete();

        Alert::info('You have successfully deleted the species.');

        return redirect()->route('platform.species.plant.list');
    }





}
