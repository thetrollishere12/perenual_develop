<?php

namespace App\Http\Livewire\Profile\Product;

use Livewire\Component;
use Storage;
use App\Models\Product;
use App\Models\ProductDimension;
use App\Models\ProductDetail;
use App\Models\ProductElement;
use Intervention\Image\Facades\Image;

class CreateEtsy extends Component
{

    public $image;



    public $product;
    public $shippingMethod;
    public $description;
    public $name;   
    public $category;
    public $quantity;
    public $price;
    public $weight;
    public $length;
    public $width;
    public $height;
    public $tag;
    public $tags = [];

    public $images = [];

    // details
    public $show_details = false;
    public $show_zone = false;

    public $cycle = '';
    public $prod_width;
    public $prod_height;
    public $watering = '';
    public $sun_exposure = '';
    public $origin = '';
    public $pet_friendly;
    public $poisonous;
    public $edible;
    public $maintenance = '';
    public $growth_rate = '';
    public $flowering_season = '';
    public $fruiting_season = '';
    public $fertilizer = '';
    public $humidity = '';

    public $color = [];
    public $location = [];
    public $soil = [];
    public $hardiness;
    public $pruning = '';

    public $zone = [
        'min' => 3.00, // Targets handle 1 value
        'max' => 12.00 // Targets handle 2 value
    ];

    public $listing_id;

    protected $listeners = ['addTags','deleteTags','image','shipping'];

    public $image_size = 1300;
    public $thumbnail_size = 500;

    public function mount(){

        custom_etsy_extract_shipping();

        $etsy = etsy_get_listing($this->listing_id);
            
        $this->show_details = true;

        $this->description = $etsy->description;
        $this->name = $etsy->title;   

        $this->quantity = $etsy->quantity;
        $this->price = $etsy->price->amount/100;
        $this->tags  = $etsy->tags;


        $this->weight=$etsy->item_weight;
        $this->length=$etsy->item_length;
        $this->width=$etsy->item_width;
        $this->height=$etsy->item_height;
        

        foreach($etsy->images as $k => $img){

            $this->images[] = [
                "displayUrl" => $img->url_570xN,
                "url" => $img->url_fullxfull,
                "name" => basename($img->url_570xN),
                "original_name" => basename($img->url_fullxfull),
                "default"=> ($k == 0)? true : false,
                "state"=>"permanent"
            ];

        }

        // sort by default
        $columns = array_column($this->images, 'default');
        array_multisort($columns, SORT_DESC, $this->images);

        

        $this->options = [
            'start' => [$this->zone['min'],$this->zone['max']],
            'range' => [
                'min' =>  [1],
                'max' => [13]
            ],
            'connect' => !0,
            'step' => 1,
            'pips' => [
                'mode' => 'steps',
                'density' => 3
            ]
        ];

    }


    public function shipping($shipping){
        $this->shippingMethod = $shipping;
    }

    public function image($images){
        $this->images = $images;
    }

    public function deleteTags($key){
        unset($this->tags[$key]);
    }

    public function addTags(){

        $limit = 15;

        $validatedData = $this->validate([
            'tag' => 'required|string|max:25',
            'tags' => 'array'
        ]);

        $this->tag = trim($this->tag);

        if (in_array($this->tag,$this->tags)) {
            $this->addError('tags','Tags already exist');
        }elseif(count($this->tags) < $limit){
            $this->tags[] = $this->tag;
        }else{
            $this->addError('tags','You can only have between 0-'.$limit.' Tags');
        }

        $this->reset('tag');
        
    }

