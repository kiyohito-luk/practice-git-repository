<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'img_path',
    ];

    protected $atrributes = [
        'comment' => '',
        'img_path' => '商品画像.jpg',

    ];


    public static function search($keyword, $company)
    {
        $query = self::query();

        if (!empty($keyword)) {
            $query->where('product_name', 'LIKE', "%{$keyword}%");
        }

        if (isset($company)) {
            $query->where('company_id', $company);
        }

        return $query->paginate(10);
    }

    public static function storeProduct(array $request)
    {
        $validator = Validator::make($request,[
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $product = new Product($request);
        
        return $product;
    }


    public function updateProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $this->product_name = $request->product_name;
        $this->price = $request->price;
        $this->stock = $request->stock;
        $this->comment = $request->comment;

        $product = new Product();
        return $product;
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
    
    