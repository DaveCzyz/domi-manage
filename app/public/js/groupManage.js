$(".seeMore").click(function(event){
    event.preventDefault();
    var loadID = $(this).attr('data-loadID');
    var parent = $("#group-"+loadID);

    if(loadID == ""){
        return
    }

    console.log(parent)

    if(parent.children().length > 0){
        parent.slideUp('fast');
        parent.html('');
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
                parent.append("<tr><td>"+key['origin_name']+"</td><td>"+key['origin_postcode']+"</td><td>-></td><td>"+key['destination_name']+"</td><td>"+key['destination_postcode']+"</td></tr>");
                // parent.append("<li class='list-group-item'>" + key['origin_name'] + ", <i>" + key['origin_postcode'] + "</i> - " + key['destination_name'] + ", <i>" + key['destination_postcode'] + "</i></li>");
            })
    
        }).done(function(){
            parent.slideDown('fast');
            counter++;
        })
    }

})