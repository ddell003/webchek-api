<?php


namespace App\Http\Api;


use App\Http\Requests\TestRequest;
use App\Models\App;
use App\Models\Test;
use App\Services\TestService;
use Illuminate\Support\Facades\Response;

class TestController
{
    private $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    /**
     * Returns all non deleted Tests
     * @return mixed
     */
    public function index(App $app)
    {
        return $this->testService->getTests($app->id, request()->input());
    }

    /**
     * Returns the specified Test
     * @param Test $Test
     * @return mixed
     */
    public function show(App $app, Test $test)
    {
       return $this->testService->getTest($test->id);
    }

    /**
     * Create a Test
     * @param TestRequest $request
     * @return mixed
     */
    public function store(TestRequest $request, App $app)
    {
        return $this->testService->createTest($request->all());
    }

    /**
     * Update a Test
     * @param TestRequest $request
     * @param Test $test
     * @return mixed
     */
    public function update(TestRequest $request, App $app, Test $test)
    {
        return $this->testService->updateTest($test->id, $request->all());
    }

    /**
     * Delete the specified Test
     * @param Test $test
     * @return mixed
     */
    public function destroy(App $app, Test $test)
    {
        $this->testService->deleteTest($test->id);
        return Response::json([], 204);
    }
}
