<?php

namespace App\Orchid\Screens\Store;


use App\Orchid\Layouts\Store\StoreListLayout;
use Orchid\Screen\Screen;
use App\Models\Store;
use Orchid\Screen\Actions\Link;

class StoreListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'stores' => Store::paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Stores';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            StoreListLayout::class
        ];
    }
}
