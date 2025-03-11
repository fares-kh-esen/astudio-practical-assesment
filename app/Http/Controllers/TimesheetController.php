<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Project;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class TimesheetController  extends Controller
{
      /**
     * Get all timesheets.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $timesheets = Timesheet::with(['user', 'project'])->get();

        return response()->json($timesheets);
    }


    public function show($id)
    {
        $timesheet = Timesheet::with(['user', 'project'])->find($id);

        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }

        return response()->json($timesheet);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|string|max:255',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();

            $timesheet = Timesheet::create([
                'task_name' => $request->task_name,
                'date' => $request->date,
                'hours' => $request->hours,
                'user_id' => $request->user_id,
                'project_id' => $request->project_id,
            ]);

            $timesheet->load(['user', 'project']);

            DB::commit();

            return response()->json($timesheet, 201);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to create timesheet', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a timesheet.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'hours' => 'sometimes|numeric|min:0',
            'user_id' => 'sometimes|exists:users,id',
            'project_id' => 'sometimes|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $timesheet = Timesheet::find($id);

        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }

        try {
            DB::beginTransaction();

            $timesheet->update($request->only(['task_name', 'date', 'hours', 'user_id', 'project_id']));

            $timesheet->load(['user', 'project']);

            DB::commit();

            return response()->json($timesheet);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to update timesheet', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a timesheet.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $timesheet = Timesheet::find($id);

        if (!$timesheet) {
            return response()->json(['message' => 'Timesheet not found'], 404);
        }

        try {
            DB::beginTransaction();

            $timesheet->delete();

            DB::commit();

            return response()->json(['message' => 'Timesheet deleted successfully']);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete timesheet', 'error' => $e->getMessage()], 500);
        }
    }
}
