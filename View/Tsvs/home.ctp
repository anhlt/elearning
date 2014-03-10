<script type="text/javascript">
function getExtension(filename) {
    return filename.split('.').pop().toLowerCase();
}
function checkFile(inputFile) {
    var valid_extensions = /(.tsv)$/i;
    if(!valid_extensions.test(inputFile.value)) {
        alert('Loai file ko hop le!');
        inputFile.value = '';
    } else if(inputFile.files[0].size > 2048){ //2M
        alert('kich thuoc file qua lon!');
        inputFile.value = '';
    }
}

</script>
<?php
if(isset($review)) {
    echo $review;
}

/* display message saved in session if any */
echo $this->Session->flash();
/* create form with proper enctype */
echo $this->Form->create('TSV', array('type' => 'file'));
/* create file input */
echo $this->Form->input('file',array( 'type' => 'file'));
/* create submit button and close form */
echo $this->Form->end('Submit');
?>