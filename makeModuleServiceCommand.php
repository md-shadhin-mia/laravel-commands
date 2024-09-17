<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class makeModuleServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:service {name : Name of the service} {module? : Name of the module}';

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

        if (!File::exists($modulePath . "/Services/" . $modelDir)) {
            File::makeDirectory($modulePath . "/Services/" . $modelDir, 0755, true, true);
        }

        $stub = File::get(__DIR__.'/stubs/crud-service.stub');
        $template = str_replace(
            [
                '{{namespace}}',
                '{{service}}',
                '{{model_namespace}}',
                '{{model}}',
                "{{model_identifire}}"
            ],
            [
                "Modules\\".$module."\\Services" . ($modelDir?"\\{$modelDir}":''),
               '',
                "Modules\\".$module."\\Models" . ($modelDir?"\\{$modelDir}":''),
               $modelName,
               Str::camel($modelName)
            ],
            $stub
        );


        File::put($modulePath . "/Services/" . $modelDir . "/".$modelName."Service.php", $template);


        $this->info('Service created successfully');
    }
}
