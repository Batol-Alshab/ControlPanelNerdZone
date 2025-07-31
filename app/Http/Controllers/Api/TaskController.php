<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Events\reminderToTask;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return $this->errorResponse('قم بتسجيل الدخول اولا', 401);
        }
        try {
            $validateData = $request->validate([
                'content' => 'required|max:700|string',
                'reminder_time' => 'required|date_format:H:i:s',
            ]);
            $task = Task::create([
                'user_id' => $user->id,
                'content' => $request->content,
                'reminder_time' => $request->reminder_time,
            ]);
            if (!$task) {
                return $this->errorResponse('حصل خطأ ما');
            }
            $task = $task->only(['id', 'content', 'is_done', 'reminder_time', 'created_at']);
            return $this->successResponse($task, 'تم اضافة المهمة');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }
    public function update(Request $request, string $id)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return $this->errorResponse('قم بتسجيل الدخول اولا', 401);
        }
        try {
            $validateData = $request->validate([
                'content' => 'nullable|max:700|string',
                'reminder_time' => 'nullable|date_format:H:i:s',
                'is_done' => 'nullable|boolean'
            ]);
            $task = $user->tasks()->where('id', $id)->first();
            if ($task->is_done) {
                return $this->errorResponse('تم انجاز المهمة لا يمكن التعديل عليها');
            }
            if (!$task) {
                return $this->errorResponse(' حصل خطأ ما.. المهمة غير موجودة');
            }
            $task->update($validateData);
            $task = $task->only(['id', 'content', 'is_done', 'reminder_time', 'created_at']);
            return $this->successResponse($task, 'تم تعديل المهمة');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage());
        }
    }
    public function destroy(string $id)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return $this->errorResponse('قم بتسجيل الدخول اولا', 401);
        }
        try {
            $task = Task::find($id);
            if (!$task) {
                return $this->errorResponse(' حصل خطأ ما.. المهمة غير موجودة');
            }

            $task->delete();
            return $this->successResponse('تم حذف المهمة');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage());
        }
    }

    public function getTask()
    {
        $result = [];
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return $this->errorResponse('قم بتسجيل الدخول اولا', 401);
        }
        $tasks =  $user->tasks()->get();
        foreach ($tasks as $task) {
            if ($task->created_at > now()->subHours(24) && $task->is_done == 0)
                $result[] = [
                    $task->only(['id', 'content', 'is_done', 'reminder_time', 'created_at'])
                ];
        }
        return $result;
    }

    public function getEndOris_doneTask()
    {
        $result = [];
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return $this->errorResponse('قم بتسجيل الدخول اولا', 401);
        }
        $tasks =  $user->tasks()->get();
        foreach ($tasks as $task) {
            if ($task->created_at < now()->subHours(24) || $task->is_done == 1)
                $result[] = [
                    $task->only(['id', 'content', 'is_done', 'reminder_time', 'created_at'])
                ];
        }
        return $result;
    }
    public function sendMessage()
    {
        $message = 'hello nerd zone';
        event(new reminderToTask($message));
        
        return 'ok';
    }
}
