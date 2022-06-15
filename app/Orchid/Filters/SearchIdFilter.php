<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;

class SearchIdFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'id',
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
        return $builder->where( "id", $this->request->get('id') );
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
        ];
    }
}
