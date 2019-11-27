$('.unfavourite').click(function(){
    var nid = $(this).data('note');
    $.post('{{base}}/ajax/removeFavourite/'+nid+'/',{
    }).done(function(data){
        $('#fn-'+nid).hide();
    }).fail(function(jqXHR, textStatus, errorThrown){
        bootbox.alert('<h3>Error</h3><br>'+jqXHR.responseText);
    });
});
