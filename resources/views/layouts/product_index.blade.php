@extends('layouts.app')

@section('content')
<div class="container">
    <header>
        <h1>商品一覧画面</h1>
        <form action="{{ route('products.index') }}" method="GET">
            <input type="text" placeholder="キーワード" name="keyword" value="{{ $keyword }}">
            <select name="company_name" id="company_name">
                <option value="">メーカー名</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}" @if($company=='{{ $company->id }}') selected @endif>{{ $company->company_name }}</option>
                @endforeach
            </select>

            <button type="submit">検索</button>
        </form>
    </header>
    <main>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>メーカー名</th>
                    <a href="{{route('products.create')}}" class="btn btn-primary mb-3">新規登録</a>
                </tr>
            </thead>
            <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </main>
    {{ $products->appends(request()->query())->links() }}
</div>
@endsection