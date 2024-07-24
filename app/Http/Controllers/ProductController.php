<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class ProductController extends Controller
{
    
     public function __construct()
     {
         $this->middleware('auth');
     }
     

     public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $company = $request->input('company_name');

        $products = Product::search($keyword, $company);
        $companies = Company::all();

        return view('layouts.product_index', compact('products', 'companies', 'keyword'));
    }
    
    public function create()
    {
        $companies = Company::all();

        return view('layouts.product_create', compact('companies'));
    }

    
    public function store(Request $request)
    {
        try {
            $product = Product::storeProduct($request->all());

            if ($request->hasFile('img_path')) {
                $fileName = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products', $fileName, 'public');
                $product->img_path = '/storage/' . $filePath;
            }
            
            $product->save();

            return redirect()->route('products.index')->with('success', 'Product created successfully!');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            \Log::error($e);
            report($e);
            return back()->with('error', 'An error occurred while creating the product.');
        }
    }


    public function show(Product $product)
    {

        if ($product === null) {
            echo('詳細');
            abort(404, 'Product not found');
        }

        return view('layouts.product_detail', compact('product'));
    }

    
    public function edit(Product $product)
    {
        $companies = Company::all();

        return view('layouts.product_edit', compact('product', 'companies'));
    }

   
    public function update(Product $product, Request $request)
    {
        try{

            $product->updateProduct($request);

            if ($request->hasFile('img_path')) {
                if($product->img_path){
                    Storage::delete($product->img_path);
                }
                $filename = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products', $filename, 'public');
                $product->img_path = '/storage/' . $filePath;
            }

            $product->save();

            return redirect()->route('products.index')->with('success', 'Product updated successfully!');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'An error occurred while creating the product.');
        }

    }

    
    public function destroy(Product $product)
    {
        try{
            DB::beginTransaction();

            $product->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
        }
        

        return redirect('/list');
    }

    public function getProductsBySearchName(Request $request)
    {
        $keyword = $request->input('keyword');
        $company = $request->input('company_name');

        $products = Product::search($keyword, $company);
        $companies = Company::all();

        return response()->json([
            'products'=> $products,
            'companies'=> $companies

        ]);
    }
}

