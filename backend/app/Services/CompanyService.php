<?php

namespace App\Services;

use App\Repositories\Eloquent\CompaniesRepository;

class CompanyService
{
    protected $companyRepository;
    function __construct(CompaniesRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index($request)
    {
        $payload = $this->companyRepository->findAll($request->all());
        $fileType = request()->query('filetype');
        if($fileType && $fileType == 'csv') {
            $this->csv($payload);
        } else {
            return $payload;
        }
    }

    public function store($request)
    {
        $payload = $this->companyRepository->create($request);
        return $payload;
    }

    public function show($id)
    {
        $payload = $this->companyRepository->findById($id);
        return $payload;
    }

    public function update($id, $request)
    {
        $attribute = $request->only(['name','email','website', 'logo']);
        $payload = $this->companyRepository->update($id, $attribute);
        return $payload;
    }

    public function destroy($id)
    {
        $payload = $this->companyRepository->destroy($id);
        return $payload;
    }

    public function findAllAutocomplete($request)
    {
        $payload = $this->companyRepository->findAllAutocomplete($request);
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
