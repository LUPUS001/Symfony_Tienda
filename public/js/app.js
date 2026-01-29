//Immediately-Invoked Function Expression (IIFE)
(function(){
    const infoProduct = $("#infoProduct");
    $( "a.open-info-product" ).click(function(event) {
      event.preventDefault();
      const id = $( this ).attr('data-id');
      const href = `/api/show/${id}`;
      $.get( href, function(data) {
        $( infoProduct ).find( "#productName" ).text(data.name);
        $( infoProduct ).find( "#productPrice" ).text(data.price);
        $( infoProduct ).find( "#productImage" ).attr("src", "/img/" + data.photo);
        infoProduct.modal('show');
      })
    });
    $(".closeInfoProduct").click(function (e) {
      infoProduct.modal('hide');
    });

    // --- LÓGICA DEL CARRITO ---
    const cartModal = $("#cart-modal");

    $(".open-cart").click(function(event) {
        event.preventDefault();
        const id = $(this).data('id');
        const href = `/cart/add/${id}`;
        
        $.get(href, function(data) {
            // Rellenamos el modal con los datos del JSON
            $(cartModal).find(".name").text(data.name);
            $(cartModal).find("img").attr("src", "/img/" + data.photo);
            $(cartModal).find("#quantity").val(data.quantity);
            
            // Abrimos el modal (Bootstrap 5)
            const modalInstance = new bootstrap.Modal(document.getElementById('cart-modal'));
            modalInstance.show();
        });
    });
    
    // Cerrar modal 
    $(".closeCart").click(function() {
        const modalEl = document.getElementById('cart-modal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if(modal) modal.hide();
    });
})();
