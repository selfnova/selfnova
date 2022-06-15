<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;

class ReportFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'type', 'id'
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

		if ( $this->request->get('type') ) {
			$query = $query->where('type', $this->request->get('type'));
		}

		if ( $this->request->get('id') ) {
			$query = $query->where('type_id', $this->request->get('id'));
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
            Input::make('type')
                ->value($this->request->get('type'))
                ->title(__('Тип жалобы')),
        ];
    }
}
