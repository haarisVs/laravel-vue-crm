<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function index(Request $request)
    {
        $payload = $this->employeeService->index($request);
        return response()->json($payload);
    }

    public function store(CreateEmployeeRequest $request)
    {
        $response = $this->employeeService->store($request->validated(),  auth()->user());
        return response()->json($response);
    }

    public function show($id)
    {
        $payload = $this->employeeService->show($id);
        return response()->json($payload);
    }

    public function update(Request $request, $id)
    {
        $response = $this->employeeService->update($id, $request);
        return response()->json($response);
    }

    public function destroy($id)
    {
        $this->employeeService->destroy($id);
    }
}
