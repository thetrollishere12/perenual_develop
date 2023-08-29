<?php

namespace App\Observers;

use App\Models\Species;


use App\Models\SpeciesCareGuide;
use App\Models\SpeciesImage;

use App\Models\Comment\SpeciesComment;
use App\Models\Comment\SpeciesCommentReview;

class SpeciesObserver
{
    /**
     * Handle the Species "created" event.
     *
     * @param  \App\Models\Species  $species
     * @return void
     */
    public function created(Species $species)
    {
        //
    }

    /**
     * Handle the Species "updated" event.
     *
     * @param  \App\Models\Species  $species
     * @return void
     */
    public function updated(Species $species)
    {

        $queries = SpeciesCareGuide::where(function($q) use($species){
            foreach ($species->getOriginal('scientific_name') as $value) {
                $q->orWhere('scientific_name','LIKE','%'.$value.'%');
            }
        })->get();

        foreach($queries as $query){

            foreach ($query->scientific_name as $sn) {

                foreach($species->getOriginal('scientific_name') as $og_sn){

                    if ($sn == $og_sn) {
                        SpeciesCareGuide::where('id',$query->id)->update([
                            'scientific_name' => $species->scientific_name
                        ]);
                    }

                }

            }

        }

        SpeciesComment::where(function($q) use($species){
            foreach ($species->getOriginal('scientific_name') as $value) {
                $q->orWhere('scientific_name','LIKE','%'.$value.'%');
            }
        })->update([
            'scientific_name' => $species->scientific_name
        ]);

        foreach($queries as $query){

            foreach ($query->scientific_name as $sn) {

                foreach($species->getOriginal('scientific_name') as $og_sn){

                    if ($sn == $og_sn) {
                        SpeciesComment::where('id',$query->id)->update([
                            'scientific_name' => $species->scientific_name
                        ]);
                    }

                }

            }

        }

        SpeciesCommentReview::where(function($q) use($species){
            foreach ($species->getOriginal('scientific_name') as $value) {
                $q->orWhere('scientific_name','LIKE','%'.$value.'%');
            }
        })->update([
            'scientific_name' => $species->scientific_name
        ]);

        foreach($queries as $query){

            foreach ($query->scientific_name as $sn) {

                foreach($species->getOriginal('scientific_name') as $og_sn){

                    if ($sn == $og_sn) {
                        SpeciesCommentReview::where('id',$query->id)->update([
                            'scientific_name' => $species->scientific_name
                        ]);
                    }

                }

            }

        }

        SpeciesImage::where(function($q) use($species){
            foreach ($species->getOriginal('scientific_name') as $value) {
                $q->orWhere('scientific_name','LIKE','%'.$value.'%');
            }
        })->update([
            'scientific_name' => $species->scientific_name
        ]);

        foreach($queries as $query){

            foreach ($query->scientific_name as $sn) {

                foreach($species->getOriginal('scientific_name') as $og_sn){

                    if ($sn == $og_sn) {
                        SpeciesImage::where('id',$query->id)->update([
                            'scientific_name' => $species->scientific_name
                        ]);
                    }

                }

            }

        }

    }

    /**
     * Handle the Species "deleted" event.
     *
     * @param  \App\Models\Species  $species
     * @return void
     */
    public function deleted(Species $species)
    {
        //
    }

    /**
     * Handle the Species "restored" event.
     *
     * @param  \App\Models\Species  $species
     * @return void
     */
    public function restored(Species $species)
    {
        //
    }

    /**
     * Handle the Species "force deleted" event.
     *
     * @param  \App\Models\Species  $species
     * @return void
     */
    public function forceDeleted(Species $species)
    {
        //
    }
}
