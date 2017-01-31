<?php
$test_experiment_section = "class='active'";

require("include/template/header.inc.php");
?>
<style type="text/css">
#sketchpad {
    border: 1px solid gray;
    height: 168px;
    width: 168px;
    cursor: crosshair;
}
#pixels{
    width: 196px;
    line-height: 6px;
    font-size:4px;
    color: #fff;

}
#pixels > span{
    display:inline-block;
    width:5px; 
    height:5px;
    margin:1px;
    text-align: center;
}
.boxNumber{
    height: 168px;
    width: 168px; 
    border: 1px solid gray;
    display:block;
    border-radius:4px;
}
#result_test{
    font-size: 120px;
    text-align: center;
    vertical-align: middle;
    line-height: 168px;
}
.half-width{
    width:49%;
}

#sketchpad {
    float:left;
    position:relative; /* Necessary for correct mouse co-ords in Firefox */
}
</style>

<script type="text/javascript">
    
var myOutput = [];
var toLayers = "";


// Variables for referencing the canvas and 2dcanvas context
var canvas,ctx;

// Variables to keep track of the mouse position and left-button status 
var mouseX,mouseY,mouseDown=0;

// Variables to keep track of the touch position
var touchX,touchY;

// Draws a dot at a specific position on the supplied canvas name
// Parameters are: A canvas context, the x position, the y position, the size of the dot
function drawDot(ctx,x,y,size) {
    // Let's use black by setting RGB values to 0, and 255 alpha (completely opaque)
    r=255; g=0; b=0; a=255;

    // Select a fill style
    ctx.fillStyle = "rgba("+r+","+g+","+b+","+(a/255)+")";

    ctx.lineWidth = 1;
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    // Draw a filled circle
    ctx.beginPath();
    ctx.arc(x, y, size, 0, Math.PI*2, true); 
    ctx.closePath();
    ctx.fill();
} 

// Clear the canvas context using the canvas width and height
function clearCanvas(canvas,ctx) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

// Keep track of the mouse button being pressed and draw a dot at current location
function sketchpad_mouseDown() {
    mouseDown=1;
    drawDot(ctx,mouseX,mouseY,1);
}

// Keep track of the mouse button being released
function sketchpad_mouseUp() {
    mouseDown=0;
}

// Keep track of the mouse position and draw a dot if mouse button is currently pressed
function sketchpad_mouseMove(e) { 
    // Update the mouse co-ordinates when moved
    getMousePos(e);

    // Draw a dot if the mouse button is currently being pressed
    if (mouseDown==1) {
        drawDot(ctx,mouseX,mouseY,1);
    }
}

// Get the current mouse position relative to the top-left of the canvas
function getMousePos(e) {
    if (!e)
        var e = event;

    if (e.offsetX) {
        mouseX = e.offsetX  / 6;
        mouseY = e.offsetY  / 6;
    }
    else if (e.layerX) {
        mouseX = e.layerX;
        mouseY = e.layerY;
    }
 }

// Draw something when a touch start is detected
function sketchpad_touchStart() {
    // Update the touch co-ordinates
    getTouchPos();

    drawDot(ctx,touchX,touchY,1);

    // Prevents an additional mousedown event being triggered
    event.preventDefault();
}

// Draw something and prevent the default scrolling when touch movement is detected
function sketchpad_touchMove(e) { 
    // Update the touch co-ordinates
    getTouchPos(e);

    // During a touchmove event, unlike a mousemove event, we don't need to check if the touch is engaged, since there will always be contact with the screen by definition.
    drawDot(ctx,touchX,touchY,1); 

    // Prevent a scrolling action as a result of this touchmove triggering.
    event.preventDefault();
}

// Get the touch position relative to the top-left of the canvas
// When we get the raw values of pageX and pageY below, they take into account the scrolling on the page
// but not the position relative to our target div. We'll adjust them using "target.offsetLeft" and
// "target.offsetTop" to get the correct values in relation to the top left of the canvas.
function getTouchPos(e) {
    if (!e)
        var e = event;

    if(e.touches) {
        if (e.touches.length == 1) { // Only deal with one finger
            var touch = e.touches[0]; // Get the information for finger #1
            touchX=(touch.pageX-touch.target.offsetLeft) / 6;
            touchY=(touch.pageY-touch.target.offsetTop)  / 6;
        }
    }
}


// Set-up the canvas and add our event handlers after the page has loaded
function init() {
    // Get the specific canvas element from the HTML document
    canvas = document.getElementById('sketchpad');

    // If the browser supports the canvas tag, get the 2d drawing context for this canvas
    if (canvas.getContext)
        ctx = canvas.getContext('2d');

    // Check that we have a valid context to draw on/with before adding event handlers
    if (ctx) {
        // React to mouse events on the canvas, and mouseup on the entire document
        canvas.addEventListener('mousedown', sketchpad_mouseDown, false);
        canvas.addEventListener('mousemove', sketchpad_mouseMove, false);
        window.addEventListener('mouseup', sketchpad_mouseUp, false);

        // React to touch events on the canvas
        canvas.addEventListener('touchstart', sketchpad_touchStart, false);
        canvas.addEventListener('touchmove', sketchpad_touchMove, false);
    }
}
</script>



<div class="container tester-container" style="max-width: 500px">
    <h3 style="text-align:center">Draw a number from 0 to 9</h3>

<div class="pull-left half-width">
    <canvas id="sketchpad" class="boxNumber" height="28" width="28"></canvas>
    <div class="btn-group btn-group-justified" style="width:168px;">
        <div class="btn-group" role="group">
            <button id="clearCanvas" class="btn btn-default">Clear</button>
        </div>
        <div class="btn-group" role="group">
            <button id="getPixels" class="btn btn-success"><i></i> Test it!</button>
        </div>
    </div>
