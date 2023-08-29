<?php

namespace App\Observers;

use App\Models\Store;
use App\Models\BankBalance;

class StoreObserver
{
    /**
     * Handle the Store "created" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function created(Store $store)
    {
        // BankBalance::create([
        //     'store_id'=> $store->id,
        //     'type'=> 'created',
        //     'currency'=> $store->currency
        // ]);
    }

    /**
     * Handle the Store "updated" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function updated(Store $store)
    {
        //
    }

    /**
     * Handle the Store "deleted" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function deleted(Store $store)
    {
        //
    }

    /**
     * Handle the Store "restored" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function restored(Store $store)
    {
        //
    }

    /**
     * Handle the Store "force deleted" event.
     *
     * @param  \App\Models\Store  $store
     * @return void
     */
    public function forceDeleted(Store $store)
    {
        //
    }
}
