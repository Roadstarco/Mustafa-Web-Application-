@extends('user.layout.base')

@section('title', 'Payment')

@section('content')

<div class="col-md-9">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">@lang('user.payment')</h4>
            </div>
        </div>
        @include('common.notify')
        <div class="row no-margin payment">
            <div class="col-md-12">
                <h5 class="btm-border"><strong>@lang('user.payment_method')</strong> 
                @if(Setting::get('CARD') == 1)
                <a href="#" class="sub-right pull-right" data-toggle="modal" data-target="#add-card-modal">@lang('user.card.add_card')</a>
                @endif
                </h5>

                {{--<div class="pay-option">
                    <h6><img src="{{asset('asset/img/cash-icon.png')}}"> @lang('user.cash') </h6>
                </div>--}}
                @if(Setting::get('CARD') == 1)
                @foreach($cards as $card)
                <div class="pay-option">
                    <h6>
                        <img src="{{asset('asset/img/card-icon.png')}}"> {{$card->brand}} **** **** **** {{$card->last_four}} 
                        @if($card->is_default)
                            <a href="#" class="default">@lang('user.card.default')</a>
                        @endif 
                        <form action="{{url('card/destory')}}" method="POST" class="pull-right">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="card_id" value="{{$card->card_id}}">
                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm" >@lang('user.card.delete')</button>
                        </form>
                    </h6>
                </div>
                @endforeach
                @endif

            </div>
        </div>

    </div>
</div>

@if(Setting::get('CARD') == 1)

    <!-- Add Card Modal -->
    <div id="add-card-modal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" >
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">@lang('user.card.add_card')</h4>
          </div>
            <form id="payment-form" action="{{ route('card.store') }}" method="POST" >
                {{ csrf_field() }}
          <div class="modal-body">
            <div class="row no-margin" id="card-payment">
                <div class="form-group col-md-12 col-sm-12">
                    <label>@lang('user.card.fullname')</label>
                    <input data-stripe="name" autocomplete="off" required type="text" id="cardHolderName" class="form-control" 
                    placeholder="@lang('user.card.fullname')">
                </div>
                <div class="form-group col-md-12 col-sm-12">
                    <label>@lang('user.card.card_no')</label>
                    <div id="card-number"></div>
                </div>

                <div class="form-group col-md-8 col-sm-12">
                    <label>@lang('user.card.month')</label>
                    <div id="card-expiry"></div>
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label>@lang('user.card.cvv')</label>
                    <div id="card-cvv"></div>
                </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" id="btnSubmit" class="btn btn-default">@lang('user.card.add_card')</button>
          </div>
        </form>

        <p  style="padding-left:10px; padding-top:10px;">Your card information is secure please check our <a href="{{url('/privacy')}}" target="_blank" style="color:blue;">Privacy Policy</a> for more details</p>

        </div>

      </div>
    </div>

    @endif

@endsection

@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>

    <script type="text/javascript">

        // Stripe.setPublishableKey("{{ Setting::get('stripe_publishable_key')}}");
        const stripe = Stripe("{{ Setting::get('stripe_publishable_key')}}");

        var elements = stripe.elements();


            var cardNumberElement = elements.create('cardNumber', {
                classes: {
                base: 'form-control',
            }
        });

        // Add an instance of the card Element into the `card-element` <div>.
        cardNumberElement.mount('#card-number');


        //Create Card Expiry Date Element
        var cardExpiryElement = elements.create('cardExpiry', {
        // placeholder: 'Card Number',
        classes: {
        base: 'form-control',
        }
        });
        // Add an instance of the card Element into the `card-element` <div>.
        cardExpiryElement.mount('#card-expiry');


        //Create Card Number Element
        var cardCvcElement = elements.create('cardCvc', {
        // placeholder: 'Card Number',
        classes: {
        base: 'form-control',
        }
        });

        cardCvcElement.mount('#card-cvv');

        // Add an instance of the card Element into the `card-element` <div>.
        
                
        $('#btnSubmit').on('click',function (event) {
            


           stripe.createToken(cardNumberElement).then(function(response) {
                console.log(response);
            if (response.error) {

                $form.find('.payment-errors').text(response.error.message);
                $form.find('button').prop('disabled', false);
                alert('error');

            } else {

                var token = response.token.id;
                var lastFour = response.token.card.last4;
                var brand = response.token.card.brand;

                
                 var $form = $('#payment-form');
                // Insert the token into the form so it gets submitted to the server
                $form.append($('<input type="hidden" id="stripeToken" name="stripe_token" />').val(token));
                $form.append($('<input type="hidden" id="lastFour" name="last_four" />').val(lastFour));
                $form.append($('<input type="hidden" id="brand" name="brand" />').val(brand));

                jQuery($form.get(0)).submit();
            }
            });
        });

    </script>
    <script type="text/javascript">
        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }
    </script>
@endsection