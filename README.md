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
- DTO  для signUpController (потому что данные могут прийти не только как реквест, а еще как нибудь иначе)
- value object для цены
- cats для этого value object, чтобы в модели кастить цену сразу
- свои queryBuilders вместо scope
- оптимизациия viewMOdel (там кешируем). Трабл с правами при сбросе кеша через shop:refresh
- menu (классы). добавляем его во view composer (ViewServiceProvider). Создать view/composers/navigationcomposer
- оптимизациия индексы на поля по которым фильтруем
- filters в domain/catalog. Отдельный сервис провайдер для фильтров. Хелпер - получить все фильтры. Не забыть подключить провайдер в providers
- Просмотренные товары (productController)
- вид списка каталога (плитка - список) через мидлвар CatalogViewMiddleware
- сортировка (класс Sorter), подключение в CatalogServiceProvider

- опубликовать блейды по пагинации php artisan vendor:publish --tag=laravel-pagination

- json_properties в Product для слепка характеристик, чтобы не дергать в каталоге их и в самой карточке
- т.к. при создании товара (seed) характеристик еще нет, то делаем job make:job ProductJsonProperties
- также нужно сделать чтобы джобы выполнялись в очереди, а не синхронно (как по умолчанию) - php artisan queue:table, и в env QUEUE_CONNECTION=database
- запустить воркера artisan queue:work
- фитры и сортировка в ProductQueryBuilder
- аксессор в модели Продукст getPropertiesAttribute (для отдачи актуальных свойств, из json_properties или DB)
- DomainCommand для создания домейнов, + stubs

- CartIdentityStorageContract - для смены cartsorage если че?
Дз
Фильтры для опций и характеристик
Добавить для характеристик и опций привязку к категориям
Опциям добавить остаток на складе
Хлебные крошки вынести в отдельный слой

# deploy
