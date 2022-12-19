<?php

namespace App\Orchid\Layouts\Product;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Product;
use Storage;

class ProductListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            
            

            TD::make('store_id', 'Store ID'),
            TD::make('Image')
                ->render(function (Product $product){
                    return '<img style="width:50px;" src="'.Storage::disk('public')->url($product->image.$product->default_image).'"/>';                   
                }),
            TD::make('sku', 'SKU'),
            TD::make('category', 'Category')
                ->render(function (Product $product) {
                    return str_replace('<span class="px-2 text-sm icon-play3"></span>'," ⯈ ",$product->category); 
                }),
            TD::make('name', 'Name')
                ->render(function (Product $product) {
                    return Link::make($product->name)
                        ->route('platform.product.edit', $product);
                }),
            TD::make('currency', 'Currency'),
            TD::make('price', 'Price'),
            TD::make('shippingMethod', 'Shipping ID'),

            TD::make('quantity', 'Quantity'),

            TD::make('created_at', 'Created'),
            TD::make('updated_at', 'Last edit'),
        ];
    }
}
