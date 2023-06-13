<?php

namespace App\Dto;

class ParsingDto implements exDto
{

    public function __construct(
        public array $rawRow
    ) {}

    public function do(): mixed {
        return [
            'external_id' => $this->rawRow['id'],
            'name' => $this->rawRow['name'],
            'date' => $this->rawRow['date']->format(ApiRowDto::apiDateFormat)
        ];
    }

}
