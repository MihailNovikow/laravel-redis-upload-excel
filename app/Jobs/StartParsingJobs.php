<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class StartParsingJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $path
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rows = FastExcel::import($this->path, function ($line) {
            return $line;
        });
        $chunks = $rows->chunk(1000);
        $rowsCount = count($rows);
        foreach ($chunks as $chunk) {
            $key = md5($this->path);
            $chunkArr = $chunk->toArray();
            Log::info('Парсинг 1000 элементов из "' . $rowsCount . '" ');
            dispatch(new ParsingExcel($chunkArr, $key));
        };
    }
}
