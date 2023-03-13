<?php

namespace App\Admin\Controllers;

use App\Models\CustomerTask;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\Task;
use Snap\Admin\Http\Controllers\ResourceModuleController;

class TaskController extends ResourceModuleController
{
    public function index()
    {
        return $this->table();
    }

    public function afterSave($resource, $request)
    {
        $task      = Task::find($resource['id']);

        if ($request['_method'] === 'POST') {
            $customers = Customer::all();

            foreach ($customers as $customer) {
                $task->issueTask($customer);
            }
        } elseif ($request['_method'] === 'PATCH') {
            $customerTasks = CustomerTask::where('task_id', $task->id)->get();

            foreach($customerTasks as $customerTask){
                $customerTask->title = $task->title;
                $customerTask->description = $task->description;
                $customerTask->category_id = $task->category_id;
                $customerTask->save();
            }
        }
    }
}
