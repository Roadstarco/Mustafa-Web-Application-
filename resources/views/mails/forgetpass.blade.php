<!DOCTYPE html>
<html>

<head>
    <title>Forget Password Mail</title>
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
            <h4 style=" font-family: Arial, ;font-size: 21px; letter-spacing: 0px;font-style: normal;font-weight: 450; color: rgb(34, 34, 34);">We Hope You Enjoy Our Services.</h4>
    <p style="font-family: Arial, ;font-size: 14px;letter-spacing: 0;font-style: normal;font-weight: 400;color: rgb(96, 108, 116);">
    Your Forget Password OTP is  <b>${{$UserRequest->OTP}}</b></p>
        </div>

        

        


    </div>
</body>

</html>