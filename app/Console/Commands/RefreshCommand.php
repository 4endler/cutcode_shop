<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class RefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shop refresh';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->isProduction()) {
            return self::FAILURE;
        }

        // Полная очистка кеша
        $this->fixPermissions();
        $this->clearAllCaches();

        Storage::deleteDirectory('images/products');
        Storage::deleteDirectory('images/brands');

        $this->call('migrate:fresh', ['--seed' => true]);

        return self::SUCCESS;
    }

    protected function clearAllCaches()
    {
        // Для файлового кеша
        if (config('cache.default') === 'file') {
            $storage = Storage::disk('local');
            foreach ($storage->files('framework/cache/data') as $file) {
                $storage->delete($file);
            }
        }
        
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('route:clear');
    }
    protected function fixPermissions()
    {
        if (!app()->runningUnitTests()) {
            $storagePath = storage_path();
            
            // Получаем текущего пользователя
            $user = getenv('USER') ?: getenv('USERNAME');
            $webUser = 'www-data'; // или 'nginx', 'apache' в зависимости от сервера
            
            if ($user) {
                exec("sudo chown -R {$user}:{$webUser} {$storagePath}/framework/cache");
                exec("sudo chmod -R 775 {$storagePath}/framework/cache");
            } else {
                $this->warn('Не удалось определить пользователя, пропускаем установку прав');
            }
        }
    }
}
