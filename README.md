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

- events для корзины make:event AfterSessionRegenerated
- создадим слой который будет заниматься регенерацией сессий ( Support/SessionRegenerator)
И добавляем в нужных местах (вход, выход, регистрация) вместо $request->session()->regenerate(); SessionRegenerator::run()
В EventServiceProvider добавляем Listener на это событие
и в корзине делаем метод для переопределния storage_id (cartManager)

- для тество фейкаем корзину, потому что она работает с сессиями

- Prunable, для удаления из БД брошенных корзин (для запуска - artisan model:prune --pretend)
лучше через schedule. 

1. Настройка очистки через планировщик (рекомендуемый способ)

Добавьте в app/Console/Kernel.php:
php

protected function schedule(Schedule $schedule)
{
    $schedule->command('session:gc')->daily(); // Очистка раз в день
    // Или чаще, если нужно:
    // $schedule->command('session:gc')->hourly();
}


- изоляция домейнов: через конфиг, например:  config/cart.php
-States для того чтобы например статусы заказа могли переходить только из конкретных в конкретные, а не в любые, например чтобы Оплачен не мог перейти в Новый
- Создаем событи OrderStatusChanged и OrderCreated
- кастомные правила валидации в OrderFormRequest
a mkake:rule PhoneRule (не стал делать)

- В support класс для транзакцийы
- в Order/Processes процессы по заказу
- в директории order/contracts общий интерфейс для процессов
- там же свой exception
- в payment делаем стейты уже не самостоятельно, как в Ордер, а с помощью пакета  composer require spatie/laravel-model-states

- т.к. ответ от платежной системы будет приходить бес csrf, надо роут в мидлваре отключить в bootsrap/app
- make:controller PurchaseController
- Оплату доделать
- Сбрасывать кеш через обсервер (php artisan make:observer BrandObserver ), зарегистрировать его в AppServiceProvider

Дз
Фильтры для опций и характеристик
Добавить для характеристик и опций привязку к категориям
Опциям добавить остаток на складе
Хлебные крошки вынести в отдельный слой
Избранное
-- брошенная корзина (отправлять уведомления) https://learn.cutcode.dev/course/63
Удалять сгенерированные изображения
# deploy
