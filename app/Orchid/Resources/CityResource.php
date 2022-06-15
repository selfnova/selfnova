<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\City;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Crud\Filters\DefaultSorted;
use Illuminate\Database\Eloquent\Model;

class CityResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = City::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
			Input::make('name')
				->title('Город'),
			Input::make('id_region')
				->title('Регион ID ')
				->value(1)
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
			TD::make('name', 'Город')->width('300px')
				->sort(),
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
		];
    }
}
