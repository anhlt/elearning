<script type="text/javascript">
function setDefault() {
    var result = '';
    for (var j = 1; j <= document.getElementsByClassName('question').length; j++) {
        if (typeof document.getElementsByName("question" + j).length === 0)
            return 0;
        for (var i = 0; i < 15; i++) {
            if (document.getElementsByName("question" + j)[i] === undefined) {
                result = result + '0,';
                break;
            }
            if (document.getElementsByName("question" + j)[i].checked) {
                result = result + (i + 1) + ',';
                break;
            }
        }
    }
    document.getElementsByName("data[Test][result]")[0].value = result;
    document.forms["formRusult"].submit();
 //   alert(result);
}
/*
    var seconds = 0;
var minute = 2;
$("#time").html("Còn lại: 2:00");
function display() {

    if (seconds <= 0) {
        seconds = 59;
        minute -= 1;
    } else
        if (minute <= -1) {
            seconds = 0;
            minute += 1;
            setDefault();
            return;
        }
        else {
            seconds -= 1;
        }
    //alert(seconds);
    $("#time").text("Còn lại: " + minute + ":" + seconds);
    setTimeout("display()", 1000);
}

display();*/

</script>

<?php $this->LeftMenu->leftMenuStudent(STUDENT_CHOOSE_COURSE);?>
    <div class="col-xs-13 col-md-9 well">  
 <h2 name="TestTitle"><?php echo $title ?></h2>
<?php echo $content; ?>
 <button type="button" class="btn btn-success" onclick="setDefault()">Kết thúc</button>
<form name="formRusult" method="post" >
    <input name="data[Test][result]" type="hidden" value="">
</form>
</div>

