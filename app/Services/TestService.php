<?php


namespace App\Services;


use App\Repository\AppRepository;
use App\Repository\TestLogRepository;
use App\Repository\TestRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class TestService
{
    private $testLogRepository;
    private $testRepository;
    private $appRepository;

    private $testOptions = [
        'includes' => [
            'latest',
            "users",
        ]
    ];

    public function __construct(AppRepository $appRepository, TestRepository $testRepository, TestLogRepository $testLogRepository)
    {
        $this->appRepository = $appRepository;
        $this->testRepository = $testRepository;
        $this->testLogRepository = $testLogRepository;
    }

    private function addAuditFields($data)
    {
        $user_id = Auth::user()->id;
        $auditfields = [
            "created_at"=> new Carbon(),
            "updated_at"=> new Carbon(),
            "created_by"=>$user_id,
            "updated_by"=>$user_id,
        ];

        return array_merge($data, $auditfields);
    }

    private function addUpDated($data)
    {
        $user_id = Auth::user()->id;
        $auditfields = [
            "updated_at"=> new Carbon(),
            "updated_by"=>$user_id,
        ];

        return array_merge($data, $auditfields);
    }


    public function getTests($appId, $options = [])
    {
        return $this->testRepository->getWhere("app_id", $appId, $this->testOptions);
    }

    public function getTest($test_id)
    {
        return $this->testRepository->getById($test_id, $this->testOptions);
    }

    public function createTest($data)
    {
        $test =  $this->testRepository->create($this->addAuditFields($data));
        $this->syncUsers($test, Arr::get($data, "users", []));
        $updatedTest = $this->testRepository->getById($test->id, $this->testOptions);
        $updatedTest->wasRecentlyCreated = true;
        return $updatedTest;

    }

    private function syncUsers($test, $users)
    {
        $testUsers = [];
        foreach ($users as $user) {
            $testUsers[$user['id']] = [
                'user_id' => $user['id'],
                'account_id' => Auth::user()->account_id
            ];
        }

        $test->users()->sync($testUsers);
    }

    public function updateTest($test_id, $data)
    {
        $oldTest = $this->testRepository->getById($test_id);
        $this->syncUsers($oldTest, Arr::get($data, "users", []));
        $this->testRepository->update($test_id, $this->addUpDated($data));
        return $this->testRepository->getById($test_id, $this->testOptions);
    }

    public function deleteTest($test_id)
    {
        $this->testRepository->delete($test_id);
    }

    public function getApps($options = [])
    {
        return $this->appRepository->get();
    }

    public function getApp($app_id)
    {
        return $this->appRepository->getById($app_id);
    }

    public function createApp($data)
    {
        return $this->appRepository->create($this->addAuditFields($data));

    }

    public function updateApp($app_id, $data)
    {
        return $this->appRepository->update($app_id, $this->addUpDated($data));
    }

    public function deleteApp($app_id)
    {
        return $this->appRepository->delete($app_id);
    }

    public function getTestLogs($test_id, $options = [])
    {
        return $this->testLogRepository->getTestLogs($test_id);
    }

    public function getTestLog($testLogId)
    {
        return $this->testLogRepository->getById($testLogId);
    }

    public function createTestLog($data)
    {
        $now = new Carbon();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;
        $data['latest'] = 1;

        return $this->testLogRepository->create($data);
    }

    public function updateTestLog($testLogId, $data)
    {

    }

    public function deleteTestLog($testLogId)
    {

    }
}
