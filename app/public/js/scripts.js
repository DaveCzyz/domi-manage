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
