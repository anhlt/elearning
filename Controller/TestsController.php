<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tests
 *
 * @author DONG
 */
class TestsController extends AppController {
    public function show($id) {
        $data_tsv = $this->getDataTSV($id);
        $this->set('title', $data_tsv[0][1]);
        $this->set('content', $this->getContent($data_tsv));
        if ($this->request->is('post')) {
            $result = $this->data['Test']['result'];
            $row = 1;
            $error = "";
            $num = count($data_tsv);
            if ($data_tsv[1][0] == "TestSunTitle") {
                //echo "<h3 name=\"TestSunTitle\">" . $data_tsv[1][1] . "</h3>";
                $row++;
            }
            $sumQuestion = 0;
            $numberQuestion = 0;
            $point = 0;
            //echo $num;

            while ($row < $num) {

                $numberQuestion = substr($data_tsv[$row][0], 2, -1);
                if ($data_tsv[$row][0] == "End") {
                    break;
                } else {
                    $sumQuestion++;
                    if ($data_tsv[$row][1] != "QS") {


                        $error = "Thieu noi dung cau hoi $numberQuestion.";
                        break;
                    } else {
                        $questionID = $data_tsv[$row][0];
                        $row++;

                        while ($row < $num && $data_tsv[$row][0] == $questionID && $data_tsv[$row][1][0] == 'S') {
                            $row++;
                        }
                        if ($data_tsv[$row][1] != "KS") {
                            $error = "Thieu ket qua cau hoi $numberQuestion.";
                            break;
                        } else {
                            if ($result[$numberQuestion * 2 - 2] == 0) {

                            } else {
                                if ($result[$numberQuestion * 2 - 2] == $data_tsv[$row][2][2]) {
                                    $point += $data_tsv[$row][3];
                                }
                            }
                            $row++;
                        }
                    }
                }
            }

            $this->loadModel("Result");
            $dataSave = array("student_id"=>$this->Auth->user('id'), "point"=>$point, "test_id"=>$id, "student_of_choice"=>$result);
            $save_result = $this->Result->save($dataSave);
            $result_id = $save_result['Result']['id'];
            echo $result_id;
            if (strlen($error) != 0) {
                echo $error;
            }
            $this->redirect("/tests/result/".$result_id);
        }
    }

    public function result($result_id) {
        $this->loadModel("Result");
        $dataTest = $this->Result->find('first', array('conditions' => array('Result.id' => $result_id)));
        $this->set('result', $dataTest['Result']['student_of_choice']);
        $this->set('data', $this->getDataTSV($dataTest['Result']["test_id"]));
    }

    private function getDataTSV($id) {
        $test = $this->Test->find("first", array("conditions"=>array("Test.id"=>$id)));
        $filename = $test['Test']['link'];  //TSVファイルの名前はリンクとして保存されている

#        $link = $_SERVER['DOCUMENT_ROOT'] . DS . 'tsv' . DS . $filename;

#        $link = $_SERVER['DOCUMENT_ROOT'] . DS . 'tsv' . DS . $filename;
        $link = WWW_ROOT . DS . 'tsv' . DS . $filename;
        $data_tsv = array();

        if (($handle = fopen($link, "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                $num = count($data);
                for ($i = 0; $i < $num; $i++) {
                    $data[$i] = mb_convert_encoding($data[$i], 'utf-8');
                }

                if ($data[0] != null && strcmp($data[0][0], '#') != 0 && ($row != 0 || strcmp($data[0][3], '#') != 0)) {
                    //print_r($data);
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

    private function getContent($data) {
        $row = 1;
        $error = "";
        $num = count($data);
        $test = "";
        if ($data[1][0] == "TestSunTitle") {
            $test = $test . "<h3 name=\"TestSunTitle\">" . $data[1][1] . "</h3>";
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
                    $test = $test . "<h4 class=\"question\">問題 " . $numberQuestion . ":" . $data[$row][2] . "</h4>";
                    $row++;

                    while ($row < $num && $data[$row][0] == $questionID && $data[$row][1][0] == 'S') {
                        $test = $test . "<div class=\"radio\"><label>";
                        $test = $test . "<input type=\"radio\" name=\"question" . $numberQuestion . "\" id=\"optionsRadios" . $numberQuestion . "\" value=\"option" . $numberQuestion . "\"";
                        $test = $test . "value=\"0\">";
                        $test = $test . $data[$row][2];
                        $test = $test . "</label></div>";
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
            $test = $test . $error;
        }
        return $test;
    }
}
