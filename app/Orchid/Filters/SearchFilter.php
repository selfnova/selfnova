<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;

class SearchFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'search', 'id'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Search');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
		$query = $builder;

		if ( $this->request->get('search') ) {
			$query->whereRaw(
				"CONCAT( LOWER(`name`), ' ', LOWER(`last_name`) ) REGEXP ?",
				[strtolower($this->request->get('search'))]
			);
		}

		if ( $this->request->get('id') ) {
			$query = $query->where('id', $this->request->get('id'));
		}

        return $query;
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
			Input::make('id')
                ->value($this->request->get('id'))
                ->title(__('ID')),
            Input::make('search')
                ->value($this->request->get('search'))
                ->title(__('Имя и фамилия')),
        ];
    }
}
