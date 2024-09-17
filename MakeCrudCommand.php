<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {Model : Model class (Singular) for example User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Crud';
    /**
     * model argument
     *
     * @var string
     */
    private $modelName = 'Create a Crud';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //model
        $this->modelName = $this->argument('Model');

        //make model with migration
        $this->call('make:model', ['name' => $this->modelName, '-m' => true, '--no-interaction' => true]);

        // artisan command for make crud
        $this->call('make:crud-controller', ['Model' => $this->modelName]);
        $this->call('make:crud-service', ['Model' => $this->modelName]);
    }
}
