<?php

namespace App\Http\Controllers;

use App\Contracts\FileInterface;
use App\Dto\GeneratePathFileDTO;
use App\Http\Requests\File\StoreFileRequest;
use App\Models\File;
use App\Traits\QueryBuilderTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileController extends Controller
{
    use QueryBuilderTrait;

    protected mixed $modelClass = File::class;

    public function __construct(private readonly FileInterface $fileRepository) {}

    public function index(Request $request): JsonResponse
    {
        $query = $this->getQuery($request);
        $data  = $query->paginate($request->get('per_page', 20));

        return okWithPaginateResponse($data);
    }

    public function store(StoreFileRequest $request): JsonResponse
    {
        $maxKb = (int) config('filemanager.max_size_kb', 20480);
        $request->validate([
            'files'   => ['required'],
            'files.*' => ['file', "max:{$maxKb}"],
        ]);

        $files = $request->file('files');

        if (is_array($files)) {
            foreach ($files as $file) {
                if (! $this->isAllowedFile($file)) {
                    return badRequestResponse(
                        "Noma'lum fayl kengaytmasi (ext: '{$file->getClientOriginalExtension()}', mime: '{$file->getMimeType()}')"
                    );
                }
            }

            $response = [];
            foreach ($files as $file) {
                $dto       = new GeneratePathFileDTO;
                $dto->file = $file;
                $response[] = $this->fileRepository->uploadFile($dto);
            }
        } else {
            if (! $this->isAllowedFile($files)) {
                return badRequestResponse(
                    "Noma'lum fayl kengaytmasi (ext: '{$files->getClientOriginalExtension()}', mime: '{$files->getMimeType()}')"
                );
            }

            $dto       = new GeneratePathFileDTO;
            $dto->file = $files;
            $response  = $this->fileRepository->uploadFile($dto);
        }

        return okResponse($response);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $query = $this->getQuery($request);
        $model = $query->where('slug', $slug)->firstOrFail();

        return okResponse($model);
    }

    public function downloadFile(Request $request, string $slug)
    {
        $file = File::query()->where('slug', $slug)->firstOrFail();
        $path = $file->getDist();

        if ($file->isImage()) {
            $mime = 'image/' . ($file->ext === 'jpg' ? 'jpeg' : $file->ext);

            return response()->file($path, ['Content-Type' => $mime]);
        }

        return response()->download($path, $file->name);
    }

    public function destroy(File $file): JsonResponse
    {
        $file->delete();

        return okResponse(null, 'Deleted successfully.');
    }

    private function isAllowedFile(\Illuminate\Http\UploadedFile $file): bool
    {
        $ext        = strtolower($file->getClientOriginalExtension());
        $extMimeMap = config('filemanager.ext_mime_map', []);

        if (! array_key_exists($ext, $extMimeMap)) {
            return false;
        }

        $extensionOnly = ['doc', 'docx', 'xls', 'xlsx', 'pdf'];
        if (in_array($ext, $extensionOnly, true)) {
            return true;
        }

        return in_array($file->getMimeType(), $extMimeMap[$ext]);
    }
}
