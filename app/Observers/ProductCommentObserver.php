<?php

namespace App\Observers;

use App\Models\ProductComment;

class ProductCommentObserver
{
    /**
     * Handle the ProductComment "created" event.
     *
     * @param  \App\Models\ProductComment  $productComment
     * @return void
     */
    public function created(ProductComment $productComment)
    {
    }

    /**
     * Handle the ProductComment "updated" event.
     *
     * @param  \App\Models\ProductComment  $productComment
     * @return void
     */
    public function updated(ProductComment $productComment)
    {
        //
    }

    /**
     * Handle the ProductComment "deleted" event.
     *
     * @param  \App\Models\ProductComment  $productComment
     * @return void
     */
    public function deleted(ProductComment $productComment)
    {
       
    }

    /**
     * Handle the ProductComment "restored" event.
     *
     * @param  \App\Models\ProductComment  $productComment
     * @return void
     */
    public function restored(ProductComment $productComment)
    {
        //
    }

    /**
     * Handle the ProductComment "force deleted" event.
     *
     * @param  \App\Models\ProductComment  $productComment
     * @return void
     */
    public function forceDeleted(ProductComment $productComment)
    {
        //
    }

    public function deleting(ProductComment $productComment){
        // finding child
        $child=ProductComment::where('parent_id',$productComment->id)->get();
        foreach($child as $row){
            $row->delete();
        }
    }
}
