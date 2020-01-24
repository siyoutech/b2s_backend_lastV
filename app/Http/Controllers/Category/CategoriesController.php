<?php
namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Request;

class CategoriesController extends Controller {

    // public function getCategoryList($id) {
    //     $subCat = Category::find($id);
    //     $parentCat = $subCat->getParentCategory;

    //     return response()->json($parentCat);
    // }

    
    public function getCategories()
    {
        $categories = Category::whereNull('parent_category_id')->with('subCategories')->get();
        return response()->json($categories);
    }


    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $category->Delete();
        return response()->json(["msg" => "the category has been deleted !!"]);
    }


    public function updateCategory(Request $request, $id)
    {
        $category = Category::findorfail($id);
        $category->category_name = $request->input('category_name');
        $category->parent_category_id = $request->input('parent_category_id');
        if ($category->save()) {
            return response()->json($category);
        }
        return response()->json(["msg" => "ERROR !!"]);
    }

    public function getCategoryParent($id)
    {
        $subCat = Category::Find($id);
        $parentCat = $subCat->getParentCategory;

        return response()->json($parentCat);
    }

    public function getCategoryChild($id)
    {
        $parentCat = Category::find($id);
        $subCat = $parentCat->getChildCategories;
        return response()->json($subCat);
    }

    public function ShowCategory($id)
    {
        $category = Category::findorfail($id);
        return response()->json($category);
    }


    public function addCategory(Request $request)
    {
        $category = new Category();
        $category->category_name = $request->input('category_name');
        $category->parent_category_id = $request->input('parent_category_id');
        if ($category->save()) {
            return response()->json("The Category has been added Successfully !!");
        }
        return response()->json("Error !!");
    }
public function categorieslist(){
    $supplier=AuthController::me();
}
}
