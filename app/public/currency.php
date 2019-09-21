<?php 
require 'header.php'; 


?>

<div class="row justify-content-center">
    <!-- Get current rate form NBP -->
    <div class="col-3">
        <h3 class="text-center">Kurs z wybranego dnia</h3>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Data</span>
            </div>
            <input type="date" class="form-control" id="pickDay" value="<?php echo date("Y-m-d");?>">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Waluta</span>
            </div>
            <select class="browser-default custom-select" class="form-control" id="pickCurrency">
                <option value="eur">Euro - EUR</option>
                <option value="gbp">Funty - GBP</option>
            </select>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Kurs</span>
            </div>
            <input type="text" id="showRate" class="form-control" disabled>
        </div>

        <div class="input-group mb-3">
            <button type="button" id="getRate" class="btn btn-default green darken-1 btn-lg btn-block btn-sm">Pobierz</button>
        </div>
    </div><!-- end -->

<!-- Exchange PLN to other currency -->
    <div class="col-3">
        <h3 class="text-center">Przelicz PLN na <span class="activCurrency"></span></h3>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">PLN</span>
            </div>
            <input type="number" id="exchangePLN" class="form-control" placeholder="Stawka PLN">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">€ £</span>
            </div>
            <input type="text" id="exchangeResult" class="form-control" placeholder="Wynik" disabled>
        </div>

        <div class="input-group mb-3">
            <button type="button" id="exchange" class="btn btn-default green darken-1 btn-lg btn-block btn-sm">Przelicz</button>
        </div>
    </div><!-- end -->

<!-- Exchange other currency to PLN -->
    <div class="col-3">
        <h3 class="text-center">Przelicz <span class="activCurrency"></span> na PLN</h3>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">€ £</span>
            </div>
            <input type="number" id="exchangeOtherCurrency" class="form-control" placeholder="Waluta obca">
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">PLN</span>
            </div>
            <input type="text" id="exchangeOtherResult" class="form-control" placeholder="Stawka PLN" disabled>
        </div>

        <div class="input-group mb-3">
            <button type="button" id="exchangeOther" class="btn btn-default green darken-1 btn-lg btn-block btn-sm">Przelicz</button>
        </div>
    </div><!-- end -->

</div><!-- end -->

<?php require 'footer.php';?>