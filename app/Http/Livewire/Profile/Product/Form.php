<?php

namespace App\Http\Livewire\Profile\Product;

use Livewire\Component;
use Storage;
use App\Models\Product;
use App\Models\ProductDimension;
use App\Models\ProductDetail;
use App\Models\ProductElement;
use Intervention\Image\Facades\Image;

class Form extends Component
{

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

    protected $listeners = ['addTags','deleteTags','image','shipping'];

    public function mount(){

        if ($this->product) {
            
            $this->show_details = true;
            
            $this->shippingMethod = $this->product->shippingMethod;
            $this->description = $this->product->description;
            $this->name = $this->product->name;   
            $this->category = $this->product->category;
            $this->quantity = $this->product->quantity;
            $this->price = $this->product->price;
            $this->tags  = $this->product->tags;

            $this->cycle = ($this->product->cycle) ? $this->product->cycle : "";
            $this->prod_width = $this->product->width;
            $this->prod_height = $this->product->height;
            $this->watering = ($this->product->watering) ? $this->product->watering : "";
            $this->sun_exposure = ($this->product->sun_exposure) ? $this->product->sun_exposure : "";
            $this->origin = ($this->product->origin) ? $this->product->origin : "";
            $this->pet_friendly = $this->product->pet_friendly;
            $this->poisonous = $this->product->poisonous;
            $this->edible = $this->product->edible;
            $this->maintenance = ($this->product->maintenance) ? $this->product->maintenance : "";
            $this->growth_rate = ($this->product->growth_rate) ? $this->product->growth_rate : "";
            $this->flowering_season = ($this->product->flowering_season) ? $this->product->flowering_season : "";
            $this->fruiting_season = ($this->product->fruiting_season) ? $this->product->fruiting_season : "";
            $this->fertilizer = ($this->product->fertilizer) ? $this->product->fertilizer : "";
            $this->humidity = ($this->product->humidity) ? $this->product->humidity : "";

            $this->soil = ($this->product->soil) ? json_decode($this->product->soil) : [];
            $this->color = ($this->product->color) ? json_decode($this->product->color) : [];
            $this->location = ($this->product->suitable_location) ? json_decode($this->product->suitable_location) : [];

            if (!empty(json_decode($this->product->hardiness))) {
                $this->show_zone = true;
                $this->zone = json_decode($this->product->hardiness,true);
            }

            $this->pruning = $this->product->pruning;

            $dimension = ProductDimension::where('id',$this->product->id)->first();

            if ($dimension) {
                $this->weight=$dimension->weight;
                $this->length=$dimension->length;
                $this->width=$dimension->width;
                $this->height=$dimension->height;
            }

            foreach(Storage::disk('public')->files($this->product->image) as $img){

                $this->images[] = [
                    "displayUrl" => Storage::disk('public')->url($this->product->image.'thumbnail/'.basename($img)),
                    "url" => Storage::disk('public')->url($img),
                    "name" => basename($img),
                    "original_name" => basename($img),
                    "default"=> ($this->product->default_image == basename($img)) ? true : false,
                    "state"=>"permanent"
                ];

            }

            // sort by default
            $columns = array_column($this->images, 'default');
            array_multisort($columns, SORT_DESC, $this->images);

        }

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

    public function generate_description(){

        $this->validate([
            'name' => 'required|string|min:5|max:255'
        ]);

        try{

        $this->description  .= AiGenerateText('generate paragraph product description with line breaks for'.$this->name.'.');

        }catch(\Exception $e){
            return $this->addError('error', 'There was a generating error. Please try again');
        }

    }

    public function generate_tag(){

        $this->validate([
            'name' => 'required|string|min:5|max:255'
        ]);

        try{

        $response = str_replace(array("\r", "\n","#"), '',AiGenerateText('generate tags for '.$this->name.'.'));

        $tags = explode('-',$response);

        $limit = 15;

        foreach($tags as $t => $tag){
            if (count($this->tags) < $limit && strlen($tags[$t]) != 0 && strlen($tags[$t]) < 25) {
                $this->tags[] = trim($tags[$t]);
            }
        }

        }catch(\Exception $e){
            return $this->addError('error', 'There was a generating error. Please try again');
        }

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
            Storage::disk('public')->move('livewire-tmp/iq-'.$image['original_name'],'marketplace/'.$store->id.'-'.$store->name.'/'.$folder_name.'/thumbnail/'.$random.'.jpg');

            Storage::disk('public')->move('livewire-tmp/'.$image['name'],'marketplace/'.$store->id.'-'.$store->name.'/'.$folder_name.'/'.$random.'.jpg');

        }

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

    public function update(){

        $validate = [
            'images'=>'required|min:1|max:8',
            'name' => 'required|string|min:5|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|max:999999999999999',
            'description' => 'required|string|max:10000',
            'shippingMethod' => 'required|numeric',
            'quantity' => 'required|numeric|min:1|max:200'
        ];

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

        try {

        Product::where('id',$this->product->id)->where('sku',$this->product->sku)->update([
            'name'=>$this->name,
            'price'=>($this->price) ? $this->price : null,
            'quantity'=>($this->quantity) ? $this->quantity : null,
            'tags'=>$this->tags,
            'shippingMethod'=>$this->shippingMethod,
            'category' => $this->category
        ]);

        ProductElement::where('product_id',$this->product->id)->update([
            'description'=>$this->description
        ]);

        if (env('PRODUCT_DIMENSION') == TRUE) {

            $dimension = ProductDimension::where('product_id',$this->product->id)->get();

            if ($dimension->count() > 0) {
                
                ProductDimension::updateOrCreate([
                    'product_id' => $this->product->id
                ],[
                    'weight' => ($this->weight) ? $this->weight : null,
                    'length' => ($this->length) ? $this->length : null,
                    'width' => ($this->width) ? $this->width : null,
                    'height' => ($this->height) ? $this->height : null
                ]);

            }else{

                if ($this->weight || $this->length || $this->width || $this->height) {
                    $dimension = new ProductDimension;
                    $dimension->product_id = $this->product->id;
                    $dimension->weight=$this->weight;
                    $dimension->length=$this->length;
                    $dimension->width=$this->width;
                    $dimension->height=$this->height;
                    $dimension->save();
                }
            }
        }

        $details = ProductDetail::where('product_id',$this->product->id)->get();

        if ($details->count() > 0) {

            ProductDetail::updateOrCreate([
                'product_id' => $this->product->id
            ],[
                'cycle' => $this->cycle,
                'width' => ($this->prod_width) ? $this->prod_width : null,
                'height' => ($this->prod_height) ? $this->prod_height : null,
                'watering' => $this->watering,
                'sun_exposure' => $this->sun_exposure,
                'origin' => $this->origin,
                'color' => $this->color,
                'pet_friendly' => $this->pet_friendly,
                'poisonous' => $this->poisonous,
                'edible' => $this->edible,
                'suitable_location' => $this->location,
                'maintenance' => $this->maintenance,
                'growth_rate' => $this->growth_rate,
                'flowering_season' => $this->flowering_season,
                'fruiting_season' => $this->fruiting_season,
                'fertilizer' => $this->fertilizer,
                'humidity' => $this->humidity,
                'soil' => $this->soil,
                'hardiness' => ($this->show_zone == true) ? $this->zone : null,
                'pruning' => $this->pruning
            ]);

        }else{



            if ($this->cycle||$this->prod_width||$this->prod_height||$this->watering||$this->sun_exposure||$this->origin||$this->color||$this->pet_friendly||$this->poisonous||$this->edible||$this->location||$this->maintenance||$this->growth_rate||$this->flowering_season||$this->fruiting_season||$this->fertilizer||$this->humidity||$this->soil||$this->pruning||$this->show_zone == true) {

                $prod_detail = new ProductDetail;
                $prod_detail->product_id = $this->product->id;
                $prod_detail->cycle = $this->cycle;
                $prod_detail->width = $this->prod_width;
                $prod_detail->height = $this->prod_height;
                $prod_detail->watering = $this->watering;
                $prod_detail->sun_exposure = $this->sun_exposure;
                $prod_detail->origin = $this->origin;
                $prod_detail->pet_friendly = $this->pet_friendly;
                $prod_detail->poisonous = $this->poisonous;
                $prod_detail->edible = $this->edible;
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

        }

        $directory = Storage::disk('public')->files($this->product->image);

        foreach($directory as $image){

            $exist = array_search(basename($image), array_column($this->images, 'original_name'));

            if ($exist === false) {
                // If image does not exist in array, delete the image from permanent folder
                Storage::disk('public')->delete($image);
                Storage::disk('public')->delete($this->product->image.'thumbnail/'.basename($image));
            }

        }

        // Moving Images from livewire temporary folder
        foreach($this->images as $i => $images){

            $name = random_id('i-'.$i.'-');

            if ($images['state'] == "temporary") {

                // Moving thumbnail & fit photo
                Storage::disk('public')->move('livewire-tmp/iq-'.$images['original_name'],$this->product->image.'thumbnail/'.$name.'.jpg');

                Storage::disk('public')->move('livewire-tmp/'.$images['name'],$this->product->image.$name.'.jpg');

                $this->images[$i]['name'] = $name.'.jpg';

            }elseif(isset($images['cropped']) && $images['cropped'] == "true" && $images['state'] == "permanent") {

                // Moving thumbnail & fit photo
                Storage::disk('public')->move('livewire-tmp/iq-'.$images['original_name'],$this->product->image.'thumbnail/'.$name.'.jpg');

                Storage::disk('public')->move('livewire-tmp/'.$images['name'],$this->product->image.$name.'.jpg');

                // Delete the outdated image from crop
                Storage::disk('public')->delete($this->product->image.'thumbnail/'.$images['original_name']);
                Storage::disk('public')->delete($this->product->image.$images['original_name']);
                $this->images[$i]['name'] = $name.'.jpg';
            }

        }

        // Reconfigurate Default
        if(!in_array($this->product->image.$this->product->default,$directory)){
            $_default = false;
            // If default image has been changed to something else
            foreach($this->images as $i => $images){

                if ($images['default'] == true) {
                    $_default = true;
                    Product::where('id',$this->product->id)->where('sku',$this->product->sku)->update([
                        'default_image'=>$images['name']
                    ]);
                    break;
                }

            }

            // If none is set to default auto makes the first one default
            if ($_default == false) {
                Product::where('id',$this->product->id)->where('sku',$this->product->sku)->update(['default_image'=>basename($directory[0])]);
            }

        }

        return redirect('user/shop/product')->with('success','Successfully updated listing');

        } catch (\Exception $e) {
            return $this->addError('error', $e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.profile.product.form',['product'=>$this->product]);
    }
}
