$(document).ready(function(){
    var noOfPlays = 0;
    var options = {
        preloader: true,
        nextButton: true,
        prevButton: true,
        animateStartingFrameIn: true,
        reverseAnimationsWhenNavigatingBackwards: false,
        numericKeysGoToFrames: false,
        swipeNavigation: false,
        pauseOnHover: true
    };

    var sequence = $("#layered").sequence(options).data("sequence");
   
});