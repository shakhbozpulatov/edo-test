<?php

namespace App\Dto;

use Illuminate\Http\UploadedFile;

class GeneratePathFileDTO
{
    public UploadedFile $file;

    public bool $useFileName = false;
}
