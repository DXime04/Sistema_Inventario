$(document).ready(function(){
    // Función de autocompletado para el campo de productos
    $('#producto').on('input', function(){
        var query = $(this).val();
        if (query !== '') {
            $.ajax({
                url: "fetch_productos.php", // Archivo PHP para traer datos de productos
                method: "POST",
                data: {query: query},
                success: function(data){
                    $('#producto-list').fadeIn();
                    $('#producto-list').html(data);
                }
            });
        } else {
            $('#producto-list').fadeOut();
        }
    });

    // Selección de producto en la lista de autocompletado
    $(document).on('click', '#producto-list li', function(){
        $('#producto').val($(this).text());
        $('#producto-list').fadeOut();
    });

    // Ocultar la lista cuando se hace clic fuera de ella
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#producto').length) {
            $('#producto-list').fadeOut();
        }
    });
});
