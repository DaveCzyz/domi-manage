//************* First letter to uppercase *************//
function greatLetter(){
    var field = $(this).val();
    var changeLetter = field.charAt(0).toUpperCase() + field.slice(1);
    $(this).val(changeLetter);
}

$("#customerName").on('change', greatLetter);
$("#originCity").on('change', greatLetter);
$("#destinationCity").on('change', greatLetter);
// Form file manage_loads.php
$("#editCustomer").on('change', greatLetter);
$("#editOriginName").on('change', greatLetter);
$("#editDestinationName").on('change', greatLetter);
$("#loadOriginCity").on('change', greatLetter);
$("#loadDestinationCity").on('change', greatLetter);

// end

//************* AJAX reusable request for JSON country list *************//
$.ajaxSetup({ cache: false });
function callAjaxLiveSearch(){
    // Result list
    var unorderedList = $(this).next();
        unorderedList.html('');
    if($(this).val() == ''){
        return
    }
    var searchField = $(this).val();
    var expression  = new RegExp(searchField, "i");
    $.getJSON('json/countries_iso_nazwy_polskie.json', function(data){
        $.each(data, function(key, value){
            if(value.country.search(expression) != -1 || value.ISO.search(expression) != -1){
                unorderedList.append("<li class='live'>" + value.country + ", " + value.ISO +"</li>");
                unorderedList.append("<li class='live' style='display:none'>" + value.postcode + "</li>");
            }
        })
    })
}

// Fields for origin city
$("#originCountry").on("keyup", callAjaxLiveSearch);
// Fields for destination city
$("#destinationCountry").on("keyup", callAjaxLiveSearch);

// Fields form manage_loads.php
$("#editOriginCountry").on("keyup", callAjaxLiveSearch);
$("#editDestinationCountry").on("keyup", callAjaxLiveSearch);

// Fields from manage_lads.php - for adding new related load
$("#loadOriginCountry").on("keyup", callAjaxLiveSearch);
$("#loadDestinationCountry").on("keyup", callAjaxLiveSearch);

// end


//************* Add AJAX result to input *************//
function liveSearchResult(){
    var resultField = $(this);
    var resultList  = $(this).parent();
    var inputField  = resultList.siblings('input');
    inputField.val(resultField.html());
    resultList.html('');
}

// Fields for origin city
$("#originResult").on("click", "li", liveSearchResult);
// Fields for destination city
$("#destinationResult").on("click", "li", liveSearchResult);

// Fields from manage_loads.php
$("#editOriginResult").on("click", "li", liveSearchResult);
$("#editDestinationResult").on("click","li", liveSearchResult);

// end

//************* AJAX request for JSON country list and postcode *************//
var post;

function postCodeResultOrigin(){
    post = $(this).next().html();
    var resultField = $(this);
    var resultList  = $(this).parent();
    var inputField  = resultList.siblings('input');
    inputField.val(resultField.html());
    resultList.html('');
    $("#postcodeFormatOrigin").html("w formacie: " + post)
}

$("#loadOriginResult").on("click","li", postCodeResultOrigin);

function postCodeResultDestination(){
    post = $(this).next().html();
    var resultField = $(this);
    var resultList  = $(this).parent();
    var inputField  = resultList.siblings('input');
    inputField.val(resultField.html());
    resultList.html('');
    $("#postcodeFormatDestination").html("w formacie: " + post)
}

$("#loadDestinationResult").on("click","li", postCodeResultDestination);

// end

//************* AJAX live serach request for customer input *************//
function callForCustomer(){
    // Result list
    var unorderedList = $(this).next();
    unorderedList.html('');
    if($(this).val() == ''){
        return
    }
    var searchField = $(this).val();
    var expression  = new RegExp(searchField, "i");

    $.ajaxSetup({ cache: true });
    $.ajax({
        type:"GET",
        dataType:"json",
        url:'ajax.php?getCustomerList=true',
        success : function(respond){
            var customers = [];
            $.each(respond, function(key, value){
                customers.push(value);
                
                if(value.search(expression) != -1){
                    unorderedList.append("<li class='live'>" + value + "</li>");
                }
            })         
        }
    })              // !!! trzeba wyczyscic te same dane
}

// Field for customer
$("#customerName").on('keyup', callForCustomer);
$("#customerResult").on("click", "li", liveSearchResult);
