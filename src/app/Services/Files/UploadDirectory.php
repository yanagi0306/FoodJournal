<?php
namespace App\Services\Files;

class UploadDirectory
{
    private string $path;

    public function __construct(string $type, int $companyId)
    {
        $this->path = "/data/upload_BK/{$type}/{$companyId}";

    }

    public function getPath(): string
    {
        return $this->path;
    }
}

