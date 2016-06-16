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

    var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizing
    function ScaleSlider() {
        var jssor_width = 808;
        var refSize = location.pathname === "" ? screen.width: jssor_width;
        if (refSize) {
            refSize = Math.min(refSize, screen.width*0.4);
            jssor_1_slider.$ScaleWidth(refSize);
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
    ScaleSlider();
    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
    //responsive code end
});
