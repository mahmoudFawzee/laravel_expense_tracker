<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return json_encode([
            'status' => 'success',
            'message' => 'Expenses retrieved successfully',
            'data' => Expense::all()
        ]);
    }
   
    public function store(ExpenseRequest $request)
    {
        $expense = Expense::create([
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'category_id' => $request->input('category_id'),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Expense created successfully',
            'data' => $expense
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
