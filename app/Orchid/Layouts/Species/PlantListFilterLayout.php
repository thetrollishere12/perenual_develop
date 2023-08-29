<?php

namespace App\Orchid\Layouts\Species;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class PlantListFilterLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
        ];
    }
}
