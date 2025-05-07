<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Preview;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use Support\ValueObjects\Price;

/**
 * @extends ModelResource<Product>
 */
class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title = 'Products';
    protected string $column = 'title';

    protected array $with = ['brand'];
    // protected array $with = ['brand','categories','properties','optionValues'];
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Название', 'title'),
            Text::make('Цена', 'price'),
            Text::make('Количество', 'quantity'),
            // Text::make('Описание', 'description'),
            // BelongsToMany::make('Категория', 'categories'),
            BelongsTo::make('Бренд', 'brand'),
            // Preview::make('Картинка', 'thumbnail',fn($item) => '/'.$item->thumbnail)
            //     ->image(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    
                    Tab::make('Основная информация', [
                        
                        ID::make()->sortable(),
                        Text::make('Название', 'title'),
                        Text::make('Цена', 'price')
                        ->changeFill(fn($item) => $item->price->raw()),
                        Text::make('Количество', 'quantity'),
                        BelongsTo::make('Бренд', 'brand')->searchable(),
                        Image::make('Картинка', 'thumbnail',fn($item) => '/'.str_replace('storage/', '', $item->thumbnail)),
                    ]),
                    Tab::make('Категории', [
                        BelongsToMany::make('Категории', 'categories'),
                    ]),
                    Tab::make('Свойства', [
                        BelongsToMany::make('Свойства', 'properties',resource: PropertyResource::class)
                        ->fields([
                            Text::make('Value'),
                        ])->creatable()
                        ,
                    ]),
                    Tab::make('Опции', [
                        BelongsToMany::make('Опции','optionValues',resource: OptionValuesResource::class)
                        // ->fields([
                        //     Text::make('Значение', 'title'),
                        // ])
                        ,
                    ]),
                ]),
                
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
        ];
    }

    /**
     * @param Product $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
