<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\Product;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Crud\Filters\DefaultSorted;
use Illuminate\Database\Eloquent\Model;
use App\Orchid\Filters\SearchIdFilter;

class ProductResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Product::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
			Input::make('subject')
				->title('Title')
				->placeholder('Enter title here'),

			TextArea::make('text')->rows(20),
		];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id'),
            TD::make('g_id'),
			TD::make('subject')->width('300px'),
            TD::make('created_at', 'Date of creation')
				->sort()
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
			new DefaultSorted('id', 'desc'),
			new SearchIdFilter(),
		];
    }
}
