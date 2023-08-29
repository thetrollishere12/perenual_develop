<?php

namespace App\Http\Livewire\Profile\Shop;

use Livewire\Component;
use Illuminate\Support\Str;
use Auth;

class ImportEtsyProduct extends Component
{

    public $state;
    public $codeChallenge;
    public $etsy_account;
    public $etsy_page = 1;
    public $pagination;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function page_b(){
        $this->etsy_page--;
        $this->emit('refreshComponent');
    }

    public function page_n(){
        $this->etsy_page++;
        $this->emit('refreshComponent');
    }

    public function render()
    {

        $type = 'Etsy';

        $limit = 25;

        $page = $this->etsy_page*$limit;

        $this->etsy_account = Auth::user()->connected_etsy()->first();

        $active = [];

        if ($this->etsy_account) {
        
            $new_bearer_token = etsy_token_refresh($this->etsy_account->shop_id);

            $active = etsy_get_listings_by_shop($new_bearer_token,$this->etsy_account->shop_id,'active',$limit,($this->etsy_page-1)*$limit,"created","desc");

            foreach ($active->results as $i => $listing) {
                
                try{

                    $response_img = etsy_get_listings_image_by_id($this->etsy_account->shop_id,$listing->listing_id);

                    $active->results[$i]->image = $response_img->results[0]->url_570xN;

                }catch(\Exception $e){
                    continue;
                }

            }

            $this->pagination = [
                'current_page'=>$this->etsy_page,
                'total_page' => ceil($active->count/$limit)
            ];

        }

        return view('livewire.profile.shop.import-etsy-product',['active'=>$active]);
    }
}
