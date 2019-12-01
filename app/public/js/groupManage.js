var counter = 0;

$(".seeMore").click(function(event){
    event.preventDefault();
    var loadID = $(this).attr('data-loadID');
    var parent = $("#group-"+loadID);

    if(loadID == ""){
        return
    }

    if(counter === 1){
        parent.slideUp('fast');
        parent.html('');
        counter = 0;
        return 

    }else{

        $.ajax({
            type:"GET",
            dataType:"text",
            url:"ajax.php",
            data: {getLoad : loadID}
        }).done(function(respond){
            var t = JSON.parse(respond);
    
            $.each(t, function(index, key){
                parent.append("<li>" + key['origin_name'] + ", <i>" + key['origin_postcode'] + "</i> - " + key['destination_name'] + ", <i>" + key['destination_postcode'] + "</i></li>");
            })
    
        }).done(function(){
            parent.slideDown('fast');
            counter++;
        })
    }

})