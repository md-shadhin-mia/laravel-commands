<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:controller {name : Name of the controller} {module? : Name of the module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     **/
    public function handle()
    {
        //
        $name = $this->argument('name');
        $module = $this->argument('module');
        if(empty($module)){
            //ask for module name
            $module = $this->ask('Enter module name')??null;
        }

        $pathInfo = array_filter(explode('/', $name));
        $modelName = Str::squish(array_pop($pathInfo));
        $modelDir = implode(DIRECTORY_SEPARATOR, $pathInfo);

        $modulesPath = base_path('Modules');
        $modulePath = $modulesPath . '/' . $module;

        if (!File::exists($modulePath . "/Controllers/" . $modelDir)) {
            File::makeDirectory($modulePath . "/Controllers/" . $modelDir, 0755, true, true);
        }



        $stub = File::get(__DIR__.'/stubs/crud-controller.stub');

        $template = str_replace(
            [
                '{{namespace}}',
                '{{service_namespace}}',
                '{{service}}',
                '{{controller}}',
                '{{model_namespace}}',
                '{{model}}',
                "{{model_identifire}}"
            ],
            [
                "Modules\\".$module."\\Controllers" . ($modelDir?"\\{$modelDir}":''),
                "Modules\\".$module."\\Services" . ($modelDir?"\\{$modelDir}":''),
                "{$modelName}Service",
                $modelName."Controller",
                "Modules\\".$module."\\Models" . ($modelDir?"\\{$modelDir}":''),
                $modelName,
                Str::camel($modelName)
            ],
            $stub
        );


        File::put($modulePath . "/Controllers/" . $modelDir . '/' . $modelName . 'Controller.php', $template);



        $this->info('Controller created successfully.');

    }
}
