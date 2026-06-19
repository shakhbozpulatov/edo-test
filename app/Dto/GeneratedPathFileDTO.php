<?php

namespace App\Dto;

class GeneratedPathFileDTO
{
    public string $file_path;

    public string $file_name;

    public string $file_folder;

    public string $file_ext;

    public ?string $origin_name = null;

    public float $file_size;
}