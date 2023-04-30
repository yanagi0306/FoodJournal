<?php

namespace App\Services\Aspit\Validators;

use App\Contracts\CsvValidatorInterface;
use Illuminate\Validation\Factory as ValidationFactory;

class AspitCsvValidator implements CsvValidatorInterface
{
    protected ValidationFactory $validationFactory;

    protected array $columnValidationRules = [
        'column1' => 'required|string|max:255',
        'column2' => 'required|integer',
        'column3' => 'required|date',
        // 他のカラムに対するルールもここに追加してください
    ];

    public function __construct(ValidationFactory $validationFactory)
    {
        $this->validationFactory = $validationFactory;
    }

    /**
     * CSVデータの検証を行う
     *
     * @param array $csvData
     * @return bool
     */
    public function validate(array $csvData): bool
    {
        foreach ($csvData as $row) {
            $validationResult = $this->validateRow($row);

            if (!$validationResult) {
                return false;
            }
        }

        return true;
    }

    /**
     * CSVの行データを検証する
     *
     * @param array $row
     * @return bool
     */
    protected function validateRow(array $row): bool
    {
        $rowData = array_combine(array_keys($this->columnValidationRules), $row);

        $validator = $this->validationFactory->make($rowData, $this->columnValidationRules);

        if ($validator->fails()) {
            return false;
        }

        return true;
    }
}

