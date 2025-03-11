<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    /**
     * Display a listing of the attributes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return response()->json($attributes);
    }

    /**
     * Store a newly created attribute in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:text,date,number,select',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            DB::beginTransaction();

            $attribute = Attribute::create($request->only(['name', 'type']));

            DB::commit();
            return response()->json($attribute, 201);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to create attribute', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified attribute.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $attribute = Attribute::with('values')->find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Attribute not found'], 404);
        }

        return response()->json($attribute);
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'type' => 'required|string|in:text,date,number,select',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Attribute not found'], 404);
        }

        try {
            DB::beginTransaction();

            $attribute->update($request->only(['name', 'type']));

            DB::commit();
            return response()->json($attribute);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to update attribute', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified attribute from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $attribute = Attribute::find($id);

        if (!$attribute) {
            return response()->json(['message' => 'Attribute not found'], 404);
        }

        try {
            DB::beginTransaction();

            $attribute->values()->delete();
            $attribute->delete();

            DB::commit();
            return response()->json(['message' => 'Attribute deleted successfully']);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete attribute', 'error' => $e->getMessage()], 500);
        }
    }
}
