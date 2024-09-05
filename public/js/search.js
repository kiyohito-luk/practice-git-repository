console.log("01");
$(function(){
    $('.search-btn').on('click', function(event){
        event.preventDefault();
        console.log("02");

    
        //dataの格納を記述.送りたい情報、キーワードとカンパニーID
        var keyword = $('#keyword').val();
        var company_name = $('#company_name').val();
        // var data = {keyword:keyword, company_name:company_name};
    
    
        $.ajax({
            url:'search', 
            type:"GET",
            dataType:"json",
            data: {keyword:keyword, company_name:company_name},
            // headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
    
            // beforeSend: function() {
            //     $("#loading").show();
            // }
    
        }).done(function(data) {
            //ajax通信が成功した時にやりたいこと
            //検索内容に該当するレコードの表,
    
            // $("#loading").hide();
            console.log("03");

            $('.table tbody').empty();

            if (data.length === 0) {
                $('.table').after('<p class="text-center mt-5 search-null">該当する商品が見つかりません</p>');

            } else {
                    console.log("04");
                    $.each(data.products, function(product){  //dataの中のproductsのarrayを指定しないとidが何を指すのか分からない
                        console.log(product.id);
                        let id = product.id;
                        let img_path = product.img_path;
                        let product_name = product.product_name;
                        let price = product.price;
                        let stock = product.stock;
                        let company_name = product.company_name;

                        // <td><img src="asset(${img_path})" alt="商品画像" width="100"></td>
                        // <td><img src="asset(${product->img_path})" alt="商品画像" width="100"></td>
                        
                        let html =`
                            <tr>
                                <td>${id}</td>
                                <td><img src="storage/products/(${img_path})" alt="商品画像" width="100"></td>
                                <td>${product_name}</td>
                                <td>${price}</td>
                                <td>${stock}</td>
                                <td>${company_name}</td>
                                <td>
                                <a href="/products/${id}" class="btn btn-info btn-sm mx-1">詳細</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm ('${product_name}を削除してよろしいですか？')">削除</button>
                                </form>
                            </td>
                            </tr>
                            `;

                        $('.table tbody').append(html);

                    });
            }


    
    
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log("ajax failed");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
    
        }).always({
            complete: function(){
                $("#loading").hide();
            }
    
        })
    })  
})