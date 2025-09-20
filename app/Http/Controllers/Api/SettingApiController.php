<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SettingApiController extends Controller
{
    public function __construct(
        private readonly SettingService $service
    ) {

    }
    public function index(Request $request)
    {
        return $this->sendJson($this->service->getAll([
            'account_id' => $request->user()->account_id
        ]));
    }

    public function store(SettingRequest $request)
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

    public function update(Request $request, string $id)
    {
        return $this->sendJson(
            $this->service->updatePartial($id, $request->all())
        );
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
