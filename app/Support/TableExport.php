<?php

namespace App\Support;

class TableExport
{
    public static function excel(string $filename, string $title, array $headers, array $rows)
    {
        $html = '<html><head><meta charset="UTF-8"></head><body>';
        $html .= '<h2>' . e($title) . '</h2>';
        $html .= '<table border="1"><thead><tr>';

        foreach ($headers as $header) {
            $html .= '<th>' . e($header) . '</th>';
        }

        $html .= '</tr></thead><tbody>';

        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $value) {
                $html .= '<td style="mso-number-format:\'\\@\';">' . e($value) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public static function pdf(string $filename, string $title, array $headers, array $rows)
    {
        return response(SimplePdf::table($title, $headers, $rows), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
