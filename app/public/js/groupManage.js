$("#seeMore").click(function(event){
    event.preventDefault();
    var loadID = $(this).attr('data-loadID');
    if(loadID == ""){
        return
    }

    $.ajax({
        type:"POST",
        dataType:"text",
        url:"ajax.php",
        data: {"getLoad" : loadID},
        success: function(response){
            console.log(response);
        }
    })




    //console.log(loadID)
})