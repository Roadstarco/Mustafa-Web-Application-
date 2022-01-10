@extends('provider.layout.app')

@section('title', 'International Trips ')

@section('content')

<div class="pro-dashboard-content gray-bg international-trips">
    <div class="profile">
        <div class="profile-content gray-bg pad50">
            <div class="container">
                <div class="col-md-12">
                    <div class="dash-content">
                        @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                        @endif
                        <div class="row no-margin mb-3">
                            <ul class="nav nav-pills int-trips-ul">
                                <li class="active"><a data-toggle="pill" href="#int-trips">Local Trips</a></li>
                                {{--<li><a data-toggle="pill" href="#mytrips">My Trips</a></li>--}}
                            </ul>
                        </div>
                        <div class="row no-margin ride-detail">

                            <div class="tab-content">
                                <div id="int-trips" class="tab-pane fade in active">
                                    <div class="col-md-12" id="localTripsDataContainer">
                                       

                                        <table class="table table-condensed" style="border-collapse:collapse;" id="localTrips">
                                            <thead>
                                                <tr>
                                                    <th>Booking Id</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>User</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                          


                                            </tbody>
                                        </table>
                                       
                                    </div>
                                </div>

                               

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
</div>
<script src="/asset/js/jquery.min.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
    console.log( "ready!" );
    populateTable();
});

    var current_latitude = 33.720001;
    var current_longitude = 73.059998;
    var request_id='';
       

    getCurrentLocation();

    setInterval( function () {

    getCurrentLocation();

}, 45000 );


function getCurrentLocation()
{
     if( navigator.geolocation ) {
       navigator.geolocation.getCurrentPosition( success, fail );
    } else {
        console.log('Sorry, your browser does not support geolocation services');
    }

    function success(position)
    {
       
        if(position.coords.longitude != "" && position.coords.latitude != ""){
            current_longitude = position.coords.longitude;
            current_latitude = position.coords.latitude;

            console.log("current_longitude"+current_longitude);
             console.log("current_latitude"+current_latitude);

            populateTable();
        }
    }

    function fail()
    {
        console.log('unable to get your location');
    }
}


function populateTable()
{
    console.log("/provider/incoming");
    console.log(window.Laravel.csrfToken);
     $.ajax({
            url: 'https://myroadstar.org/provider/incoming',
            dataType: "JSON",
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            
            data: {
                latitude: current_latitude,
                longitude: current_longitude
            },
            type: "GET",
            success: function(data){
               
               console.log(data);
                console.log('Ajax Response', data);
                if(data.requests.length > 0) {

                    $("#localTrips tr>td").remove();

                    var data=data.requests[0].request;
                    request_id=data.id;

                    var newRows = "";
                    
                    newRows = "<tr><td>" + data.booking_id +"</td>" +
                                 "<td>" + data.s_address +"</td>" +
                                "<td>" + data.d_address + "</td>"+
                                "<td>" +data.user.first_name + "</td>"+
                                "<td>" +data.status + "</td>";
                                
                                if(data.status=="SEARCHING")
                                {
                                newRows += '<td><button  class="btn btn-primary" id="acceptRequest">Accept Request </button>&nbsp;&nbsp;'+
                                '<button class="btn btn-danger" id="rejectRequest">Reject Request </button></td></tr>';   
                                }
                                else
                                {
                                    newRows +="<td>" +data.status + "</td></tr>"
                                }

                   

                    $("#localTrips tr:first").after(newRows);
                    
                }else{
                   
                   $('#localTripsDataContainer').empty();
                     $('#localTripsDataContainer').append(' <p style="text-align: center;">No trips Available</p>');
                }

            },
            error: function(xhr, status, err) {
                console.log(this.props.url, status, err.toString());
            }
        });
}


$(document).delegate('#acceptRequest', 'click', function()
{
    console.log("request id is"+request_id);
     $.ajax({
            url: '/provider/request/'+request_id,
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            success: function(data) {
                console.log('Accept', data);
                if(data.error == undefined) {
                    window.location.replace("/provider");
                }
                
            },
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }
        });

});


$(document).delegate('#rejectRequest', 'click', function()
{
     $.ajax({
            url: '/provider/request/'+request_id,
            dataType: 'json',
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            success: function(data) {
                console.log('Reject', data);
                if(data.error == undefined) {
                    window.location.replace("/provider");
                }
            },
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }
        });

});

</script>

@endsection