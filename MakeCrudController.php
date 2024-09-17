<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCrudController extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'make:crud-controller {Model : Model class (Singular) for example User}';

     /**
     * The console command description.
     *
     * @var string
     */       
     protected $description = 'Create a Controller';

     /**
     * @var string
     */
     private $nameSpance;

     /**
     * @var string
     */
     private $modelName;

      /**
     * @var string
     */
     private $serviceName;

      /**
     * @var string
     */
     private $controller;

     /**
     * @var string
     */
     private $serviceNamespance;

     
     /**
     * @var string
     */

     private $modelIdentifire;

     /**
     * @var string
     */

     private $modelNamespance;

     /**
     * Execute the console command.
     */
    public function handle()
    {
        //model
        $this->modelName = $this->argument('Model');

        $this->nameSpance = "App\Http\Controllers";

        $this->modelNamespance = "App\Models";
        $this->serviceNamespance ="App\Services";

        if(str_contains($this->modelName, "/")){
            $dirs=explode("/",$this->modelName);
            $this->modelName = array_pop($dirs);
            $this->nameSpance = "App\Http\Controllers\\".implode("\\",$dirs);
            $this->serviceNamespance = "App\Services\\".implode("\\",$dirs);
            $this->modelNamespance = "App\Models\\".implode("\\",$dirs);
        }

        $this->serviceName = $this->modelName."Service";

        $this->controller = $this->modelName."Controller";

        $this->modelIdentifire = substr_replace($this->modelName,strtolower(substr($this->modelName, 0,1)), 0,1);

        $this->info('your model is '.$this->modelIdentifire);

        $this->generateService();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function generateService()
    {
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
                $this->nameSpance,
                $this->serviceNamespance,
                $this->serviceName,
                $this->controller,
                $this->modelNamespance,
                $this->modelName,
                $this->modelIdentifire,
            ],
            $stub
        );

        if(!is_dir($this->nameSpance)){
            mkdir($this->nameSpance, 0775, true);
        }

        $path = $this->nameSpance."/".$this->controller.".php";
        
        $this->info("Path: ".$this->serviceNamespance);

        File::put($path, $template);

        return ;
    }
}
