<?php

namespace App\Contracts;

use App\Dto\GeneratePathFileDTO;
use App\Models\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface FileInterface
{
    public function uploadFile(GeneratePathFileDTO $dto): File;

    public function downloadFile(Request $request, string $slug): BinaryFileResponse;
}
