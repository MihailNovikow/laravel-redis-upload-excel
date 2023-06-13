<?php

namespace App\Dto;

use App\Models\Row;

class ApiRowDto implements exDto
{

    const apiDateFormat = 'd.m.Y';

    public function __construct(
        public Row $row
    ) {}

    public function do(): mixed
    {
        return [
            'id' => $this->row->external_id,
            'name' => $this->row->name,
            'date' => $this->row->date->format(self::apiDateFormat)
        ];
    }


}
