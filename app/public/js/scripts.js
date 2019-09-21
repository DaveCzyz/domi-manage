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
var currency = $('#pickCurrency');
var textField = $(".activCurrency");
var getCurrency = currency.val();
textField.text(getCurrency.toUpperCase());
currency.on('change', function(){
    getCurrency = currency.val();
    textField.text(getCurrency.toUpperCase());
    getRespond();
});


// Get rates from NBP API 
var respond;
var rate = $("#showRate");
var currencyBtn = $("#getRate");
currencyBtn.on('click', getRespond);

function getRespond(){
    $.getJSON("http://api.nbp.pl/api/exchangerates/rates/a/"+ getCurrency + "/"+ getDate +"/?format=json", {}, function(data){
        respond = (data['rates'][0].mid).toFixed(4);
        rate.val(respond);
    }).fail(function(){
        rate.val("Bład. Wybierz inny dzień");
    })  
}

// Exchange PLN for other currency
$('#exchange').on('click', getExchangeFromPLN);
$("#exchangePLN").on('keyup', getExchangeFromPLN);

function getExchangeFromPLN(){
    var result = $("#exchangeResult");
    var currency = rate.val();
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
        var divide = (PLN / currency).toFixed(2);
        result.val(divide + " " + getCurrency.toUpperCase());
    }
}

// Exchange other currency to PLN
$('#exchangeOther').on('click', getExchaneToPLN);
$("#exchangeOtherCurrency").on('keyup', getExchaneToPLN);

function getExchaneToPLN(){
    var result = $("#exchangeOtherResult");
    var currency = rate.val();
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
        var multiply = (otherCurrency * currency).toFixed(2);
        result.val(multiply + " PLN");
    }
}





