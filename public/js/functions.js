$(function () {
    var id = $('#group-id').val()
    if (id > 0) LoadCategories()

});
// lo de arriba no funciona no se porque. active el llamado desde el onchange del select

$('#delete-item').submit(function(e) {
    e.preventDefault();
    let message = document.getElementById('message-item-delete').value
    Swal.fire({
        title: 'Esta Seguro de Eliminar? ',
        text: message,
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: 'No',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
        }).then((result) => {
        if (result.value) {
          this.submit();
        }
    })
})


function LoadCategories () {

    var id = $('#group-id').val()
    var category = $('#category_id').val()
    $.get('/t-control/public/api/products/'+ id +'/categories', function (data){
        var html_select_group = '<option value = 0> Seleccione la categoria ... </option>'
        for (var i=0; i < data.length; i++) {
            html_select_group += '<option value = "' + data[i].id + '"';
            if (category > 0 && data[i].id == category) {
                html_select_group += ' selected'
            }
            html_select_group += '> '+ data[i].description + '</option>';
        }
        $('#category-id').html(html_select_group);
    })
}

