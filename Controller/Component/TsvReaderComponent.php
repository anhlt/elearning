<?php 
    class TsvReaderComponent extends Component {

    public function remove_utf8_bom($filename)
    {
        $file = WWW_ROOT. "tsv" . DS  . $filename;
        // Open the file to get existing content
        $current = file_get_contents($file);
        // Append a new person to the file
        $bom = pack('H*','EFBBBF');
        $current = preg_replace("/^$bom/", '', $current);
        // Write the contents back to the file
        file_put_contents($file, $current);
    }

	public function getViewTSV($filename) {
      	$link = WWW_ROOT. "tsv" . DS  . $filename;
        $this->remove_utf8_bom($filename);
        $data_tsv = array();
        if (($handle = fopen($link, "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                $num = count($data);
                for ($i = 0; $i < $num; $i++) {
                    $data[$i] = mb_convert_encoding($data[$i], 'utf-8');
                }
                if ($data[0] != null && strcmp($data[0][0], '#') != 0 && ($row != 0 || strcmp($data[0][3], '#') != 0)) {
                    $data_tsv[] = $data;
                }
                $row++;
            }
            fclose($handle);
        }
        $review = '';
        $error = '';
        if ($data_tsv[0][0] != 'TestTitle' || !isset($data_tsv[0][1])) {
            $error = $error . "タイトルがない";
            throw new Exception($error);
        // }elseif ($data_tsv[1][0] != 'TestSunTitle') {
        //     $error = $error . "TestSunTitleがない";
        //     throw new Exception($error);
        } else {
            $review = "<div name=\"TestTitle\">" . $data_tsv[0][1] . "</div>";
            $row = 1;
            $num = count($data_tsv);
            if ($data_tsv[1][0] == "TestSubTitle") {
                $review = $review . "<div name=\"TestSubTitle\">" . $data_tsv[1][1] . "</div>";
                $row++;
            }
            while ($row < $num) {
                $numberQuestion = substr($data_tsv[$row][0], 2, -1);
                if (!is_numeric($numberQuestion) && $numberQuestion !='') {
                    throw new Exception("question number wrong", 1);
                }
                if ($data_tsv[$row][0] == "End") {
                    break;
                } else {
                    if (!isset($data_tsv[$row][1]) || $data_tsv[$row][1] != "QS") {
                        $error = "質問の内容がない $numberQuestion.";
                        throw new Exception($error);
                        break;
                    } else {
                        $questionID = $data_tsv[$row][0];
                        $review = $review . "<div class=\"question\">問題 " . $numberQuestion . ":" . $data_tsv[$row][2] . "</div>";
                        $review = $review . "<ol>";
                        $row++;
                        if (sizeof($data_tsv[$row]) < 3) {
                            throw new Exception("Error when parse answer", 1);
                        }
                        while ($row < $num && $data_tsv[$row][0] == $questionID && $data_tsv[$row][1][0] == 'S') {
                            $review = $review . "<li>";
                            if (empty($data_tsv[$row][2])) {
                                throw new Exception("empty answer", 1);
                            }
                            $review = $review . $data_tsv[$row][2];
                            $review = $review . "</li>";
                            $row++;
                        }
                        $review = $review . "</ol>";

                        if (sizeof($data_tsv[$row]) <4) {
                            throw new Exception("Error when parse result", 1);
                        }
                        if ($data_tsv[$row][1] != "KS" || !isset($data_tsv[$row][2]) || !isset($data_tsv[$row][3])) {
                            $error = "質問の結果がない $numberQuestion.";
                            throw new Exception($error);
                            break;
                        } else {
                            $review = $review . "<div class=\"result\">結果：" . $data_tsv[$row][2][2] . "    " . $data_tsv[$row][3] . "点</div>";
                            $row++;
                        }
                    }
                }
            }
        }
        return $review;
    }
    private function getDataTSV($filename) {
        $link = 	$link = $_SERVER['DOCUMENT_ROOT']. 'tsv' . DS . $filename;
        $data_tsv = array();

        if (($handle = fopen($link, "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                $num = count($data);
                for ($i = 0; $i < $num; $i++) {
                    $data[$i] = mb_convert_encoding($data[$i], 'utf-8');
                }
                if ($data[0] != null && strcmp($data[0][0], '#') != 0 && ($row != 0 || strcmp($data[0][3], '#') != 0)) {
                    $data_tsv[] = $data;
                }
                $row++;
            }


            fclose($handle);
        } else {
            $this->Session->setFlash("Loi mo file!");
        }
        return $data_tsv;
    }


    public function getTest($filename) {
        $data = $this->getDataTSV($filename);
        $row = 1;
        $error = "";
        $num = count($data);
        $test = "";
        if ($data[1][0] == "TestSunTitle") {
            $test = $test."<h3 name=\"TestSunTitle\">" . $data[1][1] . "</h3>";
            $row++;
        }
        $sumQuestion = 0;
        $numberQuestion = 0;
        //$test = $test.$num;
        while ($row < $num) {

            $numberQuestion = substr($data[$row][0], 2, -1);
            if ($data[$row][0] == "End") {
                break;
            } else {
                $sumQuestion++;
                if ($data[$row][1] != "QS") {


                    $error = "Thieu noi dung cau hoi $numberQuestion.";
                    break;
                } else {

                    $questionID = $data[$row][0];
                    $test = $test."<h4 class=\"question\">問題 " . $numberQuestion . ":" . $data[$row][2] . "</h4>";
                    $row++;

                    while ($row < $num && $data[$row][0] == $questionID && $data[$row][1][0] == 'S') {
                        $test = $test."<div class=\"radio\"><label>";
                        $test = $test."<input type=\"radio\" name=\"question" . $numberQuestion . "\" id=\"optionsRadios" . $numberQuestion . "\" value=\"option" . $numberQuestion . "\"";
                        $test = $test."value=\"0\">";
                        $test = $test.$data[$row][2];
                        $test = $test."</label></div>";
                        $row++;
                    }

                    if ($data[$row][1] != "KS") {
                        $error = "Thieu ket qua cau hoi $numberQuestion.";
                        break;
                    } else {

                        $row++;
                    }
                }
            }
        }
        //$test = $test.$sumQuestion;
        if (strlen($error) != 0) {
            $test = $test.$error;
        }
        return $test;
    }
}
?>
