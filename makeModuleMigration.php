<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class makeModuleMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:migration {name : Name of the migration} {module? : Name of the module}';

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
        $name = $this->argument('name');
        $module = $this->argument('module');
        if(empty($module)){
            //ask for module name
            $module = $this->ask('Enter module name')??null;
        }

        $modulePath = 'Modules/'.$module;
        if(!File::exists($modulePath)){
            $this->error('Module not found');
            return;
        }

        $this->info('Creating migration '.$name." for module ".$modulePath);
        
        $this->call('make:migration', [
            'name' => $name,
            '--path' =>  $modulePath.'/database/migrations'
        ]);
    }
}
