<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateRequest;
use App\Services\TemplateService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TemplateApiController extends Controller
{
    public function __construct(protected TemplateService $service)
    {
    }

    public function index(Request $request)
    {
        return $this->sendJson($this->service->getAll([
            'account_id' => $request->user()->account_id
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(TemplateRequest $request)
    {
        $data = array_merge(
            $request->validated(),
            ['account_id' => $request->user()->account_id]
        );
        return $this->sendJson(
            $this->service->create($data),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
