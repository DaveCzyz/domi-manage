//************* First letter to uppercase *************//
function greatLetter(){
    var field = $(this).val();
    var changeLetter = field.charAt(0).toUpperCase() + field.slice(1);
    $(this).val(changeLetter);
}

$("#customerName").on('change', greatLetter);
$("#originCity").on('change', greatLetter);
$("#destinationCity").on('change', greatLetter);

// end

//************* AJAX reusable request for JSON county list *************//
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
                unorderedList.append("<li class='live'>" + value.country + ", " + value.ISO +"</li>")
            }
        })
    })
}

// Fields for origin city
$("#originCountry").on("keyup", callAjaxLiveSearch);
// Fields for destination city
$("#destinationCountry").on("keyup", callAjaxLiveSearch);

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

// end