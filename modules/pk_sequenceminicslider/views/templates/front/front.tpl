{if $page_name == "index"}
    <!-- SEQUENCE SLIDER -->    
    {include file="{$minicSlider.tpl}"}
    <script type="text/javascript">
        $(document).ready(function(){
            var noOfPlays = 0;
            var options = {
                preloader: true,
                nextButton: true,
                prevButton: true,
                animateStartingFrameIn: true,
                reverseAnimationsWhenNavigatingBackwards: false,
                numericKeysGoToFrames: false,
                swipeNavigation: true,
                pauseOnHover: {if $minicSlider.options.hover == 1}true{else}false{/if},
                autoPlay: {if $minicSlider.options.manual == 1}true{else}false{/if},
                autoPlayDirection: 1,
                autoPlayDelay: {if $minicSlider.options.pause != ''}{$minicSlider.options.pause}{else}5000{/if},
                cycle: true
            };

            var sequence = $("#sequence").sequence(options).data("sequence");
            var wdth = $("#sequence-theme .nav").width();
            var wdth2 = $("#sequence-theme .controls").width();
            $("#sequence-theme .nav").css({ "left": "50%", "marginLeft": -(wdth/2) });        
            $("#sequence-theme .controls").css({ "left": "50%", "marginLeft": -(wdth2/2) });        

            function autoChangePagination() {
                $("#sequence-theme .nav li").removeClass("active main_bg");
                $("#sequence-theme .nav li:nth-child("+sequence.nextFrameID+")").addClass("active main_bg");
            }

            sequence.beforeNextFrameAnimatesIn = function() {
                if(sequence.nextFrameID == 4) {
                    noOfPlays = 1;
                }
                if(sequence.nextFrameID == 1 && noOfPlays  > 0) {
                    sequence.stopAutoPlay();
                }
                autoChangePagination();
            }

            $("#sequence-theme .nav").on("click", "li", function() {
                sequence.startAutoPlay();
                sequence.settings.pauseOnHover = true;
                $("#sequence-theme .nav li").removeClass("active main_bg");
                $(this).addClass("active main_bg");
                sequence.nextFrameID = $(this).index()+1;
                sequence.goTo(sequence.nextFrameID);
            });
        });
    </script>
    <!-- END OF SEQUENCE SLIDER -->
{/if}