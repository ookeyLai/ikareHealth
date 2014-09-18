$(document).bind('mobileinit', function () {
    $.mobile.allowCrossDomainPages = true;
    $.mobile.zoom.enabled = false;
    $.mobile.buttonMarkup.hoverDelay = 0; //defaults 200
    $.mobile.defaultDialogTransition = 'none';
    $.mobile.defaultPageTransition = 'none';
});
ul.ui-listview *.ui-btn-up-e,
ul.ui-listview *.ui-btn-down-e,
ul.ui-listview *.ui-btn-hover-e {
   border: 1px solid #FBFCFE !important;
   background: #E7F1FD !important;
   font-weight: bold !important;
   color: #57576B !important;
   text-shadow: 0 1px 1px white !important;
   background-image: -webkit-gradient(linear,left top,left bottom,from( white ),to( #E7F1FD )) !important;
   background-image: -webkit-linear-gradient( white , #E7F1FD ) !important;
   background-image: -moz-linear-gradient( white , #E7F1FD ) !important;
   background-image: -ms-linear-gradient( white , #E7F1FD ) !important;
   background-image: -o-linear-gradient( white , #E7F1FD ) !important;
   background-image: linear-gradient( white , #E7F1FD ) !important;
}

ul.ui-listview *.ui-btn-up-e a.ui-link-inherit,
ul.ui-listview *.ui-btn-down-e a.ui-link-inherit,
ul.ui-listview *.ui-btn-hover-e a.ui-link-inherit { color: #57576B !important; }

 .in,.out{-webkit-animation-timing-function: ease-in-out;-webkit-animation-duration:50ms !important;}

 .ui-shadow, .ui-btn-up-a, .ui-btn-hover-a, .ui-btn-down-a, .ui-body-b, .ui-btn-up-b, .ui-btn-hover-b, .ui-btn-down-b, .ui-bar-c, .ui-body-c, .ui-btn-up-c, .ui-btn-hover-c, .ui-btn-down-c, .ui-bar-c, .ui-body-d, .ui-btn-up-d, .ui-btn-hover-d, .ui-btn-down-d, .ui-bar-d, .ui-body-e, .ui-btn-up-e, .ui-btn-hover-e, .ui-btn-down-e, .ui-bar-e, .ui-overlay-shadow, .ui-shadow, .ui-btn-active, .ui-body-a, .ui-bar-a {
     text-shadow: none !important;
     box-shadow: none !important;
     -webkit-box-shadow: none !important;
 }