$(function(){
    $('.search-btn').on('click', function(event){

        event.preventDefault();
    

    
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
            console.log('成功');

            $('.table tbody').empty();

            if (data.length === 0) {
                // No results found
                $('.table').after('<p class="text-center mt-5 search-null">該当する商品が見つかりません</p>');

            } else {
                    // data = JSON.parse(data);
                    data.forEach(function(product) {
                        const html = `
                          <tr>
                            <td>${product.id}</td>
                            <td><img src="asset(${product.img_path})" alt="商品画像" width="100"></td>
                            <td>${product.product_name}</td>
                            <td>${product.price}</td>
                            <td>${product.stock}</td>
                            <td>${product.company_name}</td>
                            </tr>
                        `
                        
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