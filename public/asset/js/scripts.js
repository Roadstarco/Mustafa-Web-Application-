$(document).ready(function () {
    var trigger = $('.hamburger'),
            overlay = $('.overlay'),
            isClosed = false;
    trigger.click(function () {
        hamburger_cross();
    });

    overlay.click(function () {
        hamburger_cross();
    });

    function hamburger_cross() {
        if (isClosed == true) {
            overlay.hide();
            trigger.removeClass('is-open');
            trigger.addClass('is-closed');
            isClosed = false;
        } else {
            overlay.show();
            trigger.removeClass('is-closed');
            trigger.addClass('is-open');
            isClosed = true;
        }
    }

    $('[data-toggle="offcanvas"]').click(function () {
        $('#wrapper').toggleClass('toggled');
    });
});

function setCSS() {
    var $window = $(window);
    var windowHeight = $(window).height();
    var windowWidth = $(window).width();
    $('.full-page-bg').css('min-height', windowHeight);

}

$(document).ready(function () {
    setCSS();
    $(window).resize(function () {
        setCSS();
    });

});





var tabCarousel = setInterval(function () {
    var tabs = $('.car-tab .nav-tabs > li'),
            active = tabs.filter('.active'),
            next = active.next('li'),
            toClick = next.length ? next.find('a') : tabs.eq(0).find('a');

    toClick.trigger('click');
}, 5000);





$("form#trips-form").on('submit', function (e) {

    e.preventDefault();
    $.ajax({
        data: $("form#trips-form").serialize(),
        type: 'post',
        url: $("form#trips-form").attr('action'),
        beforeSend: function (xhr) {
            $("#filter-seajax").show();
        },
        complete: function (jqXHR, textStatus) {
            setTimeout(function () {
                $("#filter-seajax").hide();
            }, 1000);
        },

        success: function (data, textStatus, jqXHR) {
            var html = ``;
            if (data.trips.length) {
                // var trips=JSON.parse(data.trips);
                $.each(data.trips, function (index, trip) {
//                    console.log(trip);
//                    alert(trip['email']);return;
                    if (trip['picture']) {
                        var img = trip['picture'];
                    } else {
                        var img = '/public/asset/img/avatar.png';
                    }
                    var email = ``;
                    if (trip['email']) {
                        email = `   <a href="mailto:` + trip['email'] + `}"> <i title="Verified email address" class="fa fa-envelope"></i></a>`;
                    }
                    var mobile = ``;
                    if (trip['mobile']) {
                        mobile = `<a href="tel:` + trip['mobile'] + `}"> <i title="Verified phone number" class="fa fa-phone"></i></a>`;
                    }

                    html += `                <div class="item item-travel">
                    <div class="user" title="View user profile">
                        <div class="image">
                            <img src="` + img + `" onerror="this.src='/asset/img/avatar.png'">
                           ` + trip['first_name'] + ` ` + trip['last_name'] + `
                        </div>
                        <div class="validation">
                         ` + email + mobile + `
                        </div>
                        <!--                        <div class="level">
                                                    Beginner
                                                </div>-->
                    </div>
                    <div class="description">
                        <div class="infos">
                            <span class="date">`;
                    if (trip['recurrence'] !== 'Never') {
                        html += `<i class="fa fa-refresh"></i>`;
                    } else {
                        html += `<i class="fa fa-calendar"></i>`;
                    }
                    var rec = trip['recurrence'] !== 'Never' ? trip['recurrence'] : trip['arrival_date'];
                    html += `<strong>` + rec + `</strong>
                            </span>
                            <i class="transportation fa fa-plane"></i>
                        </div>
                        <div class="path">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="origin">
                                        <i class="fa fa-map-marker"></i>
                                        <strong>` + trip['tripfrom'] + `</strong>
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
                                       ` + trip['tripto'] + `
                                        <!--<span class="country">(VN)</span>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="actions">
                            <a class="btn btn-default-alt content-more-btn" href="/trip-detail/` + trip['id'] + `" title="More details">
                                More details
                            </a>
                            <a class="btn btn-primary btn-contact" href="mailto:` + trip['email'] + `"title="Send a message">
                                Send a message
                            </a>
                        </div>
                    </div>
                </div>`;
                });

            } else {
                html = `<div class="alert alert-info fade in alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <strong>Sorry!</strong> No result for this search at the moment.</div>`;
            }
            $("#trips-list").html(html);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
});
$("input[name='recurrence']").on('change', function () {
    if ($(this).val() != 'never') {
        $("#arrival_date").prop('readonly', true);
        $("#return_date").prop('readonly', true);
    } else {
        $("#arrival_date").prop('readonly', false);
        $("#return_date").prop('readonly', false);
    }
});