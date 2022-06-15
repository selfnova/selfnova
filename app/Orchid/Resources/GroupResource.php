<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Picture;
use Orchid\Crud\Filters\DefaultSorted;
use App\Orchid\Filters\SearchIdFilter;

class GroupResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Group::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
			CheckBox::make('verify')
				->title('Верифицирован')
    			->sendTrueOrFalse(),
			CheckBox::make('is_ban')
				->title('Заблокирован')
    			->sendTrueOrFalse(),
			Input::make('name')->title('Name'),
			Input::make('alias')->title('Короткая ссылка'),
			TextArea::make('about')->rows(5)->title('О группе'),
			Input::make('country')->title('Страна'),
			Input::make('city')->title('Город'),
			Input::make('site')->title('Сайт'),
			Input::make('address')->title('Адрес'),
			Input::make('phone')->title('Телефон'),
			Picture::make('avatar'),
			Select::make('service')
				->options([
					'1'   => 'Нет',
					'2' => 'Включены',
					'3' => 'Включены главным блоком',
				])
				->title('Товары'),
			TextArea::make('adm_comment')->rows(5)->title('Комментарий'),

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
