<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Get all projects with their dynamic attributes.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Regular fields filtering
        if ($request->has('filters')) {
            foreach ($request->filters as $field => $item) {
                if (in_array($field, ['name', 'status'])) {
                    $query->where($field, $item['operator'], $item['value']);
                } else {
                    $query->whereHas('attributeValues', function ($q) use ($field, $item) {
                        $q->whereHas('attribute', function ($q) use ($field) {
                            $q->where('name', $field);
                        })->where('value', $item['operator'], $item['value']);
                    });
                }
            }
        }

        $projects = $query->with('attributeValues.attribute')->get();
        return response()->json($projects);
    }

    /**
     * Get a single project by ID with its dynamic attributes.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $project = Project::with('attributeValues.attribute')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project);
    }

    /**
     * Create a new project with optional dynamic attributes.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'attributes' => 'sometimes|array',
            'attributes.*.attribute_id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();

            $project = Project::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            if ($request->has('attributes')) {
                $attributes = $request->get('attributes');

                foreach ($attributes as $attribute) {
                    AttributeValue::create([
                        'attribute_id' => $attribute['attribute_id'],
                        'entity_id' => $project->id,
                        'entity_type' => Project::class,
                        'value' => $attribute['value'],
                    ]);
                }
            }

            DB::commit();

            $project->load('attributeValues.attribute');

            return response()->json($project, 201);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to create project', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a project and its dynamic attributes.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'status' => 'sometimes|string|max:255',
            'attributes' => 'sometimes|array',
            'attributes.*.attribute_id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            DB::beginTransaction();

            $project->update($request->only(['name', 'status']));

            if ($request->has('attributes')) {
                $attributes = $request->get('attributes');

                foreach ($attributes as $attribute) {
                    AttributeValue::updateOrCreate(
                        [
                            'attribute_id' => $attribute['attribute_id'],
                            'entity_id' => $project->id,
                            'entity_type' => Project::class,
                        ],
                        [
                            'value' => $attribute['value'],
                        ]
                    );
                }
            }

            DB::commit();

            $project->load('attributeValues.attribute');

            return response()->json($project);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to update project', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a project and its dynamic attributes.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        try {
            DB::beginTransaction();

            $project->attributeValues()->delete();
            $project->delete();

            DB::commit();

            return response()->json(['message' => 'Project deleted successfully']);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete project', 'error' => $e->getMessage()], 500);
        }
    }
}
