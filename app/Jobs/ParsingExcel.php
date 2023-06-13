<?php

namespace App\Jobs;

use App\Dto\ApiRowDto;
use App\Dto\ParsingDto;
use App\Events\RowCreated;
use DateTimeImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ParsingExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * парсинг чанка по 1000 элементов, с инкрементом в редис
     */
    public function __construct(
        public array $rows,
        public string $key
    ) {}

    private static function validRow(array $rawRow): bool
    {
        foreach (['id', 'name', 'date'] as $item) {
            if(!array_key_exists($item, $rawRow)) {
                return false;
            }
        }
        return true;
    }

    public function handle(): void
    {
        $validRows = [];
        foreach ($this->rows as $rawRow) {
            if(self::validRow($rawRow)) { // валидация записи
                $validRows[] = (new ParsingDto($rawRow))->do();
            }
        }
        Cache::increment($this->key, count($validRows));
        DB::table('rows')->insert($validRows);
        foreach ($validRows as $validRow) {
            event(new RowCreated($validRow['external_id']));
        }
        Cache::forget('rows_show');
    }
}
