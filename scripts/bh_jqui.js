


//get the document ready
$(document).ready(function() {
	
	
	//1 - enable accordians
    $('.accord').accordion({ collapsible: true, active: false, heightStyle: "content" });
    $('#dragMe').draggable();

    //2 - set up sliders
    $('#lengthSlider').slider({
        range: true,
        min: 10.00,
        max: 40.00,
        values: [12, 35],
        slide: function (event, ui) {
            $('#length').val(ui.values[0] + " - " + ui.values[1])
        }
    });
    $('#length').val(
        $('#lengthSlider').slider("values", 0)
        + " - " +
        $('#lengthSlider').slider("values", 1)
    );

    $('#dispSlider').slider({
        range: true,
        min: 0.00,
        max: 10.00,
        values: [0, 10],
        slide: function (event, ui) {
            $('#disp').val(ui.values[0] + " - " + ui.values[1])
        }
    });
    $('#disp').val(
        $('#dispSlider').slider("values", 0)
        + " - " +
        $('#dispSlider').slider("values", 1)
    );
});