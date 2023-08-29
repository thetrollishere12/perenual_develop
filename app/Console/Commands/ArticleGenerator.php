<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;

class ArticleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ArticleGenerator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate article description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $articles = Article::where('parent_id',null)->where('status',1)->where('description',null)->get();

        foreach ($articles as $key => $article) {
            
            Article::where('id',$article->id)->update([
                'description'=>ltrim(AiGenerateText('Write paragraphs intro for an article titled '.$article->title.'.',['temperature'=>1]))
            ]);

            $components = Article::where('parent_id',$article->id)->get();

            foreach ($components as $k => $component) {
                
                Article::where('id',$component->id)->update([
                    'description'=>ltrim(AiGenerateText('Write 1 paragraph about '.$component->title.' for an article titled '.$article->title.'.',['temperature'=>1]))
                ]);

            }

        }

    }
}
