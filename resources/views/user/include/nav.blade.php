<div class="col-md-3">
    <div class="dash-left">
      
        <div class="side-menu">
            <ul>
                <li><a href="{{url('dashboard')}}">@lang('user.dashboard')</a></li>
                <li><a href="{{url('mytrips')}}">@lang('user.my_trips')</a></li>

                <li> <a data-toggle="collapse" data-target="#tripsubmenu" style="cursor: pointer"> International Trips</a>
                    <ul id="tripsubmenu"  class="collapse tripsubmenu">
                        <li><a href="{{url('international-trips')}}">My International Trips</a></li>
                        <li><a href="{{route('international-trips.provider')}}">Provider Trips</a></li>        
                    </ul>
                </li>
                <li><a href="{{url('upcoming/trips')}}">@lang('user.upcoming_trips')</a></li>
                <li><a href="{{url('profile')}}">@lang('user.profile.profile')</a></li>
                <li><a href="{{url('change/password')}}">@lang('user.profile.change_password')</a></li>
                <li><a href="{{url('/payment')}}">@lang('user.payment')</a></li>
                <li><a href="{{url('/promotions')}}">@lang('user.promotion')</a></li>
                <li><a href="{{url('/wallet')}}">@lang('user.my_wallet') <span class="pull-right">{{currency(Auth::user()->wallet_balance)}}</span></a></li>
                <li><a href="{{url('/message-user')}}">Chats</a></li>
                <li><a href="{{ url('/logout') }}"
                       onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">@lang('user.profile.logout')</a></li>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </ul>
        </div>
    </div>
</div>