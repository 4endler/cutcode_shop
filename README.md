# instalation
- php artisan storage:link

- php artisan stub:publish (публикуем шаблоны создания миграций, котроллеров и т.д., чтобы их изменить)

- trait HasSlug
- shouldBeStrict, DB::listen, RateLimiter
- TelegramBotApi (logger()->channel('telegram'))
- faker->file()
- fakerImageProvider
- RefrechCommand (php artisan make:command RefreshCommand)
- RateLimit for auth
- Flash Messages
- helpers (подключить в composer.json) (composer dump-autoload - пересобрать autoload)
- tests (env.testing в gitignore)
- domains (composer.json -> autoload)
- registerNewUserAction вынесли регистрацию в экшн (потому что может использоваться в других местах)
- интерфейс для registerNewUserAction в contracts
- провайдер DomainServiceProvider (добавить его в bootstrap providers)
- в TestCase указать что все http в тестах фейовые
- покрытие тестами phpunit (в vs code не нашел как)
- scope в Brands 
- composer require intervention/image-laravel
- php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"
- создаем диск images в filesystem
- создаем route для images, контроллер, конфиг
- Trait для images

# deploy
