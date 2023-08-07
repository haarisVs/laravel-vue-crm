<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCompanyRequest;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(Request $request)
    {
        $payload = $this->companyService->index($request);
        return response()->json($payload);
    }

    public function store(CreateCompanyRequest $request)
    {
        $response = $this->companyService->store($request->validated());
        return response()->json($response);
    }

    public function show($id)
    {
        $payload = $this->companyService->show($id);
        return response()->json($payload);
    }

    public function update(Request $request, $id)
    {
        $response = $this->companyService->update($id, $request);
        return response()->json($response);
    }

    public function destroy($id)
    {
        $this->companyService->destroy($id);
    }

    public function findAllAutocomplete(Request $request)
    {
        $payload = $this->companyService->findAllAutocomplete($request->only(['query', 'limit']));
        return response()->json($payload);
    }
}
