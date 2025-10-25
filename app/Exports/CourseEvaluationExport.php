<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CourseEvaluationExport implements FromArray, WithHeadings, WithEvents
{
    protected $rows;
    protected $courseTitle;
    protected $instructorName;
    protected $questions;

    public function __construct(array $rows, string $courseTitle, string $instructorName, array $questions)
    {
        $this->rows = $rows;
        $this->courseTitle = $courseTitle;
        $this->instructorName = $instructorName;
        $this->questions = $questions;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            ["Course: " . $this->courseTitle],        // Row 1
            ["Instructor: " . $this->instructorName], // Row 2
            [""],                                     // Row 3 empty
            array_merge(["Submitted At", "User"], $this->questions) // Row 4 table headings
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $totalColumns = count($this->questions) + 2; // +2 for 'Submitted At' and 'User'

                // Merge first row for Course name
                $sheet->mergeCellsByColumnAndRow(1, 1, $totalColumns, 1);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Merge second row for Instructor
                $sheet->mergeCellsByColumnAndRow(1, 2, $totalColumns, 2);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Bold table headings
                $sheet->getStyle('A4:' . $sheet->getHighestColumn() . '4')->getFont()->setBold(true);
            }
        ];
    }
}
