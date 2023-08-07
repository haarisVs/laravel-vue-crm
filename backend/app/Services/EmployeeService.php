<?php

namespace App\Services;

use App\Repositories\Eloquent\EmployeesRepository;

class EmployeeService
{
    protected $employeeRepository;
    function __construct(EmployeesRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function index($request)
    {
        $payload = $this->employeeRepository->findAll($request->all());
        $fileType = request()->query('filetype');
        if($fileType && $fileType == 'csv') {
            $this->csv($payload);
        } else {
            return $payload;
        }
    }

    public function store($request, $currentUser)
    {
        $payload = $this->employeeRepository->create($request, $currentUser);
        return $payload;
    }

    public function show($id)
    {
        $payload = $this->employeeRepository->findById($id);
        return $payload;
    }

    public function update($id, $request)
    {
        $attribute = $request->only(['first_name','last_name','company_id','email','phone']);
        $payload = $this->employeeRepository->update($id, $attribute);
        return $payload;
    }

    public function destroy($id)
    {
        $payload = $this->employeeRepository->destroy($id);
        return $payload;
    }

    public function csv($payload = array())
    {
        header("Content-type: text/csv");
        header("Cache-Control: no-store, no-cache");
        header('Content-Disposition: attachment; filename="company.csv"');
        $rows = $payload['rows'];
        $fields = array('id','Name','Address','Phone','Email','Website','Logo','Created At','Updated At');

        $f = fopen('php://output', 'w');

        fputcsv($f, $fields);

        foreach($rows as $row)
        {
            fputcsv($f, array($row['id'],$row['name'],$row['address'],$row['phone'],$row['email'],$row['website'],$row['logo'],$row['created_at'],$row['updated_at']));
        }

        fclose($f);
    }

}
