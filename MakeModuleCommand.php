<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $modulesPath = base_path('Modules');
        $modulePath = $modulesPath . '/' . $name;

        if (!File::exists($modulePath)) {
            File::makeDirectory($modulePath, 0755, true, true);
        }

        $subFolders = ['Controllers', 'database/migrations', 'database/seeders', 'Models', 'Providers', 'resources/views/partials', 'routes', 'Services'];
        foreach ($subFolders as $folder) {
            if (!File::exists($modulePath . '/' . $folder)) {
                File::makeDirectory($modulePath . '/' . $folder, 0755, true, true);
            }
        }

        $files = [
            'Providers/'.$name.'ServiceProvider.php' => str_replace('${name}', $name, File::get(__DIR__.'/stubs/module-provider.stub')),
            'routes/api.php' => str_replace('${name}', $name, File::get(__DIR__.'/stubs/routes-api.stub')),
            'routes/web.php' => str_replace('${name}', $name, File::get(__DIR__.'/stubs/routes-web.stub')),
            'resources/views/partials/_sidebar.blade.php' => str_replace('${name}', $name, File::get(__DIR__.'/stubs/_sidebar.stub')),
        ];

        foreach ($files as $file => $content) {
            File::put($modulePath . '/' . $file, $content);
        }

        $this->info('Module created successfully');
        $this->info('Provider adding to config/app.php');
        $providerContent = file_get_contents(config_path('app.php'));
        $newProviderContent = str_replace(
            "// Add your module providers here",
            "Modules\\".$name."\Providers\\".$name."ServiceProvider::class,\n        // Add your module providers here",
            $providerContent
        );
        file_put_contents(config_path('app.php'), $newProviderContent);

        $this->info('Provider added successfully');

        return 0;
    }
}
