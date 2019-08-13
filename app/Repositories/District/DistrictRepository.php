<?php
namespace App\Repositories\District;

use App\Models\District;

Class DistrictRepository {
    protected $district;

    public function __construct(District $district)
    {
        $this->district = $district;
    }

    public function getAllDistrict(){
        $districts = $this->district->all();
        return $districts;
    }
}
