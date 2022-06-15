<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\Report;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\Link;
use Orchid\Crud\Filters\DefaultSorted;
use Illuminate\Database\Eloquent\Model;
use App\Orchid\Filters\ReportFilter;

class ReportsResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Report::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [

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
            TD::make('u_id', 'ID юзера'),
            TD::make('type_id', 'ID типа'),
			TD::make('type', 'Тип')
				->sort(),
			TD::make('link', 'Ссылка')
				->render(function ($user) {
					return Link::make('Ссылка на ресурс')
						->href("http://selfnova.co/panel/crud/list/$user->type-resources?id=$user->type_id");
				}),
			TD::make('created_at', 'Дата')
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
			new ReportFilter(),
		];
    }
}
