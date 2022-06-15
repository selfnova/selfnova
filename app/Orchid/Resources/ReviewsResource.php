<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\Review;
use App\Models\Comments;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Picture;
use Orchid\Crud\Filters\DefaultSorted;
use Illuminate\Database\Eloquent\Model;
use App\Orchid\Filters\SearchIdFilter;
use Orchid\Screen\Actions\Link;

class ReviewsResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Review::class;

	public function onDelete(Model $model)
	{

		$model->delete();
	}

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

			TextArea::make('text')->rows(20)
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
			TD::make('subject')->width('300px'),
			TD::make('link', 'Ссылка')
				->render(function ($post) {
					return Link::make('Ссылка на пост')
						->href("https://selfnova.com/".($post->g_id ? 'groups' : 'user')."/".($post->g_id ?: $post->u_id)."#post_$post->id");
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
