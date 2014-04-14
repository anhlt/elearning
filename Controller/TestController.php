<?php
class TestController extends AppController {
	var $name = 'Test';
	var $uses = array('Test', 'Result');

	public function add() {
		$lesson_id = $this->params['named']['id'];
		$this->set('id', $lesson_id);

		if ($this->request->is('post'))
		{				
			$data = $this->request->data['Test'];			
			
			if (is_uploaded_file($data['link']['tmp_name'])) {
				$name = $data['link']['name'];
				move_uploaded_file($data['link']['tmp_name'], WWW_ROOT."tsv/".$name);
				$this->request->data['Test']['link'] = $name;										
			} 

			$this->Test->create();
			$this->request->data['Test']['lesson_id'] = $lesson_id;

			if($this->Test->save($this->request->data['Test'])){
                $this->Session->setFlash(__('The test file has been uploaded'), 'alert', array(
	                'plugin' => 'BoostCake',
	                'class' => 'alert-success'
            	));            	
				
				$this->redirect(array('controller' => 'lesson', 'action' => 'test', 'id' => $lesson_id));

			} else {
                $this->Session->setFlash(__('The testfile could not be uploaded. Plz try again'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-warning'
            	));	
			}
		}
	}

	public function edit() {
		$test_id = $this->params['named']['id'];
		//var_dump($test_id);		
		$this->set("id", $test_id);
		$results = $this->Test->find("first", array("conditions"=>array('id'=>$test_id)));
		$this->set('result', $results['Test']);		

		if ($this->request->is('post'))
		{				
			$data = $this->request->data['Test'];
			$this->request->data['Test']['id'] = $test_id;		

			if (is_uploaded_file($data['link']['tmp_name'])) {
				$name = $data['link']['name'];
				unlink(WWW_ROOT.DS.$results['Test']['link']);
				move_uploaded_file($data['link']['tmp_name'], WWW_ROOT."course/test/".$name);
				$this->request->data['Test']['link'] = "course/test/".$name;										
			} else {
				$results = $this->Test->find("first", array("conditions"=>array('id'=>$test_id)));
				$this->request->data['Test']['link'] = $results['Test']['link'];				
			}			

			if($this->Test->save($this->request->data['Test'])){
                $this->Session->setFlash(__('The test file has been update'), 'alert', array(
	                'plugin' => 'BoostCake',
	                'class' => 'alert-success'
            	));		

			} else {
                $this->Session->setFlash(__('The testfile could not be update. Plz try again'), 'alert', array(
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
    		unlink(WWW_ROOT.DS.$data['Test']['link']);
            $this->Session->setFlash(__('The test has been deleted'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            )); 
            
            return $this->redirect($this->referer());      	
    	}
    }

    public function index() {

        $data_tsv = $this->getDataTSV();

        $this->set('test', $this->getTest());
        $this->set('data', $data_tsv);
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


            $this->Result->updateResult($this->Auth->user('id'), '1', $result, $point);
            //echo $sumQuestion;
            if (strlen($error) != 0) {
                echo $error;
            }
            $this->redirect(array('action' => 'result'));
        }
        $this->render('home');
    }

    public function result() {
        $dataTest = $this->Result->find('first', array('conditions' => array('student_id' => $this->Auth->user('id'), 'test_id' => '1')));
        $this->set('result', $dataTest['Result']['student_of_choice']);
        $this->set('data', $this->getDataTSV());
    }

    private function getDataTSV($filename = 'test3.tsv') {
        $link = $_SERVER['DOCUMENT_ROOT'] . DS . WEBROOT_DIR . DS . 'tsv' . DS . $filename;

        $data_tsv = array();
        var_dump($link);

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

    private function getTest($filename = 'test3.tsv') {
        $data = $this->getDataTSV($filename);
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
?>
