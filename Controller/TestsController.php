<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tests
 *
 * @author DONG + THANHBQ
 */
class TestsController extends AppController {    

    public $components = array('Paginator','TsvReader');
    public function add() {
        $lesson_id = $this->params['named']['id'];
        $this->set('id', $lesson_id);
        if ($this->request->is('post'))
        {               
            $data = $this->request->data['Test'];           
            if (is_uploaded_file($data['link']['tmp_name'])) {
                $name = uniqid() . $data['link']['name'];
                move_uploaded_file($data['link']['tmp_name'], WWW_ROOT. "tsv" . DS . $name);
                $this->request->data['Test']['link'] = $name;
            }
            try {
                $this->TsvReader->getViewTSV($name);
                $this->Test->create();
                $this->request->data['Test']['lesson_id'] = $lesson_id;
                if($this->Test->save($this->request->data['Test'])){
                    $this->Session->setFlash(__('テストファイルがアップロードされた'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                } else {
                    $this->Session->setFlash(__('テストファイルをアップロードできない。もう一度お願い'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-warning'
                    )); 
                }
            } catch (Exception $e) {
                $this->Session->setFlash(__($e->getMessage()), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'));
                    unlink(WWW_ROOT . 'tsv' . DS . $name);
            }
            $this->redirect(array('controller' => 'lesson', 'action' => 'test', 'id' => $lesson_id));
        }
    }

    public function edit() {
        $test_id = $this->params['named']['id'];
        $this->set("id", $test_id);
        $results = $this->Test->find("first", array("conditions"=>array('Test.id'=>$test_id)));
        $this->set('result', $results['Test']);     

        if ($this->request->is('post'))
        {               
            $data = $this->request->data['Test'];
            $this->request->data['Test']['id'] = $test_id;      

            if (is_uploaded_file($data['link']['tmp_name'])) {              
                unlink(WWW_ROOT . DS . 'tsv'. DS . $results['Test']['link']);
                $name = uniqid() . $data['link']['name'];
                move_uploaded_file($data['link']['tmp_name'], WWW_ROOT . "tsv" . DS . $name);
                $this->request->data['Test']['link'] = $name;                                       
            } else {
                $results = $this->Test->find("first", array("conditions"=>array('id'=>$test_id)));
                $this->request->data['Test']['link'] = $results['Test']['link'];                
            }           

            if($this->Test->save($this->request->data['Test'])){
                $this->Session->setFlash(__('テストファイルが更新された'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));     

            } else {
                $this->Session->setFlash(__('テストファイルを更新できない。もう一度お願い'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
                )); 
            }

            $this->redirect(array('controller' => 'lesson', 'action' => 'test', 'id' => $test_id));     
        }
    }

    public function delete() {
        $id = $this->params['named']['id'];
        $data = $this->Test->find('first', $id);        
        
        if ($this->Test->delete($id)) {
            $this->Session->setFlash(__('テストが削除された'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            )); 
            return $this->redirect($this->referer());    
        }
    }

    public function show($id) {
        $data_tsv = $this->getDataTSV($id);
        $this->set('title', $data_tsv[0][1]);
        $this->set('content', $this->getContent($data_tsv));
        if ($this->request->is('post')) {
            $result = $this->data['Test']['result'];
            $row = 1;
            $error = "";
            $num = count($data_tsv);
            if ($data_tsv[1][0] == "TestSubTitle") {
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
                        $error = $numberQuestion."の質問の内容がない.";
                        break;
                    } else {
                        $questionID = $data_tsv[$row][0];
                        $row++;

                        while ($row < $num && $data_tsv[$row][0] == $questionID && $data_tsv[$row][1][0] == 'S') {
                            $row++;
                        }
                        if ($data_tsv[$row][1] != "KS") {
                            $error = $numberQuestion."の質問の内容がない.";
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

    public function list_result($test_id){
        $this->loadModel('Result');
        $this->Result->recursive = 1;
        $this->paginate = array(
            'fields' => array('Result.id', 'Student.full_name','Result.point'),
            'limit' => 10,
            'conditions' => array(
                'Result.test_id' => $test_id
           )
        );
        $results = $this->Paginator->paginate('Result');
        $this->set('results',$results);

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
        if ($data[1][0] == "TestSubTitle") {
            $test = $test . "<h3 name=\"TestSubTitle\">" . $data[1][1] . "</h3>";
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


                    $error = $numberQuestion."の質問の内容がない.";
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
                        $error =  $numberQuestion."の質問の内容がない.";
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
