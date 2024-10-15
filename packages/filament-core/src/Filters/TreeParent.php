<?php

namespace RedJasmine\FilamentCore\Filters;

use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class TreeParent extends Filter
{


    protected function setUp() : void
    {
        parent::setUp();

        $this->form([
                        SelectTree::make($this->getName())
                                  ->label(fn() => $this->getLabel())
                                  ->relationship('parent', 'name', 'parent_id')
                                  ->independent(false)
                                  ->enableBranchNode()
                                  ->parentNullValue(0)
                    ]);

        $this->query(function (Builder $query, array $data) {

            return $query->when($data[$this->getName()], function ($query, $categories) {
                $categories = Arr::wrap($categories);
                return $query->whereHas('parent', fn($query) => $query->whereIn('id', $categories));
            });
        });
        $this->indicateUsing(function (array $data) : ?string {
            if (!$data[$this->getName()]) {
                return null;
            }
            $parents = Arr::wrap($data[$this->getName()]);
            return $this->getLabel() . ':' . implode(', ', $this->getTable()->getModel()::where('id', $parents)->get()
                                                                ->pluck('name')->toArray());
        });

    }


}
