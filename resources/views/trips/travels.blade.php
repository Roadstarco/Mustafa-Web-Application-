@extends('user.layout.app')
@section('content')
    <style>
                    .travels-page .item {
            border: 3px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }
        .item .user {
            display: block;
            background: #eee;
            padding: 15px;
        }
        .item .user .image {
            /* margin-bottom: 0px; */
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .item .user .image img {
            float: left;
            margin-right: 10px;
            height: 60px;
            width: 60px;
            object-fit: cover;
            border-radius: 50%;
        }
        .item .user .level {
            color: #000;
            font-weight: 700;
        }
        .item .description {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            padding: 15px;
        }
        .item .description .infos .date {
            font-size: 1.2em;
        }
        .item .description .infos .date strong {
            margin-left: 15px;
            color: #000;
        }
        .item .description .infos .transportation {
            float: right;
            color: #0082c8;
            font-size: 1.5em;
        }
        .item .description .path {
            padding: 20px 40px;
            color: #000;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
        }
        .item .description .path .origin .fa {
            display: inline-block;
            width: 25px;
            font-size: 24px;
        }
        .item .description .path .country {
            font-size: 14px;
        }
        .item .description .path .steps {
            border-top: 3px solid var(--main-bg-color);
            margin-top: 15px;
            padding-top: 10px;
        }
        .item .description .path .arrival .fa {
            color: #ec382e;
        }
        .item .description .path .country {
            font-size: 14px;
        }
        .item .description .actions {
            margin-top: 10px;
        }
        .item .description .actions .btn-contact {
            float: right;
        }
        .item .user .validation, .item .user .level, .item .user .image {
            margin-top: 6px;
        }
        .item .description .path .origin .fa {
            color: #2bb259;
        }
        .validation{
            padding: 5px 10px ;
        }
        .validation i.fa {
            color: var(--main-bg-color);
            margin: 0 3px;
            display: inline-block;
            font-size: 1.2em;
        }
        .content-more-btn {
    margin: 0;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    background-color: var(--main-bg-color);
    color: #fff;
    padding: 15px 20px;
    display: inline-block;
    padding-top: 18px;
    }

    </style>
    <?php
   
    $user_id = 0;
    if(Auth::check())
    {
        if(isset(Auth::user()->picture))
        {
            
            $user_id =Auth::user()->id;        
        }
    }
    else
    {
       // header("Location: https://myroadstar.org/provider/login");
       // die();
    }
   
//$user_id =Auth::user()->id;
//session()->put('provider_id',10);
        



?> 
   
    <div id="main" class="travels-page" style="height: auto !important; min-height: 0px !important;">
    <div id="content">
        <div class="row page-header-wrapper">
            <div class="col-md-3 hidden-sm hidden-xs cms-block">
                <div class="cms-block">
                    <p><img alt="sharing economy" src="{{ asset('asset/img/economy-roadstar.png') }}"></p>
                </div>
            </div>
            <div class="col-md-9">
                <h1 class="page-header">Crowd Shipping - Trip search</h1>
                <div class="row margin-top-30">
                    <div class="col-md-12">
                        <form action="{{url('search-trips')}}" method="post" id="trips-form" class="search-form">

                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-group-origin">
                                                <label class="control-label"><i class="fa fa-map-marker" aria-hidden="true"></i> From</label>
                                                <input type="text" onfocus="initialize(this)" id="tripfrom" name="tripfrom" required="required" placeholder="City or Country" class="form-control" />
                                                <input type="hidden" id="tripfrom_lat" name="tripfrom_lat"/>
                                                <input type="hidden" id="tripfrom_lng" name="tripfrom_lng"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-arrival">
                                                <label class="control-label"><i class="fa fa-map-marker" aria-hidden="true"></i> To</label>
                                                <input type="text"  onfocus="initialize(this)" id="tripto" name="tripto" required="required" placeholder="City or Country" class="form-control" />

                                                <input type="hidden" id="tripto_lat" name="tripto_lat"/>
                                                <input type="hidden" id="tripto_lng" name="tripto_lng"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">

                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-default btn-block content-more-btn">
                                            Search for a traveller
                                        </button>
                                        {{-- <div class="reset-link-wrapper">
                                            <a class="margin-top-10" href="{{ url('/travels') }}">
                                                New search
                                            </a>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="height: auto !important;">
            <div class="col-md-12" style="height: auto !important; min-height: 0px !important;">
                @if(!$trips->isEmpty())
                @php
                  // dd($trips)  
                @endphp
                <!--<div class="cms-block">-->
                <!--    <p><i class="fa fa-exclamation"></i>&nbsp;Results are sorted by the shortest distance from origin and arrival, based on available trips and active users on the site.<br>-->
                <!--        To maximize your chances, we advise you to contact more than one traveler&nbsp;<i class="fa fa-exclamation"></i></p>-->
                <!--</div>-->
                <div id="trips-list">
                    @foreach($trips as $trip) 
                    
                    <div class="item item-travel">
                        <div class="user" title="View user profile">
                            <div class="image">
                                <img src="{{$trip->avatar?$trip->avatar:asset('asset/img/avatar.png')}}" onerror="this.src='{{asset('asset/img/avatar.png')}}'">
                                <strong>  {{$trip->first_name.' '.$trip->last_name}}</strong>
                            </div>
                            <div class="validation">
                                @if($trip->email)
                                <a href="mailto:{{$trip->email}}"> <i title="Verified email address" class="fa fa-envelope"></i></a>   
                                @endif
                                @if($trip->mobile)
                                <a href="tel:{{$trip->mobile}}"><i title="Verified phone number" class="fa fa-phone"></i></a>   
                                @endif
                            </div>
                            <!--                        <div class="level">
                                                        Beginner
                                                    </div>-->
                        </div>
                        <div class="description">
                            <div class="infos">
                            <?php $trip->recurrence='never'; ?>
                                <span class="date">
                                    @if($trip->recurrence!='never')<!-- comment -->
                                    <i class="fa fa-refresh"></i>
                                    @else
                                    <i class="fa fa-calendar"></i> 
                                    @endif 
                                    <strong>

                                        {{$trip->recurrence!='never'?$trip->recurrence:date('M d, Y', strtotime($trip->arrival_date))}}
                                    </strong>
                                </span>
                                <i class="transportation fa fa-plane"></i>
                            </div>
                            <div class="path">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="origin">
                                            <i class="fa fa-map-marker"></i>
                                            <strong>
                                                {{$trip->tripfrom}}
                                            </strong>
                                                    <!--<span class="country">(US)</span>-->
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="steps">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="arrival">
                                            <i class="fa fa-map-marker"></i>
                                            {{$trip->tripto}}
                                            <!--<span class="country">(VN)</span>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="actions">
                                <a class="btn btn-default-alt content-more-btn" href="{{url('trip-detail',$trip->id)}}"  title="More details">
                                    More details
                                </a>
                                <!-- mailto:{{$trip->email}} -->
                                {{session(['poster_email' => $trip]) }}
                                @if ($user_id == 0)
                                <a class="btn btn-primary btn-contact"  onclick="check()" title="Send a message"> Send a message
                                </a>
                                @else
                                <a class="btn btn-primary btn-contact"  href='https://myroadstar.org/guest-chat?provider_id={{$trip->provider_id}}&user_image="{{$trip->avatar?$trip->avatar:asset("asset/img/avatar.png")}}"&user_email={{$trip->email}}&user_name="{{$trip->first_name}}"' title="Send a message">

                                    Send a message
                                </a>
@endif                                
                            </div>

                        </div>
                    </div>
 
                    @endforeach
                </div>
                @else
                <div class="alert alert-info fade in alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                    <strong>Sorry!</strong> No trips available at the moment.</div>
                @endif
            </div>
        </div>
    </div>
</div>
<style>
    .filter-seajax .visible-loader {
        padding: 22% 8%;
    }.filter-seajax {
        position: fixed;
        top: 0px;
        left: 0px;
        right: 0px;
        bottom: 0px;
        background: #fff;
        opacity: 0.9;
        z-index: 10000;
    }
</style>
<div id="filter-seajax" class="filter-seajax text-center" style="display: none">
    <p class="visible-loader"><img src="{{ asset('asset/img/loader.gif')}}"/>Loading ... </p>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key={{ Setting::get('map_key') }}&libraries=places&callback=initMap" async defer></script>
@section('scripts')
@endsection
<script type="text/javascript">

                                                    function initialize(item) {
                                                        var options = {
                                                            types: ['(cities)']
                                                        };
                                                        var autocomplete = new google.maps.places.Autocomplete(item, options);

                                                        google.maps.event.addListener(autocomplete, 'place_changed', function () {
                                                            var place = autocomplete.getPlace();

//                                                            console.log(place.address_components[0]['short_name']);
//                                                            console.log(place.address_components[3]['long_name']);
                                                            document.getElementById($(item).attr('id') + '_lat').value = place.geometry.location.lat();
                                                            document.getElementById($(item).attr('id') + '_lng').value = place.geometry.location.lng();


                                                        }
                                                        );
                                                    }
function check()
{
    var x = <?php echo $user_id; ?>;
    console.log('i am here');
    console.log(x);
    if(x == 0)
    {
        alert("Please Login First");
        return false;

    }
   
}


</script>
@endsection