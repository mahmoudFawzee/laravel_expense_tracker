<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Auth::user()->categories;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request){
        $user = Auth::user();
       // $user = User::where('id',$authedUser->id)->first();
        //? when user try to create a new category with specific name
        //?we will get his categories and check if the name he try to add exists or not
            $categoryName = $request->validated()['name'];

       //?if exists we will tell him you already has this category
            if($user->categories()->where('name',$categoryName)->exists()){
                return response()->json([
                    'status'=>'failed',
                    'message'=>'you already has this category'
                ]);
            }
        //?if not exists we will check if this category exist
        $category = Category::where('name', $request['name'])->first();
        
        //?if null we will create it
        if($category==null){
             $category=  Category::create([
            'name'=>$categoryName,
            'user_id'=>$user->id,
        ]);
        }
        //?then we will attach it to the current user
        $category->users()->attach($user->id);
        return response()->json([
                    'status'=>'success',
                    'message'=>'Category Created Successfully'
       ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
