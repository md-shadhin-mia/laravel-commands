<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeCrudService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud-service {Model : Model class (Singular) for example User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Common service';

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

        $this->nameSpance = "App\Services";

        $this->modelNamespance = "App\Models";

        if(str_contains($this->modelName, "/")){
            $dirs=explode("/",$this->modelName);
            $this->modelName = array_pop($dirs);
            $this->nameSpance = "App\Services\\".implode("\\",$dirs);
            $this->modelNamespance = "App\Models\\".implode("\\",$dirs);
        }

        $this->serviceName = $this->modelName."Service";


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
                $this->nameSpance,
                $this->serviceName,
                $this->modelNamespance,
                $this->modelName,
                $this->modelIdentifire,
            ],
            $stub
        );
        

        if(!is_dir($this->nameSpance)){
            mkdir($this->nameSpance, 0775, true);
        }
        $path = $this->nameSpance."/".$this->serviceName.".php";
        $this->info("Path: ".$path);

        File::put($path, $template);

        return ;
    }
}