</div>

<div class="pull-right half-width">
    <span id="result_test" class="boxNumber"></span>
    <div class="btn-group btn-group-justified" style="width:168px;">
        <div class="btn-group" role="group">
            <button id="yes_button" class="btn btn-success">Yes</button>
        </div>
        <div class="btn-group" role="group">
            <button id="no_button" class="btn btn-danger">No</button>
        </div>
    </div>
</div>
<div class="hide">
    <div id="result_start"></div>
    <div id="pixels"></div>
    <div id="toLayers"></div>
</div>
</div>

<script type="text/javascript">
init();
/*
(function() {
    var canvas = document.querySelector('#paint');
    var ctx = canvas.getContext('2d');

    var clearButton = document.querySelector('#clearButton');
    var getPixels = document.querySelector('#getPixels');
    var testIt = document.querySelector('#testIt');
    
    var sketch = document.querySelector('#paint');
    var sketch_style = getComputedStyle(sketch);
    canvas.width = 28;
    canvas.height = 28;

    var mouse = {x: 0, y: 0};
    var last_mouse = {x: 0, y: 0};
    
    $("#paint").on('mousemove', function(e) {

        last_mouse.x = mouse.x;
        last_mouse.y = mouse.y;
        
        mouse.x = (e.pageX - this.offsetLeft) / 6;
        mouse.y = (e.pageY - this.offsetTop) / 6;
    });

    $("#paint").on('touchmove', function(e) {
        if (e.touches) 
            e = e.touches[0];

        last_mouse.x = mouse.x;
        last_mouse.y = mouse.y;
        
        mouse.x = (e.pageX - this.offsetLeft) / 6;
        mouse.y = (e.pageY - this.offsetTop) / 6;

        return false;

    }); 
    
    ctx.lineWidth = 1;
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    ctx.strokeStyle = 'red';
    
    $("#paint").on('mousedown', function(e) {
        $("#paint").on('mousemove', onPaint);
    });
    
    $("#paint").on('mouseup', function() {
        $("#paint").off('mousemove', onPaint);
    });

    $("#paint").on('touchstart', function(e) {
        $("#paint").on('touchmove', onPaint);
    });
    
    $("#paint").on('touchend', function() {
        $("#paint").off('touchmove', onPaint);
    });

    $("#clearButton").on('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });


    $("#getPixels").on('click', function() 
    {
        myOutput = []
        toLayers = "";

        //$("#pixels").html("Tenemos: <br/>");
        var data = ctx.getImageData(0, 0, canvas.width, canvas.height).data
        //console.log(data)
        for (var i = 0; i < data.length; i += 4) 
        {
            myOutput.push("<span style='background: rgb("+data[i]+","+data[i]+","+data[i]+");'>"+i / 4+"&nbsp;</span>" );
            toLayers += " "+data[i];
        }

        toLayers = "1 784 10 \n"+toLayers
        toLayers = toLayers+" 0 0 0 0 0 0 0 0 0 0"

        $("#pixels").html(myOutput);
        $("#toLayers").html(toLayers);
        
        $("#result_test").html("");

        $("#getPixels > i").toggleClass("fa fa-circle-o-notch fa-spin");
        $("#getPixels").attr("disabled", true);

        StartTest();

    });

   
    var onPaint = function() {
        ctx.beginPath();
        ctx.moveTo(last_mouse.x, last_mouse.y);
        ctx.lineTo(mouse.x, mouse.y);
        ctx.closePath();
        ctx.stroke();
    };




}())
*/


var GetTestResult = function(){
    $.ajax({
        url: "services/get-test-result.php",
        async: true,
        error: function(e){
            console.log(e)
            $("#result_test").html("ERROR: "+data);

        },
        success: function(data){
            console.log(data);

            var values = data.split(" ");

            var res = argmax(values);


            $("#getPixels > i").toggleClass("fa fa-circle-o-notch fa-spin");
            $("#getPixels").attr("disabled", false);

            $("#result_test").html(res);

        }
    });
}

var StartTest = function()
{
    $.post("services/start-test.php", {
        data: toLayers
    },
    function(result) 
    {

        setTimeout(GetTestResult, 500);

        //$("#result_start").html(result);
    }, false);
}

$("#clearCanvas").on('click', function(e){
    clearCanvas(canvas, ctx);
})
$("#getPixels").on('click', function() 
{
    myOutput = []
    toLayers = "";


    //$("#pixels").html("Tenemos: <br/>");
    var data = ctx.getImageData(0, 0, canvas.width, canvas.height).data
    //console.log(data)
    for (var i = 0; i < data.length; i += 4) 
    {
        myOutput.push("<span style='background: rgb("+data[i]+","+data[i]+","+data[i]+");'>"+i / 4+"&nbsp;</span>" );
        toLayers += " "+data[i];
    }

    toLayers = "1 784 10 \n"+toLayers
    toLayers = toLayers+" 0 0 0 0 0 0 0 0 0 0"

    $("#pixels").html(myOutput);
    $("#toLayers").html(toLayers);
    
    $("#result_test").html("");

    $("#getPixels > i").toggleClass("fa fa-circle-o-notch fa-spin");
    $("#getPixels").attr("disabled", true);


    StartTest();

});

function argmax(arr) {
    if (arr.length === 0) {
        return -1;
    }

    var max = arr[0];
    var maxIndex = 0;

    for (var i = 1; i < arr.length; i++) {
        if (arr[i] > max) {
            maxIndex = i;
            max = arr[i];
        }
    }

    return maxIndex;
}



</script>

<?php
require("include/template/footer.inc.php");
?>