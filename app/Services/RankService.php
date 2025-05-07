<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class RankService
{
    /**
     * Обновляет rank для указанной модели.
     *
     * @param Model $model Модель, для которой нужно обновить rank
     * @param int $id ID записи
     * @param int $rank Новое значение rank
     * @return int Количество обновленных записей
     */
    // public function updateRank(Model $model, int $id, int $rank): int
    // {
    //     return $model::where('id', $id)->update(['rank' => $rank]);
    // }

    /**
     * Массово обновляет rank для указанной модели.
     *
     * @param Model $model Модель, для которой нужно обновить rank
     * @param array $ids Массив ID записей
     * @param array $ranks Массив значений rank
     * @return int Количество обновленных записей
     */
    public function updateRanks(Model $model, string $idsString): int
    {
        //$idsString// Например: "8,7,5,6,4,3,1,2"

        // Разделяем строку на массив ID
        $ids = explode(',', $idsString);

        $updated = 0;
        // foreach ($ids as $index => $id) {
        //     $updated += $model::where('id', $id)->update(['rank' => $index + 1]);
        // }
        foreach ($ids as $index => $id) {
            $record = $model::find($id); // Получаем модель
            $record->rank = $index + 1;  // Меняем атрибут
            $record->save();             // Вызовет Eloquent-событие updated, для срабатывания Observer
            $updated++;
        }
        return $updated;
    }
}