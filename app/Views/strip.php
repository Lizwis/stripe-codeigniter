<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        body {
            background-color: #d2f5d2;
        }
    </style>


</head>

<body>

    <div class="container">
        <div class="row">

            <div class="col-lg-6 mx-auto mt-5 py-4 bg-white px-4 shadow">
                <?php

                if (session()->get("message")) {
                ?>
                    <div class="alert alert-success">
                        <?= session()->get("message") ?>
                    </div>
                <?php
                }
                ?>

                <form role="form" action="<?php echo base_url('payment') ?>" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="<?= STRIPE_KEY ?>" id="payment-form">
                    <div class='form-row row'>
                        <div class='col-md-12 error form-group d-none'>
                            <div class='alert-danger alert'>Please correct the errors and try
                                again.</div>
                        </div>
                    </div>
                    <div class="col-12 name required">
                        <label><b>Name on Card</b></label>
                        <input required type="text" class="form-control mt-1 name">
                    </div>
                    <div class="col-12 pt-4 card-number required">
                        <label><b>Card Number</b></label>
                        <input required type="number" class="form-control mt-1 card_number" autocomplete='on'>
                    </div>
                    <div class="row pt-4">
                        <div class="col-lg-4 cvc required">
                            <label><b>CCV</b></label>
                            <input required type="number" class="form-control mt-1 ccv" placeholder="eg. 367">
                        </div>
                        <div class="col-lg-4 expiration required">
                            <label><b>Expiration Month</b></label>
                            <input required type="number" class="form-control mt-1 exp_month" placeholder="MM">
                        </div>
                        <div class="col-lg-4 expiration required">
                            <label><b>Expiration Year</b></label>
                            <input required type="number" class="form-control mt-1 exp_year" placeholder="YYYY" id="year">
                        </div>
                    </div>
                    <div class="col-lg-12 pt-4 text-center">
                        <button type="submit" class="btn btn-primary  btn-lg btn-block">Pay Now($1)</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    $(function() {

        var $form = $(".require-validation");

        $('form.require-validation').bind('submit', function(e) {
            var $form = $(".require-validation"),
                inputSelector = ['input[type=text]'].join(', '),
                $inputs = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid = true;
            $errorMessage.addClass('d-none');

            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('d-none');
                    e.preventDefault();
                }
            });

            if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    customer: $('.name').val(),
                    number: $('.card_number').val(),
                    cvc: $('.cvc').val(),
                    exp_month: $('.exp_month').val(),
                    exp_year: $('.exp_year').val()
                }, stripeResponseHandler);
            }

        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                $('.error')
                    .removeClass('d-none')
                    .find('.alert')
                    .text(response.error.message);
            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];

                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }

    });
</script>

</html>