<div class="row">
    <b id="time" style="position: fixed;color: #ff0000;" ></b>

<?php echo $this->Session->flash(); ?>
    <div class="col-md-8 col-md-offset-2"> 
        <h2 name="TestTitle"><?php echo $data[0][1] ?></h2>
    <?php 
	$row = 1;
	$error = "";
	$num = count($data);
	if($data[1][0] == "TestSunTitle") {
		echo "<h3 name=\"TestSunTitle\">".$data[1][1]."</h3>";
		$row++;
	}
        $sumQuestion = 0;
	$numberQuestion = 0;
        $point = 0;
	//echo $num;
	while($row < $num) {
                
		$numberQuestion = substr($data[$row][0], 2, -1);
		if($data[$row][0] == "End") {
			break;
		} else {
                    $sumQuestion++;
			if ($data[$row][1] != "QS") {

				
				$error = "Thieu noi dung cau hoi $numberQuestion.";
				break;
			} else {
				
				$questionID = $data[$row][0];
				echo "<h4 class=\"question\">問題 ".$numberQuestion.":".$data[$row][2]."</h4>";
                                echo "<ol>";
				$row++;

				while($row < $num && $data[$row][0] == $questionID && $data[$row][1][0] == 'S') {
					echo "<li>";
					echo $data[$row][2];
					echo "</li>";
					$row++;
				}
                                echo "</ol>";
				if ($data[$row][1] != "KS") {
					$error = "Thieu ket qua cau hoi $numberQuestion.";
					break;
				} else {
                                    if($result[$numberQuestion*2 - 2] == 0) {
                                        echo "<div>未回答!</div>";
                                    }else {
                                        echo "<div>選んだ答え:".$result[$numberQuestion*2 - 2]."</div>";
                                        if($result[$numberQuestion*2 - 2] == $data[$row][2][2]){
                                            $point += $data[$row][3];
                                        }
                                    }
                                        echo "<br><div class=\"result\">結果：" . $data[$row][2][2]. "</div>";
					$row++;
				}
			}
		}
	}
        
        //echo $sumQuestion;
if(strlen($error) != 0) {
    echo $error;
}
?>
        <h3>点: <?php echo $point;?></h3>
    </div></div>