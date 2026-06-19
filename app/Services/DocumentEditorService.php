<?php

namespace App\Services;

use App\Contracts\DocumentEditorServiceInterface;
use App\Models\Document;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Image as ImageStyle;

class DocumentEditorService implements DocumentEditorServiceInterface
{
    private const QR_SIZE_PT = 72; // ~2.54 cm at 72 pt/inch

    public function buildWithQr(Document $document): string
    {
        $docxPath = storage_path('app/' . $document->main_file_path);
        $qrPath   = storage_path('app/' . $document->qr_code_path);
        $position = $document->qr_position;

        abort_unless(file_exists($docxPath), 404, 'Hujjat fayli topilmadi.');
        abort_unless($document->qr_code_path && file_exists($qrPath), 422, 'QR kod fayli topilmadi.');
        abort_unless(
            $position && isset($position['x'], $position['y']),
            422,
            'Avval QR kod pozitsiyasini belgilang.'
        );

        $phpWord  = IOFactory::load($docxPath);
        $sections = $phpWord->getSections();

        $pageIdx = max(0, (int) ($position['page'] ?? 1) - 1);
        $section = $sections[min($pageIdx, count($sections) - 1)];

        [$xPt, $yPt] = $this->resolvePositionPt($section, (float) $position['x'], (float) $position['y']);

        $section->addImage($qrPath, [
            'width'         => self::QR_SIZE_PT,
            'height'        => self::QR_SIZE_PT,
            'pos'           => ImageStyle::POSITION_ABSOLUTE,
            'hPos'          => ImageStyle::POSITION_ABSOLUTE,
            'vPos'          => ImageStyle::POSITION_ABSOLUTE,
            'hPosRelTo'     => ImageStyle::POSITION_RELATIVE_TO_PAGE,
            'vPosRelTo'     => ImageStyle::POSITION_RELATIVE_TO_PAGE,
            'marginLeft'    => $xPt,
            'marginTop'     => $yPt,
            'wrappingStyle' => ImageStyle::WRAPPING_STYLE_INFRONT,
        ]);

        $tmpDir  = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $tmpPath = $tmpDir . '/doc_' . $document->id . '_' . uniqid() . '.docx';
        IOFactory::createWriter($phpWord, 'Word2007')->save($tmpPath);

        return $tmpPath;
    }

    public function extractHtml(Document $document): string
    {
        $docxPath = storage_path('app/' . $document->main_file_path);

        if (!file_exists($docxPath)) {
            return '';
        }

        $phpWord = IOFactory::load($docxPath);
        $tmpFile = tempnam(sys_get_temp_dir(), 'edo_html_') . '.html';

        try {
            IOFactory::createWriter($phpWord, 'HTML')->save($tmpFile);
            $raw = file_get_contents($tmpFile);

            if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $raw, $m)) {
                return trim($m[1]);
            }

            return $raw;
        } finally {
            if (file_exists($tmpFile)) {
                @unlink($tmpFile);
            }
        }
    }

    public function saveFromHtml(Document $document, string $html): void
    {
        $docxPath  = storage_path('app/' . $document->main_file_path);
        $tmpImages = [];

        // Replace base64 data URIs with real temp files so PhpWord can embed images
        $processed = preg_replace_callback(
            '/<img([^>]*?)src="data:image\/([a-zA-Z]+);base64,([^"]+)"([^>]*?)>/i',
            function (array $m) use (&$tmpImages): string {
                $ext  = strtolower($m[2]) === 'jpeg' ? 'jpg' : strtolower($m[2]);
                $tmp  = tempnam(sys_get_temp_dir(), 'edo_img_') . '.' . $ext;
                file_put_contents($tmp, base64_decode($m[3]));
                $tmpImages[] = $tmp;
                return '<img' . $m[1] . 'src="' . $tmp . '"' . $m[4] . '>';
            },
            $html
        );

        // Preserve page geometry from original DOCX
        $sectionStyle = [];
        try {
            $orig     = IOFactory::load($docxPath);
            $sections = $orig->getSections();
            if (!empty($sections)) {
                $style = $sections[0]->getStyle();
                $sectionStyle = [
                    'pageSizeW'    => $style->getPageSizeW(),
                    'pageSizeH'    => $style->getPageSizeH(),
                    'marginLeft'   => $style->getMarginLeft(),
                    'marginRight'  => $style->getMarginRight(),
                    'marginTop'    => $style->getMarginTop(),
                    'marginBottom' => $style->getMarginBottom(),
                ];
            }
        } catch (\Throwable) {
            // Fall back to PhpWord defaults
        }

        $phpWord = new PhpWord();
        $section = $phpWord->addSection($sectionStyle ?: []);

        Html::addHtml($section, $this->toXhtml($processed ?? $html), false, false);

        IOFactory::createWriter($phpWord, 'Word2007')->save($docxPath);

        foreach ($tmpImages as $tmp) {
            @unlink($tmp);
        }
    }

    /**
     * Convert HTML5 to XHTML so PhpWord's DOMDocument::loadXML() can parse it.
     * TinyMCE outputs void elements like <img>, <br> without self-closing slashes,
     * which breaks XML parsing. loadHTML() handles HTML5, saveXML() outputs XHTML.
     */
    private function toXhtml(string $html): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);

        $dom->loadHTML(
            '<?xml encoding="UTF-8"><div id="_edo_wrap">' . $html . '</div>',
            LIBXML_NOWARNING | LIBXML_NOERROR
        );

        libxml_clear_errors();
        libxml_use_internal_errors(false);

        $wrap = $dom->getElementById('_edo_wrap');
        if (!$wrap) {
            return $html;
        }

        $xhtml = '';
        foreach ($wrap->childNodes as $node) {
            $xhtml .= $dom->saveXML($node);
        }

        return $xhtml ?: $html;
    }

    private function resolvePositionPt($section, float $xPercent, float $yPercent): array
    {
        $style        = $section->getStyle();
        $pageWidthPt  = (($style->getPageSizeW() ?: 12240) / 20);
        $pageHeightPt = (($style->getPageSizeH() ?: 15840) / 20);

        return [
            round(($xPercent / 100) * $pageWidthPt, 2),
            round(($yPercent / 100) * $pageHeightPt, 2),
        ];
    }
}
