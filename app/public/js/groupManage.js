$("#seeMore").click(function(event){
    event.preventDefault();
    var loadID = $(this).attr('data-loadID');
    if(loadID == ""){
        return
    }

    $.ajax({
        type:"GET",
        dataType:"text",
        url:"ajax.php",
        data: {getLoad : loadID}
    }).done(function(respond){
        var t = JSON.parse(respond);
        console.log(t)
    })


})