//global variables
var minLength = -1.0;
var maxLength = -1.0;
var minDisp = -1.0;
var maxDisp = -1.0;

var lengthSliderMin = -1.0;
var lengthSliderMax = -1.0;
var dispSliderMin = -1.0;
var dispSliderMax = -1.0;


//get the document ready
$(document).ready(function () {


    //0 - TO DO - populate min and max values of sliders using geometry data
    for (var i = 0; i < myCurvesArray.length; i++) {

        //get current curve length
        var len = myCurvesArray[i]["Length"];
        console.log(len);

        //if it's greater than our max, it's the new max

        //if it's less than our min, it's the new min
    };

    //0 - TO DO - populate min and max values of sliders using geometry data
    for (var i = 0; i < myVerticesArray.length; i++) {

        //get current curve length
        var deflection = myVerticesArray[i]["Deflection"];
        console.log('def: ' + deflection);

        //if it's greater than our max, it's the new max

        //if it's less than our min, it's the new min
    };

    //1 - enable accordians
    $('.accord').accordion({ collapsible: true, active: false, heightStyle: "content" });
    $('#dragMe').draggable();

    //2 - set up sliders
    $('#lengthSlider').slider({
        range: true,
        min: 10.00,
        max: 40.00,
        step: 0.01,
        values: [12, 35],     //these should be our globals after they are populated
        slide: function (event, ui) {
            $('#length').val(ui.values[0] + " - " + ui.values[1] + "m");
            minLength = ui.values[0];
            maxLength = ui.values[1];

            //debugging - log values;
            console.log("minLength = " + minLength);
            console.log("maxLength = " + maxLength);
        }
    });
    $('#length').val(
        $('#lengthSlider').slider("values", 0)
        + " - " +
        $('#lengthSlider').slider("values", 1) + "m"
    );

    $('#dispSlider').slider({
        range: true,
        min: 0.00,
        max: 10.00,
        step: 0.001,
        values: [0, 10],
        slide: function (event, ui) {
            $('#disp').val(ui.values[0] + " - " + ui.values[1] + "m");
            minDisp = ui.values[0];
            maxDisp = ui.values[1];

            //debugging - log values;
            console.log("minDisp = " + minDisp);
            console.log("maxDisp = " + maxDisp);
        }
    });
    $('#disp').val(
        $('#dispSlider').slider("values", 0)
        + " - " +
        $('#dispSlider').slider("values", 1) + "m"
    );
});