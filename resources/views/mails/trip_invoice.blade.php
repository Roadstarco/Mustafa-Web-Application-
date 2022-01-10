<!DOCTYPE html>
<html>

<head>
    <title>Trip Invoice</title>
    <style>
        .small-font {
            font-family: Arial, ;
            font-size: 14px;
            letter-spacing: 0;
            font-style: normal;
            font-weight: 400;
            color: rgb(96, 108, 116);
        }
        
        .medium-font {
            font-family: Arial, ;
            font-size: 16px;
            letter-spacing: 0;
            font-style: normal;
            font-weight: 500;
            color: rgb(96, 108, 116);
        }
        
        .thanks {
            font-family: Arial, ;
            font-size: 21px;
            letter-spacing: 0px;
            font-style: normal;
            font-weight: 450;
            color: rgb(34, 34, 34);
        }
        
        .box {
            width: 100%;
        }
        
        .box1 {
            float: left;
            width: 40%;
        }
        
        .margin-bottom-0 {
            margin-bottom: 0px;
        }
    </style>
</head>

<body>

    <div style="width: 100%; padding: 20px;">

        <div>
            <img src="{{Setting::get('site_logo')}}" width="60" height="60" alt="">

        </div>

        <div>
            <h4 style=" font-family: Arial, ;font-size: 21px; letter-spacing: 0px;font-style: normal;font-weight: 450; color: rgb(34, 34, 34);">Thanks for booking your ride through us.</h4>
    <p style="font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">
    Your ride fare was <b>${{$UserRequest->payment->total}}</b></p>
        </div>

        <hr>

        <div style="float: left; width: 50%;">
            <h4> Pickup</h4>
        </div>
        <div style="float: left; width: 50%;">
           <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->started_at->format('Y-m-d H:i:s')}}</p>
        </div>
        <div style=" width: 100%; clear:both;">
        <p style="font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">
        <img src="{{asset('asset/img/origin-icon.png')}}"  width="25" height="25" alt="">{{$UserRequest->s_address}}
        </div>

        <div style="float: left;width: 50%;">
            <h4>Dropoff</h4>
        </div>
        <div style="float: left;width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->finished_at->format('Y-m-d H:i:s')}}</p>
        </div>
        <div style=" width: 100%; clear:both;">
            <p style="font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">
            <img src="{{asset('asset/img/origin-icon.png')}}"  width="25" height="25" alt="">{{$UserRequest->d_address}}</p>
        </div>


        <hr>
        <div style="width:100%;">
            <h4>Product detail</h4>
        </div>
        <hr>

        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">Product category</p>
        </div>
        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->category}}</p>
        </div>


        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">Product type</p>
        </div>
        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->product_type}}</p>
        </div>


         <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">Product weight</p>
        </div>
        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->product_weight.' '.$UserRequest->weight_unit}}</p>
        </div>

                <hr>

        <div style="width:100%;">
            <h4>Your fare breakdown</h4>
        </div>
        <hr>


        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">Trip Fare</p>
        </div>
        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->payment->total}}</p>
        </div>

        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">Discount</p>
        </div>
        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->payment->discount}}</p>
        </div>

        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">Subtotal</p>
        </div>
        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->payment->payable}}</p>
        </div>


        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">Amount charged</p>
        </div>
        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->payment->payable}}</p>
        </div>


        <hr>

        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">
           {{$UserRequest->payment->payment_mode}} @if($UserRequest->payment->payment_mode=="CARD") 
           {{$paymentCard->brand.' ----'.$paymentCard->last_four}} @endif </p>
        </div>
        <div style="float: left; width: 50%;">
            <p style=" font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);text-align: right">
            {{$UserRequest->payment->payable}}</p>
        </div>
        <hr>

        <div style="float: left; width: 10%;">
            <img src="{{asset('asset/img/avatra-red.png')}}" width="70" height="70" alt="">
        </div>
        <div style="float: left; width: 90%; line-height: 50%;">
            <h5 style="">{{$UserRequest->provider->first_name.' '.$UserRequest->provider->last_name}}</h5>
            <h6 style="">{{$UserRequest->provider_service->service_model.'-'.$UserRequest->provider_service->service_number}}</h6>
        </div>
        <div>

            <div style=" width: 100%; clear:both; text-align: center;">
            <h4 style=" font-family: Arial, ;font-size: 21px; letter-spacing: 0px;font-style: normal;font-weight: 450; color: rgb(34, 34, 34);">
            Thank you for using Roadstar.</h4>
            <p style="font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">
                Booking ID #<b>{{$UserRequest->booking_id}}</b></p>
        </div>

        <hr style="width:40%;margin-left:30%;">


         <div style=" width: 100%; text-align: center;">
            <p style="font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">
            This is not a tax invoice. This is a payment receipt for the transportation services provided to you by the Captain, and Roadstar is acting as a payment                  agent on behalf of the Captain (for non-cash payments).</p>
        </div>


    </div>
</body>

</html>