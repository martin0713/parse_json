<?php

namespace App\Services;

class ParseService
{
    private function getData(): string
    {
        return file_get_contents(storage_path('raileurope_original.json'));
    }
    public function all(): string
    {
        $json = $this->getData();
        return $json;
    }
}
