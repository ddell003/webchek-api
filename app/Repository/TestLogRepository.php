<?php


namespace App\Repository;


use App\Models\TestLog;

class TestLogRepository extends EloquentRepository
{
    protected function getModel()
    {
        return new TestLog();
    }

    public function create($data)
    {
        //set all to not latest
        $builder = $this->getModel()->newQuery();
        $builder->where("test_id",  "=", $data["test_id"]);
        $builder->update(["latest"=>0]);
        $createdItem = $this->getModel()->create($data);
        $item = $this->getById($createdItem->id);
        $item->wasRecentlyCreated = true;
        return $item;
    }

    public function getTestLogs($test_id)
    {
        $builder = $this->getModel()->newQuery();
        $builder->where("test_id", "=", $test_id);
        $builder->orderBy("created_at", "desc");
        return $builder->get();
    }
}

