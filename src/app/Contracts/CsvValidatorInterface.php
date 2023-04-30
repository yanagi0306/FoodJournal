<?php

namespace App\Contracts;

interface CsvValidatorInterface
{
    /**
     * CSVデータの検証を行う
     *
     * @param array $csvData
     * @return bool
     */
    public function validate(array $csvData): bool;
}

