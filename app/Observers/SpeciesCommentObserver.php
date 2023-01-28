<?php

namespace App\Observers;

use App\Models\Comment\SpeciesComment;

class SpeciesCommentObserver
{
    /**
     * Handle the SpeciesComment "created" event.
     *
     * @param  \App\Models\Comment\SpeciesComment  $speciesComment
     * @return void
     */
    public function created(SpeciesComment $speciesComment)
    {
        //
    }

    /**
     * Handle the SpeciesComment "updated" event.
     *
     * @param  \App\Models\Comment\SpeciesComment  $speciesComment
     * @return void
     */
    public function updated(SpeciesComment $speciesComment)
    {
        //
    }

    /**
     * Handle the SpeciesComment "deleted" event.
     *
     * @param  \App\Models\Comment\SpeciesComment  $speciesComment
     * @return void
     */
    public function deleted(SpeciesComment $speciesComment)
    {

        $child=SpeciesComment::where('parent_id',$speciesComment->id)->get();

        foreach($child as $row){
            $row->delete();
        }
        
    }

    /**
     * Handle the SpeciesComment "restored" event.
     *
     * @param  \App\Models\Comment\SpeciesComment  $speciesComment
     * @return void
     */
    public function restored(SpeciesComment $speciesComment)
    {
        //
    }

    /**
     * Handle the SpeciesComment "force deleted" event.
     *
     * @param  \App\Models\Comment\SpeciesComment  $speciesComment
     * @return void
     */
    public function forceDeleted(SpeciesComment $speciesComment)
    {

    }

}
