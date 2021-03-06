// Scripts for exchange money page
if(window.jQuery){
    console.log('jQuery loaded')
}else{
    console.log('jQuery is not loaded')
}

// Functions for provide currency rates
// Manage date values
var date = $("#pickDay");
var getDate = date.val();
date.on('change', function(){
    getDate = date.val();
    getRespond();
});

// Manage currency values
var getCurrency = $('#pickCurrency').val();
var textField = $(".activCurrency");
textField.text(getCurrency.toUpperCase());

$("#pickCurrency").on('change', function(){
    getCurrency = $(this).val();
    textField.text(getCurrency.toUpperCase());
    getRespond();
});

// Get rates from NBP API 
var respond;
var rate = $("#showRate");
var currencyBtn = $("#getRate");
var cur = rate.val();
currencyBtn.on('click', getRespond);

function getRespond(){
    $.getJSON("http://api.nbp.pl/api/exchangerates/rates/a/"+ getCurrency + "/"+ getDate +"/?format=json", {}, function(data){
        respond = (data['rates'][0].mid).toFixed(4);
        rate.val(respond);
        cur = rate.val();
    }).fail(function(){
        rate.val("Bład. Wybierz inny dzień");
    })  
}

// Exchange PLN for other currency
$('#exchange').on('click', getExchangeFromPLN);
$("#exchangePLN").on('keyup', getExchangeFromPLN);

function getExchangeFromPLN(){
    var result = $("#exchangeResult");
    var PLN = $("#exchangePLN").val();
    if(PLN == ""){
        $('#exchangeResult').val("Podaj wartość w PLN");
        $("#exchangePLN").css({"border-color" : 'red' });
    }else if(rate.val() == ""){
        $('#exchangeResult').val("Brak waluty");
        $("#showRate").css({"border-color" : 'red' });
    }else{
        $("#exchangePLN").css({"border-color" : '' });
        $("#showRate").css({"border-color" : '' });
        var divide = (PLN / cur).toFixed(2);
        result.val(divide + " " + getCurrency.toUpperCase());
    }
}

// Exchange other currency to PLN
$('#exchangeOther').on('click', getExchaneToPLN);
$("#exchangeOtherCurrency").on('keyup', getExchaneToPLN);

function getExchaneToPLN(){
    var result = $("#exchangeOtherResult");
    var otherCurrency = $("#exchangeOtherCurrency").val();
    if(otherCurrency == ""){
        $('#exchangeOtherResult').val("Podaj wartość");
        $("#exchangeOtherCurrency").css({"border-color" : 'red' });
    }else if(rate.val() == ""){
        $('#exchangeOtherResult').val("Brak waluty");
        $("#showRate").css({"border-color" : 'red' });
    }else{
        $("#exchangeOtherCurrency").css({"border-color" : '' });
        $("#showRate").css({"border-color" : '' });
        var multiply = (otherCurrency * cur).toFixed(2);
        result.val(multiply + " PLN");
    }
}
