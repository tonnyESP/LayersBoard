<?php
$test_experiment_section = "class='active'";

require("include/template/header.inc.php");
?>
<style type="text/css">
#sketch, #result {
    border: 1px solid gray;
    height: 168px;
    width: 168px;
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
#paint{
    height: 168px;
    width: 168px; 
}
#result_test{
    font-size: 100px;
    text-align: center;
    vertical-align: middle;
    line-height: 120px;
}
html, body{
     max-width:100%;
     max-height:100%;
     overflow:hidden;
}
</style>
<div id="sketch">
    <canvas id="paint"></canvas>
</div>
<button id="clearButton" class="btn btn-default">Clear</button>
<button id="getPixels" class="btn btn-success">Test it!</button>

<div id="result">
    <h1 id="result_test"></h1>
</div>
<button id="clearButton" class="btn btn-success">Yes</button>
<button id="getPixels" class="btn btn-danger">No</button>


<div id="result_start"></div>

<div id="pixels"></div>
<div id="toLayers"></div>


<script type="text/javascript">
    
var myOutput = [];
var toLayers = "";




$('html, body').on('touchstart touchmove', function(e){ 
     //prevent native touch activity like scrolling
     e.preventDefault(); 
});

(function() {
    var canvas = document.querySelector('#paint');
    var ctx = canvas.getContext('2d');

    var clearButton = document.querySelector('#clearButton');
    var getPixels = document.querySelector('#getPixels');
    var testIt = document.querySelector('#testIt');
    
    var sketch = document.querySelector('#sketch');
    var sketch_style = getComputedStyle(sketch);
    canvas.width = 28;
    canvas.height = 28;

    var mouse = {x: 0, y: 0};
    var last_mouse = {x: 0, y: 0};
    
    /* Mouse Capturing Work */
    canvas.addEventListener('mousemove', function(e) {
        last_mouse.x = mouse.x;
        last_mouse.y = mouse.y;
        
        mouse.x = (e.pageX - this.offsetLeft) / 6;
        mouse.y = (e.pageY - this.offsetTop) / 6;
    }, false);

    canvas.addEventListener('touchmove', function(e) {
        last_mouse.x = mouse.x;
        last_mouse.y = mouse.y;
        
        mouse.x = (e.pageX - this.offsetLeft) / 6;
        mouse.y = (e.pageY - this.offsetTop) / 6;
    }, false); 
    
    /* Drawing on Paint App */
    ctx.lineWidth = 1;
    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    ctx.strokeStyle = 'red';
    
    canvas.addEventListener('mousedown', function(e) {
        canvas.addEventListener('mousemove', onPaint, false);
    }, false);
    
    canvas.addEventListener('mouseup', function() {
        canvas.removeEventListener('mousemove', onPaint, false);
    }, false);

    canvas.addEventListener('touchstart', function(e) {
        canvas.addEventListener('touchmove', onPaint, false);
    }, false);
    
    canvas.addEventListener('touchend', function() {
        canvas.removeEventListener('touchmove', onPaint, false);
    }, false);

    clearButton.addEventListener('click touchdown', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }, false);


    getPixels.addEventListener('click touchdown', function() 
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


        StartTest();

    }, false);

   
    var onPaint = function() {
        ctx.beginPath();
        ctx.moveTo(last_mouse.x, last_mouse.y);
        ctx.lineTo(mouse.x, mouse.y);
        ctx.closePath();
        ctx.stroke();
    };




}())

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

        setTimeout(GetTestResult, 3000);

        //$("#result_start").html(result);
    }, false);
}


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