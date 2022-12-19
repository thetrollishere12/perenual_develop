<?php

namespace App\Http\Livewire\Profile\User\MyPlant;

use Livewire\Component;
use App\Models\MyPlant;
use Auth;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;
    public $plants;

    public function delete_plant($id){

        try{

            $plant = MyPlant::where('plant_id',$id)->where('user_id',Auth::id())->delete();

            return $this->notification([
                'title'       => 'Plant Deleted',
                'description' => 'Your plant has been deleted from your account',
                'icon'        => 'success',
            ]);

        }catch(\Exception $e){

            return $this->notification([
                'title'       => 'Error. Try Again!',
                'description' => 'There was an error trying to delete your plant. Please try again',
                'icon'        => 'error',
            ]);

        }

    }

    public function render()
    {

        $this->plants = MyPlant::where('user_id',Auth::id())->get();

        return view('livewire.profile.user.my-plant.index');
    }
}
