<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $reportType;
    protected $member;
    protected $members;
    protected $dateRange;

    public function __construct($data, $reportType, $member = null, $members = null, $dateRange = null)
    {
        $this->data = $data;
        $this->reportType = $reportType;
        $this->member = $member;
        $this->members = $members;
        $this->dateRange = $dateRange;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->reportType === 'attendance_individual') {
            return collect($this->data);
        } elseif ($this->reportType === 'attendance_all') {
            $flattenedData = [];
            foreach ($this->data as $memberId => $dates) {
                foreach ($dates as $date => $attendance) {
                    $flattenedData[] = [
                        'member_id' => $attendance['id'],
                        'member_name' => $attendance['name'],
                        'date' => $date,
                        'status' => $attendance['status']
                    ];
                }
            }
            return collect($flattenedData);
        }
        
        return collect([]);
    }

    public function headings(): array
    {
        if ($this->reportType === 'attendance_individual') {
            return [
                'Date',
                'Status',
                'Check In Time',
                'Shift'
            ];
        } elseif ($this->reportType === 'attendance_all') {
            return [
                'Member ID',
                'Member Name',
                'Date',
                'Status'
            ];
        }
        
        return [];
    }

    public function map($row): array
    {
        if ($this->reportType === 'attendance_individual') {
            return [
                $row['date'],
                $row['status'],
                $row['check_in_time'] ?? 'N/A',
                $row['shift'] ?? 'N/A'
            ];
        } elseif ($this->reportType === 'attendance_all') {
            return [
                $row['member_id'],
                $row['member_name'],
                $row['date'],
                $row['status']
            ];
        }
        
        return [];
    }
}
