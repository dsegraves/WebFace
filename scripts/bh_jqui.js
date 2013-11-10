//global variables
var minLength = -1.0;
var maxLength = -1.0;
var minDisp = -1.0;
var maxDisp = -1.0;

var lengthSliderMin = 1000000;
var lengthSliderMax = -1.0;
var dispSliderMin = 1000000;
var dispSliderMax = -1.0;


//get the document ready
$(document).ready(function () {


    //0 - TO DO - populate min and max values of sliders using geometry data
    for (var i = 0; i < myCurvesArray.length; i++) {

        //get current curve length
        var len = myCurvesArray[i]["Length"];
        var L = parseFloat(len);

        //if it's greater than our max, it's the new max
        if (L > lengthSliderMax) {
            lengthSliderMax = L;
        };

        //if it's less than our min, it's the new min
        if (L < lengthSliderMin) {
            lengthSliderMin = L;
        };
    };

    //0 - TO DO - populate min and max values of sliders using geometry data
    for (var i = 0; i < myVerticesArray.length; i++) {

        //get current curve length
        var deflection = myVerticesArray[i]["Deflection"];
        var D = parseFloat(deflection);
        D = D / 100000000000;

        //if it's greater than our max, it's the new max
        if (D > dispSliderMax) {
            dispSliderMax = D;
        };

        //if it's less than our min, it's the new min
        if (D < dispSliderMin) {
            dispSliderMin = D;
        };
    };

    //1 - enable accordians
    $('.accord').accordion({ collapsible: true, active: false, heightStyle: "content" });
    $('#dragMe').draggable();

    //2 - set up sliders

    //length slider
    $('#lengthSlider').slider({
        range: true,
        min: lengthSliderMin,
        max: lengthSliderMax,
        step: 0.01,
        values: [lengthSliderMin, lengthSliderMax],     //these should be our globals after they are populated
        //on slide event:
        slide: function (event, ui) {

            var myMin = ui.values[0];
            var myMax = ui.values[1];
            minLength = myMin.toFixed(3);
            maxLength = myMax.toFixed(3);
            $('#length').val(minLength + " to " + maxLength + "m");

            //debugging - log values;
            //console.log("minLength = " + minLength);
            //console.log("maxLength = " + maxLength);
        }
    });

    //set values first time through
    var minL = $('#lengthSlider').slider("values", 0);
    var maxL = $('#lengthSlider').slider("values", 1);
    $('#length').val(
        minL.toFixed(3)
        + " to " +
        maxL.toFixed(3) + "m"
    );



    //disp slider
    $('#dispSlider').slider({
        range: true,
        min: dispSliderMin,
        max: dispSliderMax,
        step: 0.001,
        values: [dispSliderMin, dispSliderMax],
        slide: function (event, ui) {
            var myMin = ui.values[0];
            var myMax = ui.values[1];
            minDisp = myMin.toFixed(3);
            maxDisp = myMax.toFixed(3);
            $('#disp').val(minDisp + " to " + maxDisp + "m");

            //debugging - log values;
            //console.log("minDisp = " + minDisp);
            //console.log("maxDisp = " + maxDisp);
        }
    });

    //set values first time through
    var minD = $('#dispSlider').slider("values", 0);
    var maxD = $('#dispSlider').slider("values", 1);
    $('#disp').val(
        minD.toFixed(3)
        + " to " +
        maxD.toFixed(3) + "m"
    );
});