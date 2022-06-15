<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\User;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Crud\Filters\DefaultSorted;
use Orchid\Screen\Fields\Picture;
use App\Orchid\Filters\SearchFilter;
use App\Orchid\Filters\SearchIdFilter;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Crud\Filters\WithTrashed;

class UserResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = User::class;



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
			Input::make('name')->title('Имя'),
			Input::make('last_name')->title('Фамилия'),
			Input::make('alias')->title('Короткая ссылка'),
			TextArea::make('about')->rows(5)->title('О себе'),
			DateTimer::make('born')->type('date')->title('Рождение'),
			Input::make('country')->title('Страна'),
			Input::make('city')->title('Город'),
			Input::make('site')->title('Сайт'),
			Input::make('email')->title('E-mail'),
			Picture::make('avatar'),
			Select::make('photoblog')
				->options([
					'1'   => 'Нет',
					'2' => 'Включены',
					'3' => 'Включены главным блоком',
				])
				->title('Фотоблог'),
			TextArea::make('adm_comment')->rows(5)->title('Комментарий'),
			Input::make('deleted_at')->title('Удален в'),
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
			TD::make('full_name', 'ФИО'),
			TD::make('born', 'Дата рождения'),
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
			new SearchFilter(),
			WithTrashed::class
		];
    }
}
