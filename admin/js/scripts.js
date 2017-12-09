tinymce.init({ selector:'textarea' });

$('#select_all_boxes').click(function () {    
    $(':checkbox.checkBoxes').prop('checked', this.checked);    
});