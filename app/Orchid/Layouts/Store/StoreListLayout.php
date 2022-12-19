<?php

namespace App\Orchid\Layouts\Store;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Store;
use Storage;

class StoreListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'stores';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('user_id', 'User ID')
                ->render(function (Store $store){
                    if ($store->profile_photo_path) {
                        return '<img width="64px" height="64px" src="'.Storage::disk('public')->url('shop-profile-photos/'.$store->id.'/'.$store->profile_photo_path).'"/>';
                    }else{
                        return '<img class="h-8 w-8 my-1 pr-1 object-cover" src="https://ui-avatars.com/api/?name='.mb_substr($store->name, 0, 1).'&color=7F9CF5&background=EBF4FF"/>';
                    }
                    
                }),
            TD::make('name', 'Name')
                ->render(function (Store $store) {
                    return Link::make($store->name)
                        ->route('platform.shop.edit', $store);
                }),
            TD::make('currency', 'Currency'),
            TD::make('country', 'Country'),
            TD::make('local_pickup', 'Pickup')
                ->render(function(Store $store){
                    if($store->local_pickup){
                        return 'True';
                    }
                }),
            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
