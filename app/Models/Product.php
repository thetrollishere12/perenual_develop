<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Carbon\Carbon;
use Auth;
class Product extends Model
{
    use HasFactory,AsSource;

    protected $fillable = [
        'category',
        'style',
        'name',
        'default_image',
        'image',
        'price',
        'shippingMethod',
        'description',
        'quantity',
        'attributes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
        'attributes'=>'array'
    ];

    protected $hidden = [
        'attributes'
    ];
    
    public function ProductElement(){
        return $this->hasOne(ProductElement::class,'product_id','id');
    }

    public function ProductDetails(){
        return $this->hasOne(ProductDetails::class,'product_id','id');
    }


    public function ProductDimension(){
        return $this->hasOne(ProductDimension::class,'product_id','id');
    }

    public function seen(){

            $type = "product";

            $unique = UniqueVisitor::where('ip',\request()->ip())->where('type_id',$this->id)->where('type',$type)->first();

            if ($unique) {
                
                if (Carbon::parse($unique->created_at)->addDays(1)->isPast()) {
                 
                    UniqueVisitor::create([
                        'type'=>$type,
                        'url'=>url()->current(),
                        'type_id'=>$this->id,
                        'user_id'=>(Auth::check())?Auth::user()->id:null,
                        'ip'=>\request()->ip()
                    ]);

                    ProductElement::where('product_id',$this->id)->increment('seen');

                }

            }else{
             
                UniqueVisitor::create([
                    'type'=>$type,
                    'url'=>url()->current(),
                    'type_id'=>$this->id,
                    'user_id'=>(Auth::check())?Auth::user()->id:null,
                    'ip'=>\request()->ip()
                ]);

                ProductElement::where('product_id',$this->id)->increment('seen');

            }

    }

    public function third_party_rating(){

        $store = Store::where('id',$this->store_id)->first();

        $etsy = EtsyAccount::where('userId',$store->user_id)->first();

        return $etsy;

    }

}
