/*
 * Loader classes for header and foot
 */
$(function(){
        //every page will have these 2 elements
    var path = window.location.pathname;
    
    if(path.substr(path.length - 3) == 'php'){
        $("#header_nav").load("../Helpers/header.html"); 
        $("#footer_nav").load("../Helpers/footer.html");
    } else {
        $("#header_nav").load("Helpers/header.html"); 
        $("#footer_nav").load("Helpers/footer.html");
    }
});

