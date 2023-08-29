<?php

namespace App\Orchid\Layouts\Species;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use App\Models\Species;
use Storage;
use Orchid\Screen\Fields\Input;

class PlantListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'species';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [

            TD::make('id', 'ID')->filter(Input::make()),
            TD::make('Image')
                ->render(function (Species $species){
                    return '<img style="width:50px;" src="'.Storage::disk('public')->url('species_image/'.$species->folder.'/thumbnail/'.$species->default_image).'"/>';                   
                }),
            TD::make('common_name', 'Common Name')->filter(Input::make())->width('300px'),
            TD::make('scientific_name', 'Scientific Name')
                ->filter(Input::make())
                ->render(function (Species $species) {
                    if ($species->scientific_name) {
                        return implode(",",$species->scientific_name);
                    }
                }),
            TD::make('other_name', 'Other Name')
                ->filter(Input::make())
                ->render(function (Species $species) {
                    if ($species->other_name) {
                        return implode(",",$species->other_name);
                    }
                }),
            TD::make('seen', 'Seen'),
            TD::make('helpful', 'Helpful'),
            TD::make('edit')
                ->render(function (Species $species) {
                    return Link::make('Edit')
                        ->route('platform.species.plant.edit', $species);
                }),

        ];
    }
}
