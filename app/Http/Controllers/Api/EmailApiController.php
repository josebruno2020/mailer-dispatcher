<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailRequest;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailApiController extends Controller
{
    public function __construct(
        private EmailService $service
    ) {
    }

    public function index(Request $request)
    {
        $filters = array_merge(
            $request->only(['status', 'scheduled_at_from', 'scheduled_at_to']),
            ['account_id' => $request->user()->account_id]
        );
        return $this->sendJson($this->service->paginate($filters));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailRequest $request)
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

    public function show(Request $request, string $id)
    {
        return $this->sendJson($this->service->findOrThrow($id, [
            'account_id' => $request->user()->account_id
        ]));
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
        $this->service->deleteById($id);
        return $this->sendJson(null, Response::HTTP_NO_CONTENT);
    }
}
