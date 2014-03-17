<?php

//App::uses('AppsController', 'Controller');

class TsvsController extends AppController {

    public $uses = array();

    public function index() {
        /* form submitted? */
        ECHO $this->getViewTSV();
        if ($this->request->is('post')) {
            $filename = time() . $this->data['TSV']['file']['name'];
            $link = $_SERVER['DOCUMENT_ROOT'] . DS . 'elearning' . DS . WEBROOT_DIR . DS . 'tsv' . DS . $filename;

            /* copy uploaded file */
            if (move_uploaded_file($this->data['TSV']['file']['tmp_name'], $link)) {

                $data_tsv = array();

                if (($handle = fopen($link, "r")) !== FALSE) {
                    $row = 0;
                    while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                        $num = count($data);
                        for ($i = 0; $i < $num; $i++) {
                            $data[$i] = mb_convert_encoding($data[$i], 'utf-8');
                        }
                        //$review = $review."row $row:".$data[0][2]."<br/>";
                        if ($data[0] != null && strcmp($data[0][0], '#') != 0 && ($row != 0 || strcmp($data[0][3], '#') != 0)) {
                            //print_r($data);
                            $data_tsv[] = $data;
                        }
                        $row++;
                    }

                    fclose($handle);
                }
                $review = '';
                $error = '';
                //echo $data_tsv[0][0][3];
                if ($data_tsv[0][0][3] != 'T' || !isset($data_tsv[0][1])) {
                    $error = $error . "Thieu Test title";
                } else {
                    $review = "<div name=\"TestTitle\">" . $data_tsv[0][1] . "</div>";

                    $row = 1;
                    $num = count($data_tsv);
                    if ($data_tsv[1][0] == "TestSunTitle") {
                        $review = $review . "<div name=\"TestSunTitle\">" . $data_tsv[1][1] . "</div>";
                        $row++;
                    }

                    //$review = $review.$num;
                    while ($row < $num) {
                        $numberQuestion = substr($data_tsv[$row][0], 2, -1);
                        if ($data_tsv[$row][0] == "End") {
                            break;
                        } else {
                            if ($data_tsv[$row][1] != "QS") {


                                $error = "Thieu noi dung cau hoi $numberQuestion.";
                                break;
                            } else {

                                $questionID = $data_tsv[$row][0];
                                $review = $review . "<div class=\"question\">問題 " . $numberQuestion . ":" . $data_tsv[$row][2] . "</div>";
                                $review = $review . "<ol>";
                                $row++;

                                while ($row < $num && $data_tsv[$row][0] == $questionID && $data_tsv[$row][1][0] == 'S') {
                                    $review = $review . "<li>";
                                    $review = $review . $data_tsv[$row][2];
                                    $review = $review . "</li>";
                                    $row++;
                                }
                                $review = $review . "</ol>";

                                if ($data_tsv[$row][1] != "KS" || !isset($data_tsv[$row][2]) || !isset($data_tsv[$row][3])) {
                                    $error = "Thieu ket qua cau hoi $numberQuestion.";
                                    break;
                                } else {
                                    $review = $review . "<div class=\"result\">結果：" . $data_tsv[$row][2][2] . "    " . $data_tsv[$row][3] . "点</div>";
                                    $row++;
                                }
                            }
                        }
                    }
                }
                $this->set('review', $review);
                //echo $review;
                if (strlen($error) != 0) {
                    unlink($link);
                    $this->Session->setFlash($error);
                } else {
                    
                }
                //$this->redirect(array('action' => 'review', '?' => array('filename' => $filename)));
            } else {
                //unlink($link);
                $this->Session->setFlash('There was a problem uploading file. Please try again.');
            }
        }
        $this->render('home');
    }

    function review() {
        $filename = $this->request->query['filename'];
        $link = $_SERVER['DOCUMENT_ROOT'] . DS . 'elearning' . DS . WEBROOT_DIR . DS . 'tsv' . DS . $filename;

        $data_tsv = array();

        if (($handle = fopen($link, "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                $num = count($data);
                for ($i = 0; $i < $num; $i++) {
                    $data[$i] = mb_convert_encoding($data[$i], 'utf-8');
                }
                //$review = $review."row $row:".$data[0][2]."<br/>";
                if ($data[0] != null && strcmp($data[0][0], '#') != 0 && ($row != 0 || strcmp($data[0][2], '#') != 0)) {
                    //print_r($data);
                    $data_tsv[] = $data;
                }
                $row++;
            }



            fclose($handle);
            $this->set('data', $data_tsv);
        }
        //print_r($data_tsv);
    }

    public function getViewTSV($filename = 'test3.tsv') {
      //  $filename = time() . $this->data['TSV']['file']['name'];
        $link = $_SERVER['DOCUMENT_ROOT'] . DS . 'elearning' . DS . WEBROOT_DIR . DS . 'tsv' . DS . $filename;
     
     //  $link = WEBROOT_DIR . DS . 'tsv' . DS . $filename;
        echo $link;

        $data_tsv = array();

        if (($handle = fopen($link, "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                $num = count($data);
                for ($i = 0; $i < $num; $i++) {
                    $data[$i] = mb_convert_encoding($data[$i], 'utf-8');
                }
                //$review = $review."row $row:".$data[0][2]."<br/>";
                if ($data[0] != null && strcmp($data[0][0], '#') != 0 && ($row != 0 || strcmp($data[0][3], '#') != 0)) {
                    //print_r($data);
                    $data_tsv[] = $data;
                }
                $row++;
            }

            fclose($handle);
        }
        $review = '';
        $error = '';
        //echo $data_tsv[0][0][3];
        if ($data_tsv[0][0][3] != 'T' || !isset($data_tsv[0][1])) {
            $error = $error . "Thieu Test title";
        } else {
            $review = "<div name=\"TestTitle\">" . $data_tsv[0][1] . "</div>";

            $row = 1;
            $num = count($data_tsv);
            if ($data_tsv[1][0] == "TestSunTitle") {
                $review = $review . "<div name=\"TestSunTitle\">" . $data_tsv[1][1] . "</div>";
                $row++;
            }

            //$review = $review.$num;
            while ($row < $num) {
                $numberQuestion = substr($data_tsv[$row][0], 2, -1);
                if ($data_tsv[$row][0] == "End") {
                    break;
                } else {
                    if ($data_tsv[$row][1] != "QS") {


                        $error = "Thieu noi dung cau hoi $numberQuestion.";
                        break;
                    } else {

                        $questionID = $data_tsv[$row][0];
                        $review = $review . "<div class=\"question\">問題 " . $numberQuestion . ":" . $data_tsv[$row][2] . "</div>";
                        $review = $review . "<ol>";
                        $row++;

                        while ($row < $num && $data_tsv[$row][0] == $questionID && $data_tsv[$row][1][0] == 'S') {
                            $review = $review . "<li>";
                            $review = $review . $data_tsv[$row][2];
                            $review = $review . "</li>";
                            $row++;
                        }
                        $review = $review . "</ol>";

                        if ($data_tsv[$row][1] != "KS" || !isset($data_tsv[$row][2]) || !isset($data_tsv[$row][3])) {
                            $error = "Thieu ket qua cau hoi $numberQuestion.";
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

}
