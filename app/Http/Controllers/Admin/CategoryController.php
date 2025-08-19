<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // $categories = Category::latest();
        $categories = Category::orderBy('id','ASC');
        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }
        // $categories = Category::orderBy('id','ASC')->paginate(20);
        // $categories = Category::latest()->paginate(30);
        // dd($categories); this line print value of variable in ui page
        // $data['categories'] = $categories;
        // return view('admin.category.list', $data); // this line send to view and develoer can directly use 'category' key in blade file.
        $categories = $categories->paginate(30);
        return view('admin.category.list',compact('categories')); //this line is also same  
    }
    public function create()
    {
        // echo "category create";
        return view('admin.category.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories'
        ]);
        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $request->session()->flash('success', 'Category added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit() {}
    public function update() {}
    public function destroy() {}
}
