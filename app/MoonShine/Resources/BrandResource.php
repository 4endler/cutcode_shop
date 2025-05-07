<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Model;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Support\Enums\SortDirection;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Preview;
use MoonShine\UI\Fields\Switcher;

/**
 * @extends ModelResource<Brand>
 */
class BrandResource extends ModelResource
{
    //TODO: сделать сортировку перетаскиванием
    protected string $model = Brand::class;

    protected string $title = 'Brands';

    protected string $column = 'title';

     // Поле сортировки по умолчанию
     protected string $sortColumn = 'rank';
 
     // Тип сортировки по умолчанию
     protected SortDirection $sortDirection = SortDirection::ASC;
    
    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Заголовок', 'title'),
            Preview::make('Картинка', 'thumbnail',fn($item) => '/'.$item->thumbnail)
                ->image(),
            Switcher::make('On home page')->updateOnPreview()->sortable(),
            Number::make('Порядок сортировки', 'rank')->sortable(),
        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
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
     * @param Brand $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    public function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return parent::modifyListComponent($component)->fields([
            ...parent::modifyFormComponent($component)->getFields()->toArray(),
        ])->reorderable(route('brands.reorder'));
    }

    protected function search(): array
    {
        return ['id', 'title'];
    }
}
