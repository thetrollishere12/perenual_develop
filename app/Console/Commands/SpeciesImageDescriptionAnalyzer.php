<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SpeciesImage;

class SpeciesImageDescriptionAnalyzer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SpeciesImageDescriptionAnalyzer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze image description and get parts and colors';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {




        // Define common colors, textures, shapes, and sizes
        $colors = ["white", "green", "purple", "pink", "yellow", "brown", "gray", "blue", "indigo", "salmon", "cream", "deep", "purple", "shocking", "pink", "deep", "green", "cinnamon", "brown", "black", "red", "violet", "orange", "burgundy", "maroon", "ivory", "teal", "lilac", "magenta", "turquoise", "tan", "gold", "silver","silvery","coral", "aqua", "beige", "chartreuse", "fuchsia", "lime", "mauve", "navy", "olive", "peach", "plum", "ruby", "sapphire", "scarlet", "vermillion", "amber", "bronze", "jade", "lavender", "reddish", "green","grey"];
    $textures = ["glossy", "dewy", "exfoliating", "hairy", "fuzzy", "slender", "narrow", "pointy", "spherical", "bulbous", "tubular", "lance-shaped", "needle-like", "globular", "tulip-shaped", "rough", "smooth", "ridged", "spiny", "crinkly", "leathery", "veiny", "prickly", "fibrous", "flaky", "bumpy", "gritty", "shiny", "matte", "slimy", "slippery", "furry", "silky", "velvety", "sandy", "crusty", "sticky", "spongy", "waxy", "feathery", "thorny", "woody", "metallic", "gummy", "ruffled","barky"];
    $shapes = ["cluster","round", "oval", "heart-shaped", "star-shaped", "spear-shaped", "spoon-shaped", "serrated", "lobed", "palmate", "pinnate", "pinnately compound", "palmately compound", "lanceolate", "elliptical", "oblong", "cordate", "reniform", "linear", "needle-like", "obovate", "obcordate", "triangular", "diamond-shaped", "ovate", "orbicular", "fan-shaped", "flame-shaped", "club-shaped", "cylindrical", "conical", "globular", "spindle-shaped", "cuboid", "cubical", "pyramidal", "spherical", "columnar", "umbrella-shaped", "bell-shaped", "funnel-shaped", "tubular", "cup-shaped", "cupped", "saucer-shaped", "disk-shaped", "plate-shaped", "bell-shaped", "bellied", "bulbous", "oblate", "prolate", "ovoid", "ellipsoidal", "spheroidal", "circular", "curved", "arched", "twisted", "spiral", "whorled", "opposite", "alternate", "basal", "terminal", "axillary", "corymbose", "racemose", "panicle", "umbellate", "spike", "capitate", "cyme", "dense", "open", "branched", "simple", "compound", "undivided", "divided", "palmately lobed", "pinnately lobed", "irregular", "symmetrical", "asymmetrical", "elongated","rose-like"];
    $sizes = ["small", "medium", "large", "tiny", "big", "gigantic", "massive", "miniature", "compact", "oversized", "mini", "micro", "nano", "jumbo", "petite", "majestic", "stout", "towering", "mammoth", "enormous", "ginormous","tall","short"];
    $plantParts = ["flowers","flower", "leaf", "leaves", "stem", "stems", "bark","barks","frond", "fronds", "bulb", "bulbs", "anther", "anthers", "petal", "petals", "root", "roots", "branch", "branches", "twig", "twigs", "bud", "buds", "fruit", "fruits", "seed", "seeds", "thorn", "thorns", "pod", "pods", "stalk", "stalks", "crown", "crowns", "blade", "blades", "stolon", "stolons", "shoot", "shoots", "bract", "bracts", "pistil", "pistils", "stigma", "stigmas", "capsule", "capsules", "cone", "cones", "foliage", "sprout", "sprouts", "blossom", "blossoms", "spore", "spores", "pollen", "vines", "vine", "tendril", "tendrils", "kernel", "kernels", "berry", "berries", "nut", "nuts", "pod", "pods", "trunk", "trunks", "rhizome", "rhizomes", "tuber", "tubers", "nectar", "pith", "calyx", "calyces", "corolla", "corollas", "sepal", "sepals", "catkin", "catkins", "spadix", "spadices", "spathe", "spathes", "stipe", "stipes", "capsule", "capsules", "ovule", "ovules", "stamen", "stamens", "filament", "filaments", "receptacle", "receptacles", "axil", "axils", "node", "nodes", "internode", "internodes", "bristle", "bristles", "burr", "burrs", "gland", "glands", "hair", "hairs", "nectary", "nectaries", "prickle", "prickles", "scale", "scales", "suture", "sutures", "tepal", "tepals", "umbel", "umbels", "vein", "veins", "midrib", "midribs", "petiole", "petioles", "rachis", "rachises", "sheath", "sheaths", "stipule", "stipules", "areole", "areoles", "cladode", "cladodes", "pseudobulb", "pseudobulbs", "tendril", "tendrils", "bulbil", "bulbils", "corm", "corms", "rhizome", "rhizomes", "stolon", "stolons", "tuber", "tubers", "ovary", "ovaries", "ovule", "ovules", "pistil", "pistils", "stigma", "stigmas", "style", "styles", "achene", "achenes", "berry", "berries", "capsule", "capsules", "caryopsis", "caryopses", "cypsela", "cypselae", "drupe", "drupes", "legume", "legumes", "nut", "nuts", "samara", "samaras", "schizocarp", "schizocarps", "silique", "siliques", "utricle", "utricles", "wood", "woods", "canopy", "canopies", "cell", "cells", "chloroplast", "chloroplasts", "thicket", "thickets", "grove", "groves", "forest", "forests", "understory", "understories", "tissue", "tissues", "vascular system", "vascular systems", "xylem", "phloem", "mesophyll", "epidermis", "cuticle", "cuticles", "resin", "resins", "gum", "gums", "nectar", "spine", "spines", "camouflage", "camouflages", "thallus", "thalli", "mycelium", "mycelia", "hypha", "hyphae", "sporangium", "sporangia", "zygote", "zygotes", "gamete", "gametes", "plankton", "bush", "bushes", "shrub", "shrubs", "herb", "herbs", "weed", "weeds", "vine", "vines", "moss", "mosses", "fern", "ferns", "algae", "fungus", "fungi", "bacterium", "bacteria", "archaeon", "archaea", "protist", "protists", "artichoke", "artichokes", "aril", "arils", "asparagus", "asparaguses", "bean", "beans", "beet", "beets", "bok choy", "bok choys", "broccoli", "broccolis", "brussels sprout", "brussels sprouts", "cabbage", "cabbages", "carrot", "carrots", "cauliflower", "cauliflowers", "celery", "celeries", "chard", "chards", "collard", "collards", "corn", "corns", "cucumber", "cucumbers", "eggplant", "eggplants", "fennel", "fennels", "garlic", "garlics", "ginger", "gingers", "grapefruit", "grapefruits", "kale", "kales", "leek", "leeks", "lettuce", "lettuces", "mango", "mangos", "mushroom", "mushrooms", "okra", "okras", "onion", "onions", "pepper", "peppers", "potato", "potatoes", "pumpkin", "pumpkins", "radish", "radishes", "spinach", "spinaches", "squash", "squashes", "sweet potato", "sweet potatoes", "tomato", "tomatoes", "turnip", "turnips", "watermelon", "watermelons", "zucchini", "zucchinis","leaflets","leaflet","baby cones","baby cone","needles","bloom","blooms","margin","margins","pines","pine","center","falls","cactus","spikelets","spikelet","veining"];





        $images = SpeciesImage::whereNot('description',null)->get();

        foreach ($images as $key => $image) {
            

            $description = $image->description;

            // Find all matches of the plant parts in the description
            preg_match_all('/\b(?:' . implode('|', $plantParts) . ')\b/i', $description, $plantMatches);

            $plantDetails = [];

            foreach ($plantMatches[0] as $plantPart) {
                $color = null;
                $texture = null;
                $shape = null;
                $size = null;

                // Find the preceding four words
                preg_match('/(?:\b\w+\b\W+){0,4}' . preg_quote($plantPart, '/') . '/', $description, $precedingWordsMatch);
                if (!empty($precedingWordsMatch[0])) {
                    $precedingWords = preg_split('/\s+/', trim($precedingWordsMatch[0]));
                    $precedingWords = array_map('strtolower', $precedingWords);
                    $precedingWords = array_filter($precedingWords);

                    // Check if any of the preceding words match color, texture, shape, or size
                    foreach ($precedingWords as $word) {
                        
                        $word = strtolower($word);

                        // Check for exact color matches
                        if (in_array($word, $colors)) {
                            $color = $word;
                        }

                        // Check for color combinations
                        foreach ($colors as $colorOption) {
                            if (strpos($word, $colorOption) !== false) {
                                $color = $word;
                                break;
                            }
                        }



                        // Check for exact texture matches
                        if (in_array($word, $textures)) {
                            $texture = $word;
                        }

                        // Check for texture combinations
                        foreach ($textures as $textureOption) {
                            if (strpos($word, $textureOption) !== false) {
                                $texture = $word;
                                break;
                            }
                        }



                        // Check for exact size matches
                        if (in_array($word, $sizes)) {
                            $size = $word;
                        }

                        // Check for size combinations
                        foreach ($sizes as $sizeOption) {
                            if (strpos($word, $sizeOption) !== false) {
                                $size = $word;
                                break;
                            }
                        }



                        // Check for exact shape matches
                        if (in_array($word, $shapes)) {
                            $shape = $word;
                        }

                        // Check for shape combinations
                        foreach ($shapes as $shapeOption) {
                            if (strpos($word, $shapeOption) !== false) {
                                $shape = $word;
                                break;
                            }
                        }


                        if (in_array($word, $textures)) {
                            $texture = $word;
                        }
                        if (in_array($word, $shapes)) {
                            $shape = $word;
                        }
                        if (in_array($word, $sizes)) {
                            $size = $word;
                        }
                    }
                }

                $plantDetails[] = ['part' => $plantPart, 'color' => $color, 'texture' => $texture, 'shape' => $shape, 'size' => $size];
            }

            $result = array_map(function($item) {
                $item = array_filter($item, function($value) {
                    return !is_null($value);
                });

                // Use array_map to apply rtrim to each item
                $item = array_map(function($value) {
                    return rtrim($value, ',');
                }, $item);

                return $item;

            },$plantDetails);

            if (count($result) > 0) {
            
                SpeciesImage::where('id',$image->id)->update([
                    'plant_image_anatomy'=>$result
                ]);
                
                echo(' Image ID - '.$image->id.' added '.count($result).' parts ');
  
            }



        }

    }
}
