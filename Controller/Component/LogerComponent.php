<?php
class LogerComponent extends Component{
    public function writeLog($logType, $username, $role, $action, $other){
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        //        $folder = new Folder($folder_path, true, 777);
        //      $folder.create($folder_path);
        $today = getDate();
        $time = $today['hours'].":".$today['minutes'].":".$today['seconds'];
        $datetime = $today['year']."-".$today['mon']."-".$today['mday'];
        $folder_path = "logs/".$today['year']."/".$today['month']."/".$logType."/";
        shell_exec ("mkdir logs");
        shell_exec("chmod -R 777 logs");
        shell_exec("mkdir "."logs/".$today['year']);
        shell_exec("chmod -R 777 "."logs/".$today['year']);
        shell_exec("mkdir "."logs/".$today['year']."/".$today['month']);
        shell_exec("chmod -R 777 "."logs/".$today['year']."/".$today['month']);
        shell_exec("mkdir "."logs/".$today['year']."/".$today['month']."/".$logType);
        shell_exec("chmod -R 777 "."logs/".$today['year']."/".$today['month']."/".$logType);

        $file = new File($folder_path.$datetime.".log", true, "777");
        $file->append("[$logType][$time][$username][$role][$action][$other]\n");
    }
}
?>