    public function submit(){

        $validate = [
            'images'=>'required|min:1|max:8',
            'name' => 'required|string|min:5|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|max:999999999999999',
            'description' => 'required|string|max:10000',
            'shippingMethod' => 'required|numeric',
            'quantity' => 'required|numeric|min:1|max:200'
        ];

        if ($this->price < 1) {
            return $this->addError('error','Price must be over $0.99');
        }

        if (env('PRODUCT_DIMENSION') == TRUE) {

            $validate += [
                'weight' => 'nullable|numeric|max:999999999',
                'length' => 'nullable|numeric|max:999999999',
                'width' => 'nullable|numeric|max:999999999',
                'height' => 'nullable|numeric|max:999999999',
            ];
         
        }

        $validate += [
            'cycle' => 'nullable|string',
            'width' =>'nullable|numeric|max:999999999',
            'height' =>'nullable|numeric|max:999999999',
            'watering' =>'nullable|string',
            'sun_exposure' =>'nullable|string',
            'origin' =>'nullable|string',
            'color' =>'nullable|array',
            'pet_friendly' => 'nullable|string',
            'poisonous' => 'nullable|string',
            'edible' => 'nullable|string',
            'location' => 'nullable|array',
            'maintenance' =>'nullable|string',
            'growth_rate' => 'nullable|string',
            'flowering_season' => 'nullable|string',
            'fruiting_season' => 'nullable|string',
            'fertilizer' => 'nullable|string',
            'humidity' => 'nullable|string',
            'soil' => 'nullable|array',
            'hardiness' => 'nullable|array',
            'pruning' => 'nullable|string',
        ];

        $validatedData = $this->validate($validate);

        $folder_name = random_id('p-');

        try {

            $store = get_store()->first();

            foreach($this->images as $i => $image){
             
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

        $etsy = etsy_get_listing($this->listing_id);

        $product = new Product;
        $product->store_id = $store->id;
        $product->sku = random_id('sku-');
        $product->name = $this->name;
        $product->default_image = $default;
        $product->image = 'marketplace/'.$store->id.'-'.$store->name.'/'.$folder_name.'/';
        $product->category = $this->category;
        $product->style = 'style';
        $product->currency = $store->currency;
        $product->price = ($this->price) ? $this->price : null;
        $product->shippingMethod = $this->shippingMethod;
        $product->tags = $this->tags;
        $product->quantity = ($this->quantity) ? $this->quantity : null;
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
        $element->description = $this->description;
        $element->save();

        if (env('PRODUCT_DIMENSION') == TRUE) {
            if ($this->weight || $this->length || $this->width || $this->height) {
                $dimension = new ProductDimension;
                $dimension->product_id = $product->id;
                $dimension->weight=$this->weight;
                $dimension->length=$this->length;
                $dimension->width=$this->width;
                $dimension->height=$this->height;
                $dimension->save();
            }
        }

        if ($this->cycle||$this->prod_width||$this->prod_height||$this->watering||$this->sun_exposure||$this->origin||$this->color||$this->pet_friendly||$this->poisonous||$this->location||$this->maintenance||$this->growth_rate||$this->flowering_season||$this->fruiting_season||$this->fertilizer||$this->humidity||$this->soil||$this->pruning||$this->show_zone == true) {

        $prod_detail = new ProductDetail;
        $prod_detail->product_id = $product->id;
        $prod_detail->cycle = $this->cycle;
        $prod_detail->width = $this->prod_width;
        $prod_detail->height = $this->prod_height;
        $prod_detail->watering = $this->watering;
        $prod_detail->sun_exposure = $this->sun_exposure;
        $prod_detail->origin = $this->origin;
        $prod_detail->pet_friendly = $this->pet_friendly;
        $prod_detail->edible = $this->edible;
        $prod_detail->poisonous = $this->poisonous;
        $prod_detail->maintenance = $this->maintenance;
        $prod_detail->growth_rate = $this->growth_rate;
        $prod_detail->flowering_season = $this->flowering_season;
        $prod_detail->fruiting_season = $this->fruiting_season;
        $prod_detail->fertilizer = $this->fertilizer;
        $prod_detail->humidity = $this->humidity;
        $prod_detail->suitable_location = $this->location;
        $prod_detail->color = $this->color;
        $prod_detail->soil = $this->soil;
        $prod_detail->hardiness = ($this->show_zone == true) ? $this->zone : null;
        $prod_detail->pruning = $this->pruning;
        $prod_detail->save();

        }

        if (env('VARIATION') == TRUE) {
            // if (isset($req->option_name)) {
            //     $variation = new Variation;
            //     $variation->product_id = $product->id;
            //     $variation->name = $req->variation_name[0];

            //     $variation->quantity = ($req->option_quantity) ? true : null;
            //     $variation->price = ($req->option_price) ? true : null;

            //     $variation->save();
            // }

            // if (isset($req->option_name)) {
            //     foreach($req->option_name as $o => $option){
            //         $v_option = new VariationList;
            //         $v_option->variation_id = $variation->id;
            //         $v_option->image = "test";
            //         $v_option->name = $req->option_name[$o];
            //         // $v_option->image = $option['option_img'];
            //         $v_option->quantity = ($req->option_quantity) ? $req->option_quantity[$o] : null;
            //         $v_option->price = ($req->option_price) ? $req->option_price[$o] : null;
            //         $v_option->save();
            //     }
            // }
        }

        session()->put('product',$product);
        return redirect('user/shop/product/success');

        } catch (\Exception $e) {
       
            return $this->addError('error', $e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.profile.product.create-etsy');
    }
}
