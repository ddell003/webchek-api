<?php


namespace App\Http\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\TestLogRequest;
use App\Http\Requests\TestRequest;
use App\Models\App;
use App\Models\Test;
use App\Models\TestLog;
use App\Services\TestService;
use Illuminate\Support\Facades\Response;

class TestLogController extends Controller
{
    private $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function index(App $app, Test $test)
    {
        return $this->testService->getTestLogs($test->id, request()->input());
    }

    public function show(App $app, Test $test, TestLog $log)
    {
        return $this->testService->getTestLog($log->id);
    }

    public function store(TestLogRequest $request, App $app, Test $test)
    {
        return $this->testService->createTestLog($request->all());
    }

    public function update(TestRequest $request, App $app, Test $test, TestLog $log)
    {
        return $this->testService->updateTestLog($log->id, $request->all());
    }

    /**
     * Delete the specified Test
     * @param Test $test
     * @return mixed
     */
    public function destroy(App $app, Test $test, TestLog $log)
    {
        $this->testService->deleteTestLog($log->id);
        return Response::json([], 204);
    }
}
