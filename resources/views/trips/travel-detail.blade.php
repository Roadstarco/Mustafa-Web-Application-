@extends('user.layout.app')
@section('content')
<style>
    .share-btn{
    background: #337ab7;
    color: #ffffff;
    padding: 8px 6px 5px 6px;
    font-size: 12px;
    text-align: center;
}
.share-btn .fa,.share-btn span {
    color: white;
}
.trip-user-details {
    padding-top: 10px;
    line-height: 1.5;
}
.verified-items i{
    color: var(--main-bg-color);
}
span.verified-items a{
    padding: 5px;  
}
li.list-table-header {
    background: #a7e2a8;
}
/*Travels Page Style*/
.travels-page{
    margin-bottom: 30px;    
    margin-top: 30px;    
}
.travels-page #content {
    padding: 3rem 10rem !important;
}
/*.travels-page .row .col-md-3 {
    padding: 3rem !important;
}*/
.travels-page .content-more-btn {
    padding: 9px 12px !important;
    margin-bottom: 8px;
}
.travels-page .reset-link-wrapper {
    text-align: right;
}
.travels-page .reset-link-wrapper a{
    text-decoration: none;
}
.travels-page .item {
    border: 3px solid #ccc;
    border-radius: 5px;
    margin-bottom: 15px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
}
li.list-table-header {
    background: #a7e2a8;
}
.list-table li {
    padding: 8px 10px;
}
.cms-block img, .img-responsive {
    display: block;
    max-width: 100%;
    height: auto;
}
.cms-block .fa-exclamation{
    color: var(--main-bg-color);
}
.page-header-wrapper{
    margin-bottom: 40px;
}
.trip-user-avatar{
    height: 60px;
    width: 60px;
    object-fit: cover;
    border-radius: 50%;
}
</style>
<div id="main" class="travels-page" style="height: auto !important; min-height: 0px !important;">
    <div id="content">
        <div class="row page-header-wrapper">
            <div class="row page-header-wrapper">
                <div class="col-md-2 hidden-sm hidden-xs cms-block">
                    <div class="cms-block">
                        <p>
                            <img alt="crowdshipping" src="{{asset('asset/img/economy-roadstar-detail.png')}}">
                        </p>
                    </div>

                </div>
                <div class="col-md-10">
                    <h1 class="page-header"> {{$trip->tripfrom}} - {{$trip->tripto}}</h1>
                    <div class="pull-right hidden-xs">
                        <a class="share-btn share-btn-facebook share-btn-branded" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{url('trip-detail',$trip->id)}}">
                            <i class="fa fa-facebook"></i> 
                            <span class="share-btn-text">Share</span>
                        </a>
                        &nbsp;
                        <a class="share-btn share-btn-twitter share-btn-branded" target="_blank"  href="https://twitter.com/intent/tweet?url={{url('trip-detail',$trip->id)}}">
                            <i class="fa fa-twitter"></i> 
                            <span class="share-btn-text">Share</span>
                        </a>
                    </div>
                    <div class="pubdate">
                        <div class="pull-left" style="margin-right: 5px;">
                            <a href="javascript:void(0);" title="View user profile">
                                <img src="{{$trip->avatar?$trip->avatar:asset('asset/img/avatar.png')}}" onerror="this.src='{{asset('asset/img/avatar.png')}}'"  class="trip-user-avatar">

                            </a>
                        </div>
                        <div class="trip-user-details">
                            @if($trip->created_at)
                            <strong>Trip posted on</strong>
                            {{date('M d, Y',strtotime($trip->created_at))}}

                            @endif
                            <br>
                            <strong>by</strong>
                            <a href="javascript:void(0);" class="username-link">
                                <span> {{$trip->first_name.' '.$trip->last_name}}</span>
                            </a>
                            <span class="verified-items">
                                @if($trip->email)
                                <a href="mailto:{{$trip->email}}" > <i title="Verified email address" class="fa fa-envelope"></i></a>   
                                @endif
                                @if($trip->mobile)
                                <a href="tel:{{$trip->mobile}}"><i title="Verified phone number" class="fa fa-phone"></i></a>   
                                @endif                            
                            </span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row margin-bottom-40">
            <div class="col-md-6 margin-bottom-40">
                <ul class="list-table">
                    <li class="list-table-header">
                        <strong>Trip details</strong>
                    </li>
                    <li>
                        <strong> {{$trip->recurrence!='never'?'Recurrence':'Trip arrival date'}}</strong> :
                         {{$trip->recurrence!='never'?$trip->recurrence:date('M d, Y', strtotime($trip->arrival_date))}}
                       
                    </li>
                    <li>
                        <strong>Practical details</strong> :
                         {{$trip->other_information}}
                    </li>
                </ul>
            </div>
            <div class="col-md-6 margin-bottom-40">
                <ul class="list-table">
                    <li class="list-table-header">
                        <strong>Logistics</strong>
                    </li>
                    <li>
                        <strong>Size you may transport</strong> :
                      {{$trip->item_size}}
                    </li>
                    <li>
                        <strong>Service type</strong> :
                         {{$trip->service_type}}
                        
                    </li>
                </ul>
            </div>
        </div>
        <div class="row margin-bottom-40">
            <div class="col-md-6 margin-bottom-40">
                <ul class="list-table">
                    <li class="list-table-header">
                        <strong>Provider details</strong>
                    </li>
                    <li>
                        <strong> Rating </strong> :
                         {{$trip->rating}}
                       
                    </li>
                    <li>
                        <strong>Total International Trips </strong> :
                         {{count($trip->total_trips)}}
                    </li>
                </ul>
            </div>
        </div>
        <div class="row margin-bottom-40">
            <div class="col-md-6 margin-bottom-40">
                <ul class="list-table">
                    <li class="list-table-header">
                        <strong>Previous Trips</strong>
                    </li>
                    <?php
                    for($x =0; $x<count($trip->total_trips); $x++)
                    {
                    echo '<li><strong class="page-header"> '.$trip->total_trips[$x]->tripfrom .' - '. $trip->total_trips[$x]->tripto.'</strong></li>';
                    }
                     ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<style>
    .pac-container {  
        z-index: 9999999 !important;  
        top: 10px !important;  
        left: 0 !important; 
    }
</style>
@endsection