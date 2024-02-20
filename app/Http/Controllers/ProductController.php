<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
     public function __construct()
     {
         $this->middleware('auth');
     }
     

    public function index(Request $request)
    {   
        $query = Product::query();
        $companies = Company::all();

        $keyword = $request->input('keyword');
        $company = $request->input('company_name');


        $query->join('companies', function ($query) use ($request){
            $query->on('products.company_id', '=', 'companies.id');
            });

        if(!empty($keyword)){
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }

        if(isset($company)){
            $query->where('company_id', $company);
        }

        

        $products = $query->get();
        $products = $query->paginate(10)->appends($request->all());

        return view('layouts.product_index', compact('products','companies', 'keyword'));
        //returnの内容はまとめる！！
        //view配下のパスの選択は（フォルダ名）.（ファイル名）で選択する
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();

        return view('layouts.product_create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);

        try{
            DB::beginTransaction();
    
            $product = new Product([
                'product_name' => $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
            ]);
    
            if($request->hasFile('img_path')){
                $filename = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products',$filename,'public');
                $product->img_path = '/storage/' . $filePath;
            }
    
            $product->save();

            DB::commit();

            

        } catch (\Exception $e) {
            DB::rollback();

        }
        return redirect()->route('products.index');

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        dump($product);
        if ($product === null) {
            echo('詳細');
            abort(404, 'Product not found');
        }

        return view('layouts.product_detail', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $companies = Company::all();

        return view('layouts.product_edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try{
            DB::beginTransaction();

            $request->validate([
                'product_name' => 'required',
                'price' => 'required',
                'stock' => 'required',
            ]);

            $product->product_name = $request->product_name;
            $product->price = $request->price;
            $product->stock = $request->stock;

            $product->save();

            DB::commit();
        } catch (\Exception $e){
            DB::rollback();
            Log::error($e);
        }
        

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}

