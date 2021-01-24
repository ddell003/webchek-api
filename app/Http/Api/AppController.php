<?php


namespace App\Http\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\AppRequest;
use App\Models\App;
use App\Services\TestService;
use Illuminate\Support\Facades\Response;

class AppController extends Controller
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
    public function index()
    {
        return $this->testService->getApps(request()->input());
    }

    /**
     * Returns the specified App
     * @param Test $Test
     * @return mixed
     */
    public function show(App $app)
    {
        return $this->testService->getApp($app->id);
    }

    /**
     * Create an App
     * @param AppRequest $request
     * @return mixed
     */
    public function store(AppRequest $request)
    {
        return $this->testService->createApp($request->all());
    }

    /**
     * Update a Test
     * @param AppRequest $request
     * @param App $app
     * @return mixed
     */
    public function update(AppRequest $request, App $app)
    {
        return $this->testService->updateApp($app->id, $request->all());
    }

    /**
     * Delete the specified Test
     * @param App $app
     * @return mixed
     */
    public function destroy(App $app)
    {
        $this->testService->deleteApp($app->id);
        return Response::json([], 204);
    }
}
