<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Species;
use App\Models\SpeciesCareGuide as SpeciesCareGuide_;
use App\Models\SpeciesCareGuideSection; 

use App\Models\UniqueVisitor; 

use Carbon\Carbon;

class SpeciesCareGuide extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SpeciesCareGuide';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create species care guide for specific species';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        set_time_limit(0);


        $species_of_the_day = UniqueVisitor::where('type','species')->whereDate('created_at', Carbon::yesterday())->select('type_id')->groupBy('type_id')->orderByRaw('COUNT(*) DESC')->limit(200)->pluck('type_id');

        $species = Species::whereBetween('id', [1, 1000])->get();
        
        foreach ($species as $value) {

            $types = ['watering','sunlight','pruning'];

            foreach ($types as $type) {
                
                $guide = SpeciesCareGuide_::where('species_id',$value->id)
                ->first();

                if(!$guide){
                    
                    $guide = SpeciesCareGuide_::create([
                        'species_id' => $value['id'],
                        'common_name' => $value['common_name'],
                        'scientific_name' => $value['scientific_name']
                    ]);
                    echo(' created - '.$value->id);

                }

                if (SpeciesCareGuideSection::where('guide_id',$guide->id)->where('type',$type)->count() > 0) {
                    continue;

                    // Update

                    // $array = [
                    //     'description' => ltrim(AiGenerateTextV2('Write on how much & when for '.$type.' for a plant species called'.$value['common_name']." (".$value['scientific_name'][0].') in detail but dont make it long.',[],0)),
                    //     'generated_user_id'=>0
                    // ];
                        
                    // SpeciesCareGuideSection::where('guide_id',$guide->id)->where('type',$type)->update($array);
                    // echo('Redid Guide -'.$guide->id.' type '.$type.' species - '.$value['id']);
                }else{


                    $array = [
                        'guide_id'=>$guide->id,
                        'type' => $type,
                        'description' => ltrim(AiGenerateTextV2('Write on how much & when for '.$type.' for a plant species called'.$value['common_name']." (".$value['scientific_name'][0].') in detail but dont make it long.',[],1)),
                        'generated_user_id'=>0
                    ];

                    // if ($value->fruits) {
                    //     $array['fruits'] = ltrim(AiGenerateText('Write paragraphs about solely just on fruits for '.$value['common_name'].'.',[]));
                    // }

                    // if ($value->poisonous_to_pets || $value->poisonous_to_humans) {
                    //     $array['toxic'] = ltrim(AiGenerateText('Write paragraphs about solely just on poisonous for '.$value['common_name'].'.',[]));
                    // }

                    SpeciesCareGuideSection::create($array);
                    echo(' Made Guide Section - '.$guide->id.' type '.$type.' species - '.$value['id']);

                }

            }

        }


    }
}