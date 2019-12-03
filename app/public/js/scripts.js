// Toggle slide buttons in user_progile.php
$("#toogleChangeUserData").click(function(){
    $("#changeUserData").slideToggle("fast");
});

$("#toogleChangeTransData").click(function(){
    $("#changeTransData").slideToggle("fast");
});

$("#toogleChangePassword").click(function(){
    $("#changePassword").slideToggle("fast");
});

$("#toggleDeleteAccount").click(function(){
    $("#deleteAccount").slideToggle("fast");
});

// Toggle slide button in loads.php
$("#addNewLoadGroup").click(function(){
    $("#loadGroup").slideToggle("fast");
    $(this).hide();
});

$("#cancelLoadGroup").click(function(){
    $("#loadGroup").slideToggle("fast");
    $("#addNewLoadGroup").show("fast");
});

// Toggle slide button in manage_loads.php
$("#addNewLoad").click(function(){
    $("#addNewRelatedLoad").slideToggle("fast");
});
// Clear all inputs and toggle form
$("#cancelNewLoad").click(function(e){
    e.preventDefault();
    $("#addNewRelatedLoad").slideToggle("fast");

    $("#loadOriginCity").val('');
    $("#loadOriginCountry").val('');
    $("#loadOriginPostcode").val('');
    $("#postcodeFormatOrigin").html('');
    $("#loadDestinationCity").val('');
    $("#loadDestinationCountry").val('');
    $("#loadDestinationPostcode").val('');
    $("#postcodeFormatDestination").html('');
    $("#weightDetails").val('');
    $("#lengthDetails").val('');
});

