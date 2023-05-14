<?php

namespace App\Services\Files;

use App\Constants\Common;

class UploadDirectory
{
    private string $path;

    public function __construct(string $type, int $companyId)
    {
        $this->path = Common::DATA_DIR . "upload_BK/{$type}/{$companyId}";

    }

    public function getPath(): string
    {
        return $this->path;
    }
}

