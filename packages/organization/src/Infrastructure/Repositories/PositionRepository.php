<?php

namespace RedJasmine\Organization\Infrastructure\Repositories;

use RedJasmine\Organization\Domain\Models\Position;
use RedJasmine\Organization\Domain\Repositories\PositionRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PositionRepository extends Repository implements PositionRepositoryInterface
{
    protected static string $modelClass = Position::class;

    /**
     * 根据职位序列查找
     */
    public function findBySequence(string $sequence): \Illuminate\Database\Eloquent\Collection
    {
        return Position::where('sequence', $sequence)->get();
    }

    /**
     * 根据职级查找
     */
    public function findByLevel(int $level): \Illuminate\Database\Eloquent\Collection
    {
        return Position::where('level', $level)->get();
    }
}
