<?php

namespace App\Orchid\Screens\Species;

use Orchid\Screen\Screen;
use App\Orchid\Layouts\Species\PlantListLayout;
use App\Models\Species;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\Species\PlantListFilterLayout;

class PlantListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'species' => Species::filters(PlantListFilterLayout::class)->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Plant List Screen';
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
                ->route('platform.species.plant.edit')
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
            PlantListFilterLayout::class,
            PlantListLayout::class
        ];
    }
}
