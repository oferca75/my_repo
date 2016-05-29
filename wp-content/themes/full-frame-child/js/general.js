jQuery(document).ready(function ($) {

    var jssor_1_options = {
        $AutoPlay: true,
        $AutoPlaySteps: 4,
        $SlideDuration: 260,
        $SlideWidth: 160,
        // $SlideHeight: 140,
        $Idle:8000,
        $SlideSpacing: 0,
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
        var refSize = location.pathname === "" ? screen.width: 1235;
        if (refSize) {
            refSize = Math.min(refSize, screen.width);
            jssor_1_slider.$ScaleWidth(refSize);
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
