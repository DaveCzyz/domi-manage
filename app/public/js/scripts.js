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
// Toggle slide button in fleet.php
$("#addNewCarrier").click(function(){
    $("#newCarrier").slideToggle("fast");
    $(this).hide();
});

// Clear all inputs and toggle form - manage_loads.php
$("#cancelLoadGroup").click(function(){
    $("#loadGroup").slideToggle("fast");
    $("#addNewLoadGroup").show("fast");

    $("#customerName").val('');
    $("#customerResult").html('')
    $("#originCity").val('');
    $("#originCountry").val('');
    $("#destinationCity").val('');
    $("#destinationCountry").val('');
});

// Clear all inputs and toggle form - fleet.php
$("#cancelCarrier").click(function(){
    $("#newCarrier").slideToggle("fast");
    $("#addNewCarrier").show("fast");

    $("#carrierName").val('');
    $("#carrierBase").html('')
    $("#carrierContact").val('');
    $("#carrierPhone").val('');
    $("#carrierEmail").val('');
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

// Toggle filter form
$("#toggleFiltr").click(function(){
    $("#filtrForm").slideToggle('fast');
});

// Toggle carriers card
$(".showCarrierDetails").click(function(e){
    var parent = $(this).parent();
    var next = parent.next().next();
    next.slideToggle();



})
