<?php

namespace App\Services;

use App\Contracts\DocumentServiceInterface;
use App\Models\Document;
use App\Models\DocumentTemplate;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentService implements DocumentServiceInterface
{
    public function generateFromTemplate(DocumentTemplate $template, Document $document): array
    {
        $templatePath = storage_path('app/' . $template->file_path);

        if (!file_exists($templatePath)) {
            $templatePath = $this->createBlankTemplate($template);
        }

        $processor = new TemplateProcessor($templatePath);
        $processor->setValue('document_number',  $document->document_number  ?? '');
        $processor->setValue('short_description', $document->short_description ?? '');
        $processor->setValue('date',              now()->format('d.m.Y'));
        $processor->setValue('user_name',         $document->user->name        ?? '');
        $processor->setValue('organization',      $document->user->organization ?? '');

        $fileName = Str::slug($template->name) . '_' . $document->id . '_' . time() . '.docx';
        $filePath = 'documents/' . $document->user_id . '/' . $fileName;
        $fullPath = storage_path('app/' . $filePath);

        $this->ensureDirectory(dirname($fullPath));

        $processor->saveAs($fullPath);

        return ['file_path' => $filePath, 'file_name' => $fileName];
    }

    public function updateMainFile(Document $document, string $filePath, string $fileName): void
    {
        $this->deleteFileIfDifferent($document->main_file_path, $filePath);

        $document->update([
            'main_file_path' => $filePath,
            'main_file_name' => $fileName,
        ]);
    }

    private function createBlankTemplate(DocumentTemplate $template): string
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Hujjat raqami: ${document_number}');
        $section->addText('Sana: ${date}');
        $section->addText('Qisqa tavsif: ${short_description}');
        $section->addText('Tuzuvchi: ${user_name}');
        $section->addText('Tashkilot: ${organization}');

        $dir  = storage_path('app/templates');
        $this->ensureDirectory($dir);

        $path   = $dir . '/' . Str::slug($template->name) . '.docx';
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return $path;
    }

    private function deleteFileIfDifferent(?string $existing, string $new): void
    {
        if ($existing && $existing !== $new) {
            $full = storage_path('app/' . $existing);
            if (file_exists($full)) {
                @unlink($full);
            }
        }
    }

    private function ensureDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
