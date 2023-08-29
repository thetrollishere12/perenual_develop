<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductDimension;
use App\Models\ProductElement;
use App\Models\ProductReview;

use Storage;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        // ProductDetail::where('product_id',$product->id)->delete();
        // ProductDimension::where('product_id',$product->id)->delete();
        // ProductElement::where('product_id',$product->id)->delete();
        // Storage::disk('public')->deleteDirectory('marketplace/'.$product->id);
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
