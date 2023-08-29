<?php

namespace App\Http\Livewire\Admin\Merchant\Importer\Etsy;

use Livewire\Component;
use App\Models\User;
use App\Models\ShippingDomestic;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductElement;
use Intervention\Image\Facades\Image;
use App\Models\ProductDimension;
use Storage;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subset;

use WireUi\Traits\Actions;

class Products extends Component
{

    use Actions;

    public $user_id;
    public $etsy_account;
    public $etsy_page = 1;
    public $pagination;
    public $add;
    public $category;

    public $image_size = 1300;
    public $thumbnail_size = 500;

    public $categories;

    public function page_b(){
        $this->etsy_page--;
        $this->emit('refreshComponent');
    }

    public function page_n(){
        $this->etsy_page++;
        $this->emit('refreshComponent');
    }
    
    public function mount(){

            $this->categories = Category::leftJoin('subcategories','categories.id','=','subcategories.category_id')
            ->leftJoin('subsets','subcategories.id','=','subsets.subcategory_id')
            ->select('categories.*','subcategories.id as subCategoryId','subcategories.name as SubcategoryName','subsets.id as SubsetId','subsets.name as SubsetName')
            ->orderBy('categories.name')
            ->orderBy('subcategories.name')
            ->orderBy('subsets.name')
            ->get();    

            foreach($this->categories as $key => $category){

                $category->category = $category->name ? $category->category = $category->name : $category->category;
                $category->category = $category->SubcategoryName ? $category->category .= '<span class="px-2 text-sm icon-play3"></span>'.$category->SubcategoryName : $category->category;
                $category->category = $category->SubsetName ? $category->category .= '<span class="px-2 text-sm icon-play3"></span>'.$category->SubsetName : $category->category;

            }


    }

    public function render()
    {

        $limit = 25;

        $page = $this->etsy_page*$limit;

        $this->etsy_account = User::findOrFail($this->user_id)->connected_etsy()->first();

        $active = [];

        if ($this->etsy_account) {
        
            $new_bearer_token = etsy_token_refresh($this->etsy_account->shop_id);

            $active = etsy_get_listings_by_shop($new_bearer_token,$this->etsy_account->shop_id,'active',$limit,($this->etsy_page-1)*$limit,"created","desc");

            foreach ($active->results as $i => $listing) {
                
                if (Product::where('attributes','LIKE','%'.$listing->listing_id.'%')->count() > 0) {
                    
                    unset($active->results[$i]);

                }else{

                    try{

                        $response_img = etsy_get_listings_image_by_id($this->etsy_account->shop_id,$listing->listing_id);

                        $active->results[$i]->image = $response_img->results[0]->url_570xN;

                    }catch(\Exception $e){
                        continue;
                    }

                }

            }

            $this->pagination = [
                'current_page'=>$this->etsy_page,
                'total_page' => ceil($active->count/$limit)
            ];

        }

        return view('livewire.admin.merchant.importer.etsy.products',['active'=>$active]);
    }

    public function submit(){

        $store = User::findOrFail($this->user_id)->store()->first();

        foreach ($this->add as $key => $value) {

            if (Product::where('attributes','LIKE','%'.$value.'%')->count() > 0) {

                $this->notification([
                    'title'       => 'Exist',
                    'description' => 'Already Exist',
                    'icon'        => 'error'
                ]);

                continue;

            }

            $etsy = etsy_get_listing($value);

            $folder_name = random_id('p-');

            $images = [];

            foreach($etsy->images as $k => $img){

                $images[] = [
                    "displayUrl" => $img->url_570xN,
                    "url" => $img->url_fullxfull,
                    "name" => basename($img->url_570xN),
                    "original_name" => basename($img->url_fullxfull),
                    "default"=> ($k == 0)? true : false,
                    "state"=>"permanent"
                ];

            }


            foreach($images as $i => $image){
             
                // Move from temporary to permanent folder
                $random = random_id('i-'.$i.'-');

                if ($image['default'] == true) {
                    $default = $random.'.jpg';
                }

                // Move to new temporary folder
                Storage::disk('public')->put('marketplace/'.$store->id.'-'.$store->name.'/'.$folder_name.'/'.$random.'.jpg',file_get_contents($image['url'],true));

                

                $disk = Storage::disk('public')->get('marketplace/'.$store->id.'-'.$store->name.'/'.$folder_name.'/'.$random.'.jpg');

                $image = Image::make($disk)->orientate();

                ($image->height() > $image->width()) ? $dimension = $image->width() : $dimension = $image->height();

                // thumbnail fit image
                if ($dimension > $this->thumbnail_size) {
                    $img_tn = $image->fit($dimension, $dimension)->resize($this->thumbnail_size,$this->thumbnail_size,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode('jpg',80)->stream();

                    Storage::disk('public')->put('marketplace/'.$store->id.'-'.$store->name.'/'.$folder_name.'/thumbnail/'.$random.'.jpg', $img_tn);
                }else{
                    Storage::disk('public')->put('marketplace/'.$store->id.'-'.$store->name.'/'.$folder_name.'/thumbnail/'.$random.'.jpg', $image);
                }

            }

            $etsy->tags[] = "seed";

            $product = new Product;
            $product->store_id = $store->id;
            $product->sku = random_id('sku-');
            $product->name = $etsy->title;
            $product->default_image = $default;
            $product->image = 'marketplace/'.$store->id.'-'.$store->name.'/'.$folder_name.'/';
            $product->category = $this->category[$key];
            $product->style = 'style';
            $product->currency = $store->currency;
            $product->price = $etsy->price->amount/100;
            $product->shippingMethod = ShippingDomestic::where('attributes','LIKE','%'.$etsy->shipping_profile->shipping_profile_id.'%')->first()->id;
            $product->tags = $etsy->tags;
            $product->quantity = $etsy->quantity;
            $product->attributes = [
                'source'=>[
                    'platform'=>'etsy',
                    'method'=>'import',
                    'listing_id'=> $etsy->listing_id,
                    'user_id'=> $etsy->user_id,
                    'shop_id'=> $etsy->shop_id,
                    'url'=> $etsy->url,
                    'shipping_profile'=> $etsy->shipping_profile
                ]
            ];
            $product->save();

            $element = new ProductElement;
            $element->product_id = $product->id;
            $element->description = $etsy->description;
            $element->save();



            $this->notification([
                'title'       => 'Added',
                'description' => 'Added To Marketplace',
                'icon'        => 'success'
            ]);




        }


    }
}
