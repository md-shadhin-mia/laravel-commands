<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:model {name : Name of the model} {module? : Name of the module} {--m|migration=false} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');   //   Directory/Sales
        $module = $this->argument('module');
        if(empty($module)){
            //ask for module name
            $module = $this->ask('Enter module name')??null;
        }
        
        $pathInfo = array_filter(explode('/', $name));
        $modelName = array_pop($pathInfo);
        $modelDir = implode(DIRECTORY_SEPARATOR, $pathInfo);

        $modulesPath = base_path('Modules');
        $modulePath = $modulesPath . '/' . $module;

        if (!File::exists($modulePath . "/Models/" . $modelDir)) {
            File::makeDirectory($modulePath . "/Models/" . $modelDir, 0755, true, true);
        }

        // dd($name, $module, $modelDir);
        $migration = $this->option('migration');
        if($migration){
            $this->call('make:migration', [
                'name' => 'create'.(Str::plural($modelName)).'Table',
                '--path' =>  'Modules/'.$module.'/database/migrations'
            ]);
        }
        $model = str_replace(
            [
                '${model}',
                '${module}',
                '${model_dir}'
            ],
            [
                $modelName,
                $module,
                $modelDir === '' ? '' : "\\".$modelDir
            ],
            File::get(__DIR__.'/stubs/module-model.stub')
        );

        File::put($modulePath . "/Models/" . $modelDir . '/' . $modelName . '.php', $model);

        $this->info('Model ' . $modelName . ' created successfully');
        // $path = ;

    }
}
