<?php

namespace App\Support;

class SimplePdf
{
    public static function table(string $title, array $headers, array $rows): string
    {
        $pageWidth = 842;
        $pageHeight = 595;
        $margin = 36;
        $maxChars = 138;
        $widths = self::columnWidths($headers, $rows, $maxChars);
        $headerLine = self::formatRow($headers, $widths);
        $dividerLine = str_repeat('-', strlen($headerLine));
        $rowLines = array_map(fn (array $row) => self::formatRow($row, $widths), $rows);

        if (! $rowLines) {
            $rowLines = ['No records found.'];
        }

        $rowsPerPage = 32;
        $chunks = array_chunk($rowLines, $rowsPerPage);
        $totalPages = count($chunks);
        $contentObjects = [];

        foreach ($chunks as $pageIndex => $chunk) {
            $lines = [
                $title,
                'Generated: ' . now()->format('Y-m-d H:i'),
                '',
                $headerLine,
                $dividerLine,
                ...$chunk,
                '',
                'Page ' . ($pageIndex + 1) . ' of ' . $totalPages,
            ];

            $contentObjects[] = self::contentStream($lines, $pageHeight, $margin);
        }

        return self::document($contentObjects, $pageWidth, $pageHeight);
    }

    private static function columnWidths(array $headers, array $rows, int $maxChars): array
    {
        $widths = array_map(fn ($header) => min(30, max(6, strlen((string) $header))), $headers);

        foreach ($rows as $row) {
            foreach (array_values($row) as $index => $value) {
                $widths[$index] = min(32, max($widths[$index] ?? 6, strlen((string) $value)));
            }
        }

        $separatorWidth = max(0, count($widths) - 1) * 3;
        $available = $maxChars - $separatorWidth;
        $total = array_sum($widths);

        if ($total <= $available) {
            return $widths;
        }

        $scaled = [];
        foreach ($widths as $width) {
            $scaled[] = max(6, (int) floor($width * ($available / $total)));
        }

        while (array_sum($scaled) > $available) {
            $largestIndex = array_keys($scaled, max($scaled), true)[0];
            $scaled[$largestIndex]--;
        }

        return $scaled;
    }

    private static function formatRow(array $row, array $widths): string
    {
        $cells = [];

        foreach (array_values($row) as $index => $value) {
            $text = preg_replace('/\s+/', ' ', trim((string) $value));
            $width = $widths[$index] ?? 12;

            if (strlen($text) > $width) {
                $text = substr($text, 0, max(0, $width - 3)) . '...';
            }

            $cells[] = str_pad($text, $width);
        }

        return implode(' | ', $cells);
    }

    private static function contentStream(array $lines, int $pageHeight, int $margin): string
    {
        $commands = [
            'BT',
            '/F1 16 Tf',
            $margin . ' ' . ($pageHeight - $margin) . ' Td',
            '(' . self::escape($lines[0] ?? '') . ') Tj',
            '/F1 9 Tf',
        ];

        foreach (array_slice($lines, 1) as $line) {
            $commands[] = '0 -14 Td';
            $commands[] = '(' . self::escape($line) . ') Tj';
        }

        $commands[] = 'ET';

        return implode("\n", $commands);
    }

    private static function document(array $contentStreams, int $pageWidth, int $pageHeight): string
    {
        $objects = [];
        $pageObjectNumbers = [];
        $fontObjectNumber = 3;

        foreach ($contentStreams as $index => $stream) {
            $pageObjectNumber = 4 + ($index * 2);
            $contentObjectNumber = $pageObjectNumber + 1;
            $pageObjectNumbers[] = $pageObjectNumber;

            $objects[$pageObjectNumber] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' . $pageWidth . ' ' . $pageHeight . '] /Resources << /Font << /F1 ' . $fontObjectNumber . ' 0 R >> >> /Contents ' . $contentObjectNumber . ' 0 R >>';
            $objects[$contentObjectNumber] = "<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream";
        }

        $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[2] = '<< /Type /Pages /Kids [' . implode(' ', array_map(fn ($number) => $number . ' 0 R', $pageObjectNumbers)) . '] /Count ' . count($pageObjectNumbers) . ' >>';
        $objects[$fontObjectNumber] = '<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>';

        ksort($objects);

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        $maxObjectNumber = max(array_keys($objects));

        for ($number = 1; $number <= $maxObjectNumber; $number++) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . ($objects[$number] ?? '<< >>') . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . ($maxObjectNumber + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($number = 1; $number <= $maxObjectNumber; $number++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$number]);
        }

        return $pdf . "trailer\n<< /Size " . ($maxObjectNumber + 1) . " /Root 1 0 R >>\nstartxref\n" . $xrefOffset . "\n%%EOF";
    }

    private static function escape(string $text): string
    {
        $text = preg_replace('/[^\x20-\x7E]/', '?', $text);

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
