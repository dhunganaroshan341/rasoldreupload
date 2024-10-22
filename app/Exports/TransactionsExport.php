<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TransactionsExport implements FromArray, WithEvents, WithHeadings
{
    protected $data;

    protected $maxRows;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $data = [];

        // Add Opening Balance
        if (isset($this->data['OpeningBalance'])) {
            $data[] = ['Opening Balance', '', $this->data['OpeningBalance'], '', '', '', '', ''];
        }

        // Add Income and Expense labels (this should be the second row)
        $data[] = ['', 'Income', '', '', '', 'Expense', '', ''];

        // Add headers (this should be the first row)
        $data[] = ['Date', 'Source', 'Amount', 'Medium', 'Date', 'Source', 'Amount', 'Medium'];

        // Add Income and Expense data
        $maxRows = max(count($this->data['Income']), count($this->data['Expense']));
        $this->maxRows = $maxRows;
        for ($i = 0; $i < $maxRows; $i++) {
            $income = $this->data['Income'][$i] ?? ['-', '-', '0', '-'];
            $expense = $this->data['Expense'][$i] ?? ['-', '-', '0', '-'];

            $data[] = [
                $income[1], $income[0], $income[2], $income[3], // Date, Source, Amount, Medium for Income
                $expense[1], $expense[0], $expense[2], $expense[3], // Date, Source, Amount, Medium for Expense
            ];
        }

        // Add summary data at the end
        // foreach ($this->data['Summary'] as $summaryRow) {
        //     $data[] = array_merge($summaryRow, ['', '', '', '', '', '']);
        // }

        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $totalRows = count($this->array()); // Total rows of data

                // Style for headers
                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                // Apply styles to headers (first row and second row)
                $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
                $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);

                // Adjust column widths
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Define border style
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                // Apply border style to the data range
                $sheet->getStyle('A1:H'.$totalRows)->applyFromArray($borderStyle);

                // Define custom border style for the right border of column D
                $customBorderStyle = [
                    'borders' => [
                        'right' => [
                            'borderStyle' => Border::BORDER_DOUBLE,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                // Apply custom border style to the right border of column D
                $sheet->getStyle('D1:D'.$totalRows)->applyFromArray($customBorderStyle);

                // Define top border style for the second row
                $topBorderStyle = [
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                // Apply top border style to the second row
                $sheet->getStyle('A2:H2')->applyFromArray($topBorderStyle);

                // Define horizontal border style for the end of data
                $horizontalBorderStyle = [
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                // Apply horizontal border style to the end of data
                $sheet->getStyle('A'.$totalRows.':H'.$totalRows)->applyFromArray($horizontalBorderStyle);

                // Add formulas for totals and balances at the end of columns A, B, and C
                $totalIncomeRow = $totalRows + 1;
                $totalExpenseRow = $totalRows + 2;
                $openingBalanceRow = $totalRows + 3;
                $finalBalanceRow = $totalRows + 4;

                // Add Total Income
                $sheet->setCellValue('A'.$totalIncomeRow, 'Total Income');
                $sheet->setCellValue('C'.$totalIncomeRow, '=SUM(C2:C'.$totalRows.')'); // Total Income in column C

                // Add Total Expense
                $sheet->setCellValue('A'.$totalExpenseRow, 'Total Expense');
                $sheet->setCellValue('G'.$totalExpenseRow, '=SUM(G2:G'.$totalRows.')'); // Total Expense in column G

                // Find the row with 'Opening Balance' title
                $openingBalanceRowIndex = $totalRows; // Default to last row

                for ($rowIndex = 2; $rowIndex <= $totalRows; $rowIndex++) {
                    if ($sheet->getCell('B'.$rowIndex)->getValue() === 'Opening Balance') {
                        $openingBalanceRowIndex = $rowIndex;
                        break;
                    }
                }

                // Insert Opening Balance and Final Balance
                $sheet->setCellValue('A'.$openingBalanceRow, 'Opening Balance');
                $sheet->setCellValue('C'.$openingBalanceRow, '=C'.$openingBalanceRowIndex); // Opening Balance in column C

                $sheet->setCellValue('A'.$finalBalanceRow, 'Final Balance');
                $sheet->setCellValue('C'.$finalBalanceRow, '=C'.$openingBalanceRow.' + C'.$totalIncomeRow.' - G'.$totalExpenseRow); // Final Balance in column C

                // Apply styles to cells with formulas
                $sheet->getStyle('A'.$totalIncomeRow.':C'.($finalBalanceRow))->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
