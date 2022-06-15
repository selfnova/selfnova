<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\News;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\CheckBox;
use App\Orchid\Filters\SearchIdFilter;

use Orchid\Crud\Filters\DefaultSorted;

class NewsResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = News::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
			Input::make('name')->title('Name'),
			Input::make('alias')->title('Ссылка'),
			Quill::make('content')->title('Контент')
    			->toolbar(["text", "color", "header", "list", "format", "media"]),
			TextArea::make('short_cont')->rows(5)->title('Сокращенный контент'),
			Input::make('title')->title('Title'),
			Input::make('description')->title('Description'),
			Input::make('alt')->title('Alt'),
			CheckBox::make('active')
				->title('Включено')
    			->sendTrueOrFalse(),
			Picture::make('photo')
				->title('Фото')
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
			TD::make('name')->width('300px'),
			TD::make('active')->width('300px')
				->render(function ($model) {
					return $model->active ? 'Включено' : 'Выключено';
				}),
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
