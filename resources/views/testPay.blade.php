
  <!-- curl https://test-gateway.mastercard.com/api/nvp/version/61
-d "apiOperation=CREATE_CHECKOUT_SESSION" \
-d "apiPassword=$PWD" \
-d "apiUsername=merchant.<your_merchant_id>" \
-d "merchant=<your_merchant_id>" \
-d "interaction.operation=AUTHORIZE" \
-d "order.id=<unique_order_id>" \
-d "order.amount=100.00" \
-d "order.currency=USD" -->





<?php

// $orderid = "2";
// $merchant = "TestArabia";
// $apipassword = "625f10c58181361c412a275e80396295";
// $returnUrl = "http://localhost:8000/pay";
// $currency = "EGP";
// $amount ="10.00";
// $apiUsername= "Merchant.TestArabia";

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,"https://test-gateway.mastercard.com/api/nvp/version/59");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, "apiOperation=CREATE_CHECKOUT_SESSION&apiPassword=$apipassword&interaction.returnUrl=$returnUrl&apiUsername=$apiUsername&merchant=$merchant&interaction.operation=AUTHORIZE&order.id=$orderid&order.amount=$amount&order.currency=$currency");

// $headers = array();
// $headers[] = 'Content-Type: application/x-www-form-urlencoded';
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// $result = curl_exec($ch);
// if(curl_errno($ch)) {
//    echo 'ERROR:'. curl_error($ch);
// }

// curl_close($ch);
// // print_r($result);
// // die();
// $sessionid = explode("=", explode("&", $result)[2])[1];
// https://eu-gateway.mastercard.com/checkout/version/59/checkout.js // live
// src="https://test-gateway.mastercard.com/checkout/version/59/checkout.js" //test
?>
<script
src="https://eu-gateway.mastercard.com/checkout/version/59/checkout.js"
data-error="errorCallback"
data-cancel="cancelCallback"

 data-complete="completeCallback"
></script>
<script type="text/javascript">
 var merchantId = '<?php echo $data[0]['merchant'] ?>';
        var sessionId = '<?php echo $data[0]['sessionid'] ?>';
        var sessionVersion = '<?php echo $data[0]['sessionVersion'] ?>';
        var successIndicator =  '<?php echo $data[0]['successIndicator'] ?>'
        var orderId = '<?php echo $data[0]['orderid'] ?>'
    var resultIndicator = null;


        function errorCallback(error) {
            var message = JSON.stringify(error);
            $("#loading-bar-spinner").hide();
            var $errorAlert = $('#error-alert');
            console.log(message);
            $errorAlert.append("<p>" + message + "</p>");
            $errorAlert.show();
        }
        function cancelCallback() {
            console.log('Payment cancelled');
            // Reload the page to generate a new session ID - the old one is out of date as soon as the lightbox is invoked
            window.location.reload(true);
        }

    Checkout.configure({
        merchant: '<?php echo $data[0]['merchant'] ?>',
        order: {
            amount: function () {
                return <?php echo $data[0]['amount']?>;
            },
            currency: '<?php echo $data[0]['currency'] ?>',
            description: 'Order Goods',
            id: '<?php echo $data[0]['orderid'] ?>'
        },
        interaction: {
          merchant: {
                name: "Hoteels Egypt",
                address: {
                    line1: 'cairo',
                    line2: 'gesr suze'
                }
            }
        },
        session: {
            id: '<?php echo $data[0]['sessionid'] ?>'
        }
    });
   Checkout.showPaymentPage();
// Checkout.showLightbox();

</script>
