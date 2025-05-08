<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data =ExpenseResource::collection(Auth::user()->expenses);
        return response()->json([
            'status'=>'success',
            'message'=>'expenses gotten',
            'data'=>$data,
        ]);
    }
   
    public function store(ExpenseRequest $request)
    {

        $expense = Expense::create([
            'name' => $request->input('name'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'category_id' => $request->input('categoryId'),
            'user_id' => Auth::user()->id,
        ]);
        $category = Category::where('id',$expense->category_id)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Expense created successfully',
            'data' => [
                'expense'=>$expense,
                'category'=>$category,
            ],
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
