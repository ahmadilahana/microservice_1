<?php

namespace App\Http\Controllers;

use App\Http\Requests\TasksRequest;
use App\Models\TaskModel;
use Helper\ResponseHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskManagementController extends Controller
{
    public function getTasks(Request $request) {
        $data = TaskModel::query()
                ->where(function (Builder $query) use ($request) {
                    if ($request->title) $query->orWhere('title', 'like', '%'.$request->title.'%');
                    if ($request->status) $query->orWhere('status', $request->status);
                    if ($request->created_by) $query->orWhere('created_by', $request->created_by);
                })
                ->with("user")
                ->when($request->sortBy, function (Builder $query) use ($request) {
                    $query->orderBy($request->sortBy, $request->dir ?? "desc");
                })
                ->paginate($request->size ?? 10);
        
        return ResponseHelper::SuccessReponse($data, true, "Tasks berhasil ditemukan", "GET_TASKS_SUCCESS");
    }

    public function createTasks(TasksRequest $request) {

        $tokenUser = json_decode($request->input("TokenData"));

        $tasks = TaskModel::create([
            "title" => $request->input("title"),
            "description" => $request->input("description"),
            "status" => $request->input("status"),
            "created_by" => $tokenUser->id,
        ]);
        if ($tasks) {
            $tasks->load("user");
            return ResponseHelper::SuccessReponse($tasks, true, "Tasks Berhasil Ditambahkan", "ADD_TASKS_SUCCESS");
        }else {
            return ResponseHelper::BadRequestResponse("ADD_TASKS_FAILED", false, "Gagal menambahkan tasks");
        }
    }

    public function detailTasks(TaskModel $task) {
        $task->load("user");
        return ResponseHelper::SuccessReponse($task, true, "Tasks berhasil ditemukan", "GET_TASKS_SUCCESS");
    }

    public function updateTasks(TasksRequest $request, TaskModel $task) {
        $task->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        $task->load("user");
        return ResponseHelper::SuccessReponse($task, true, "Tasks berhasil diubah", "UPDATE_TASKS_SUCCESS");
    }

    public function deleteTasks(TaskModel $task) {
        if ($task->trashed()) {
            //
        }
        $task->delete();
        return ResponseHelper::SuccessReponse(null, true, "Tasks berhasil dihapus", "DELETE_TASKS_SUCCESS");
    }
}
