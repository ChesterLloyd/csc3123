$('.delete-note').click(function(){
    var nid = $(this).data('note');
    bootbox.confirm({
        message: "Are you sure you want to delete this note?",
        buttons: {
            cancel: {
                label: 'Cancel',
                className: 'btn-success'
            },
            confirm: {
                label: 'Delete Note',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result){
                $.post('{{base}}/ajax/delete/'+nid+'/', {
                }).done(function(data){
                    $('#n-'+nid).hide();
                    bootbox.alert('<h3>Success</h3><br>'+data);
                }).fail(function(jqXHR, textStatus, errorThrown){
                    bootbox.alert('<h3>Error Deleting Note</h3><br>'+jqXHR.responseText);
                });
            }
        }
    });
});
