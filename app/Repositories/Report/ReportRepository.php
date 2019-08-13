<?php
namespace App\Repositories\Report;

use App\Models\Report;

Class ReportRepository {
    protected $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function getAllReport(){
        $reports = $this->report->all();
        return $reports;
    }

    public function countReport(){
        $reports = $this->report->all();
        return $reports;
    }

    public function addReport($data){
        $insert_data = [
            'ip_address' => $data['ip_address'],
            'id_motelroom' => $data['id_motelroom'],
            'status' => $data['status'],
        ];
        $this->report->create($insert_data);
    }
}
