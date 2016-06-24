jQuery(document).ready(function ($) {

    var jssor_1_options = {
        $AutoPlay: true,
        $AutoPlaySteps: 4,
        $SlideDuration: 260,
        $SlideWidth: 150,
        // $SlideHeight: 140,
        $Idle:8000,
        $SlideSpacing: 15,
        $Cols: 8,
        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$,
            $Steps: 4
        },
        // $BulletNavigatorOptions: {
        //     $Class: $JssorBulletNavigator$,
        //     $SpacingX: 1,
        //     $SpacingY: 1
        // }
    };

    setTimeout(function(){
        $(".jssor_container").css("visibility","visible !important")
    },0);
    $(".jssor_container").each(function(){
        var jssor_slider = new $JssorSlider$($(this).attr("id"), jssor_1_options);
        ScaleSlider(jssor_slider);
        $(window).bind("load", ScaleSlider(jssor_slider));
        $(window).bind("resize", ScaleSlider(jssor_slider));
        $(window).bind("orientationchange", ScaleSlider(jssor_slider));
    })

    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizing
    function ScaleSlider(slider) {
        var jssor_width = 808;
        var refSize = location.pathname === "" ? screen.width: jssor_width;
        if (refSize) {
            refSize = Math.min(refSize, screen.width < 600 ? screen.width : screen.width*0.45);
            slider.$ScaleWidth(refSize);
            var factor = refSize*0.3/jssor_width;
            var scale = factor;
            scale = Math.min(1,scale * 2);
            var baseMargin = -37;
            var marginTop = Math.max(baseMargin,baseMargin * factor * 2 - 10);
            jQuery(".next-step.next-arrow").css("transform","rotate(90deg) translateZ(0px) scale("+ scale+")");
            jQuery(".next-step.next-arrow").css("margin-top",marginTop+"px");
            jQuery(".next-step.next-arrow").css("visibility","visible");

        }
        else {
            window.setTimeout(ScaleSlider, 30);
        }
    }
    //responsive code end
});
