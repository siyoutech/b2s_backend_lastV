<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
class ProductsController extends Controller
{

    public function __construct()
    {
        // $this->middleware('cors');
        // Sto
    }

/**
 * manager products (supplier)
 */
    public function addProduct(Request $request)
    {
        $supplier = AuthController::me();

        $product = new Product();
        $catalog = Catalog::find(1);
        $product->category_id = $request->input('category_id');
        $product->product_name = $request->input('product_name');
        $product->supplier_id = $supplier->id;
        $product->product_description = $request->input('product_description');
        $product->quantity = $request->input('quantity');
        $product->product_price = $request->input('product_price');
        $product->product_weight = $request->input('product_weight');
        $product->product_size = $request->input('product_size');
        $product->product_color = $request->input('product_color');
        $product->discount_type = $request->input('discount_type');
        $product->product_barcode = $request->input('product_barcode');
        $product->product_box = $request->input('product_box');
        $product->product_package = $request->input('product_package');
        $product->product_discount_price = $request->input('product_discount_price');
        $product->product_image = $request->input('product_image');
        // if ($request->hasFile('product_image')) {
        //     $path = $request->file('product_image')->store('products', 'public');
        //     // return response()->json(["msg" => "has file"]);
        //     $product->product_image = $path;
        // }
        // return response()->json(["msg" => "has no file"]);
        if ($product->save()) {
            $product->catalogs()->attach($catalog);
            return response()->json($product);
        }
        return response()->json(["msg" => "error while saving"]);
    }
    public function SupplierproductList()
    {
        $supplier = AuthController::me();

        $productsList = Product::where('supplier_id', $supplier->id)->get();
        return response()->json($productsList);
    }
    public function updateProduct(Request $request, $id)
    {
        $supplier = AuthController::me();
        $product = Product::find($id);
        $product->product_name = $request->input('product_name');
        $product->product_description = $request->input('product_description');
        $product->quantity = $request->input('quantity');
        $product->product_price = $request->input('product_price');
        $product->product_weight = $request->input('product_weight');
        $product->product_size = $request->input('product_size');
        $product->product_color = $request->input('product_color');
        $product->product_package = $request->input('product_package');
        $product->product_box = $request->input('product_box');
        $product->product_barcode = $request->input('product_barcode');
        $product->discount_type = $request->input('discount_type');
        $product->product_discount_price = $request->input('product_discount_price');
        $product->category_id = $request->input('category_id');
        $product->supplier_id = $supplier->id;
        $product->product_image = $request->input('product_image');
            // if ($request->hasFile('product_image')) {
        //     $path = $request->file('product_image')->store('products', 'public');
        //     // return response()->json(["msg" => "has file"]);
        //     $product->product_image = $path;
        // }
        // return response()->json(["msg" => "has no file"]);
        if ($product->save()) {
            return response()->json($product);
        }
        return response()->json(["msg" => "ERROR !!"]);
    }

    public function getProductsByCategory($category_id)
    {
        $category_products = Category::with('products')->find($category_id);
        foreach ($category_products['products'] as $product) {
            $product['suppliers'] = user::where('id', $product['supplier_id'])->get();
        }
        return response()->json($category_products);
    }


    public function getProductsBySupplier($supplier_id)
    {
        $Supplier_products = User::with('products')->find($supplier_id);
        return response()->json($Supplier_products);
    }

    public function deleteProduct($id)
    {
        $supplier = AuthController::me();

        $product = Product::find($id);
        if($product->supplier_id == $supplier->id){
        $product->delete();
        return response()->json(["msg" => "the Product has been deleted Successfully !!"]);
    }
    return response()->json(["msg"=>"Not your Product !!!!"]);
}



public function GetAllSupplierWithProducts()
{
    $Supplier_products = User::with('products')->where('role_id', 2)->get();
    return response()->json($Supplier_products);
}


public function GetAllCategoryWithProducts()
{
    $category_products = Category::with('products')->get();
    $allCategoryProduct = $category_products[0];


    foreach ($allCategoryProduct['products'] as $product) {
        $product['suppliers'] = User::where('id', $product['supplier_id'])->get();
    }
    return response()->json($allCategoryProduct);
}


public function showProduct($id)
{
    $product = Product::findorfail($id);
    return response()->json($product);
}

public function productList()
{
    $productsList = Product::all();
    return response()->json($productsList);
}


    public function addProductsWithExcel(Request $request)
    {
        // if ($request->hasFile('products')) {
        //     return response()->json(["msg" => "has file!"]);
        // }
        $file = Input::file('products');
        $fileReader = IOFactory::createReaderForFile($file);
        $spreadSheet = $fileReader->load($file->path());
        $content = $spreadSheet->getActiveSheet()->toArray();
        $responseArray = [];
        foreach ($content as $key => $value) {
            if ($key === 0) continue;
            $product = new Product();
            foreach ($value as $key => $val) {
                $product[$content[0][$key]] = $val;
            }
            if ($product->save()) {
                $responseArray[] = $product;
            }
        }
        // return response()->json($spreadSheet->getActiveSheet()->toArray());
        return response()->json($responseArray);
        //  test
        // $inputFile = __dir__ .'/products.xlsx';
        // $spreadsheet = IOFactory::load($inputFile);
        // return response()->json($spreadsheet->getActiveSheet()->toArray());
    }

    
    // public function getImageUrl(Request $request)
    // {
    //     $name = $request->query('name');
    //     $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
    //     $path = Storage::url($name);
    //     return response()->json(["msg" => $storagePath . $path]);
    // }
}
