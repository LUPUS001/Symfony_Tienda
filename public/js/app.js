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

    // 1. AL ABRIR EL MODAL
    $(".open-cart").click(function(event) {
        event.preventDefault();
        const id = $(this).data('id');
        const href = `/cart/add/${id}`;
        
        $.get(href, function(data) {
            // Rellenamos el modal con los datos del JSON
            $(cartModal).find(".name").text(data.name);
            $(cartModal).find("img").attr("src", "/img/" + data.photo);
            $(cartModal).find("#quantity").val(data.quantity);

            // Le pegamos el ID al botón Update para usarlo luego
            $(cartModal).find(".update").data('id', data.id);
            
            // Abrimos el modal (Bootstrap 5)
            const modalInstance = new bootstrap.Modal(document.getElementById('cart-modal'));
            modalInstance.show();
        });
    });
    
    // 2. AL PULSAR EL BOTÓN UPDATE (NUEVO)
    $(".update").on('click', function() {
        const id = $(this).data('id'); // Recuperamos el ID que guardamos antes (En la línea 35 .find(".update")...)
        const quantity = $("#quantity").val();
        
        // Llamada AJAX a la ruta que acabamos de crear
        const href = `/cart/update/${id}/${quantity}`;

        $.post(href, function(data) {
            // Cerramos el modal tras actualizar, Opcion A
            const modalEl = document.getElementById('cart-modal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide(); 
            
            // Cuando se actualice mostramos un aviso, Opcion B: Más simple y más visual
            /* alert("Cantidad actualizada correctamente a " + quantity); */
        });
    });

    
    // Cerrar modal 
    $(".closeCart").click(function() {
        const modalEl = document.getElementById('cart-modal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if(modal) modal.hide();
    });
})();
