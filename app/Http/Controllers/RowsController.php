<?php

namespace App\Http\Controllers;

use App\Dto\ApiRowDto;
use App\Jobs\StartParsingJobs;
use App\Models\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class RowsController extends Controller
{

    public function get(): array
    {
        return Cache::rememberForever('rows_show', function () {
            return Row::all()->map(function (Row $row) {
                return (new ApiRowDto($row))->do();
            })->groupBy('date')->toArray();
        });
    }

    public function parse(Request $request) {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx'
            ]);
            $excelFile = $request->file('file');
            $tmpFilePath = storage_path('/app/resource/' . $excelFile->storeAs(md5($excelFile->getPathname()) . '.xlsx', ['disk' => 'resource']));
            dispatch((new StartParsingJobs($tmpFilePath)));
            return response(['status' => 'ok', 'message' => 'put in queue']);
        } catch (ValidationException $e) {
            $message = $e->getMessage();
            return response(['status' => 'error', 'message' => $message], 403);
        }
    }

}
