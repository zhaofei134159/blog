<?php
class Html{
    
    /*
    function __construct(){
        $this->ci= & get_instance();
    }
    */

    public static function kindeditor($params = array()) {
        $id      =  (empty($params['id'])?'':$params['id']);
        $name    =  (empty($params['name'])?'':$params['name']);
        $value   =  (empty($params['value'])?'':$params['value']);
        $width   =  (empty($params['width'])?'98%':$params['width']);
        $height  =  (empty($params['height'])?'300':$params['height']);
        $str     = '';
        $str .= "    <textarea id='".$id."' name='".$name."' style='width:".$width."px;height:".$height."px;'>    \r\n";
        $str .=  $value;
        $str .= "    </textarea>\r\n";
        $str .= "    <script>    \r\n";
        $str .= "      var editor_".$id.";    \r\n";
        $str .= "      KindEditor.ready(function(K) {    \r\n";
        $str .= "          editor_".$id." = K.create('#".$id."', {    \r\n";
        $str .= "          uploadJson : '/resource/kindeditor-4.1.7/php/upload_json.php',    \r\n";
        $str .= "          width: '$width',    \r\n";
        $str .= "          height: '$height',    \r\n";
        $str .= "          fileManagerJson : '/resource/kindeditor-4.1.7/php/file_manager_json.php',    \r\n";
        $str .= "              afterBlur: function(){this.sync();}    \r\n";
        $str .= "          });    \r\n";
        $str .= "      });    \r\n";
        $str .= "    </script>    \r\n";
        echo $str;
    }


    /*
     * FCKEDITOR编辑器
    */
    public static function showFck($params = array()){
        $id       = (empty($params['id'])?'':$params['id']);
        $name     = (empty($params['name'])?'':$params['name']);
        $value    = (empty($params['value'])?'':$params['value']);
        $width    = (empty($params['width'])?'98%':$params['width']);
        $height   = (empty($params['height'])?'':$params['height']);
        $toolbar  = (empty($params['toolbar'])?'Full':$params['toolbar']);
        echo "<textarea name='".$name."' id='".$id."'>".$value."</textarea> \n"; 
        echo "<script type='text/javascript'> \n"; 
        echo "CKEDITOR.replace( '".$name."', \n"; 
        echo "{ \n"; 
        echo "filebrowserBrowseUrl : '/public/ckfinder/ckfinder.html', \n"; 
        echo "filebrowserImageBrowseUrl : '/public/ckfinder/ckfinder.html?Type=Images', \n"; 
        echo "filebrowserFlashBrowseUrl : '/public/ckfinder/ckfinder.html?Type=Flash', \n"; 
        echo "filebrowserUploadUrl : '/public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files', \n"; 
        echo "filebrowserImageUploadUrl : '/public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images', \n"; 
        echo "filebrowserFlashUploadUrl : '/public/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash' \n"; 
        echo "}); \n"; 
        echo "</script> \n";
    }
    
    /*
     * Kindeditor 编辑器
     */
    public static function showKindeditor($params){
        $name   = (empty($params['name'])?'':$params['name']);
        $value  = (empty($params['value'])?'':$params['value']);
        $id     = (empty($params['id'])?'':$params['id']);
    ?>
		    <textarea id="<?php echo $id;?>" name="<?php echo $name;?>" style="width:100%;height:300px;">
		      <?php echo htmlspecialchars($value); ?>
		    </textarea>
		    <script>
				KE.show({
					id : '<?php echo $id;?>',
					allowFileManager : true
				});
		    </script>
    <?php
    }

    
    /*
     * input时间控件
    */
    public static function showInputDateTime($params){
        $name     = (empty($params['name'])?'':$params['name']);
        $value     = (empty($params['value'])?'':$params['value']);
        $id     = (empty($params['id'])?'':$params['id']);
        $format     = (empty($params['format'])?'yyyy-MM-dd HH:mm:ss':$params['format']);
        $startDate     = (empty($params['startDate'])?'%y-%M-01 00:00:00':$params['startDate']);
        $str = '<input class="Wdate" style="width:160px;" readonly="readonly" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" onFocus="WdatePicker({startDate:\''.$startDate.'\',dateFmt:\''.$format.'\',alwaysUseStartDate:true})"/>';
        return $str;
    }
	
	/*
     * input时间控件（短）
    */
    public static function shortInputDateTime($params){
        $name     = (empty($params['name'])?'':$params['name']);
        $value     = (empty($params['value'])?'':$params['value']);
        $id     = (empty($params['id'])?'':$params['id']);
        $format     = (empty($params['format'])?'yyyy-MM-dd HH:mm:ss':$params['format']);
        $startDate     = (empty($params['startDate'])?'%y-%M-01 00:00:00':$params['startDate']);
        $str = '<input class="Wdate" style="width:90px;" readonly="readonly" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" onFocus="WdatePicker({startDate:\''.$startDate.'\',dateFmt:\''.$format.'\',alwaysUseStartDate:true})"/>';
        return $str;
    }
 
    /*
     * select控件
    */
    public static function getSelect($params){
        $datas = $params['datas'];
        $defaultvalue = $params['value'];
        $fieldname = $params['fieldname'];
        $name = $params['name'];
        $id = $params['id'];
        $strselects = "";
        $strselects .= "<select name='".$name."' id='".$id."'>\r\n";
        $strselects .= "<option value=''>请选择</option>\r\n";
        $selected = "";
        foreach($datas as $item){
            $item['id']==$defaultvalue?$selected = "selected": $selected = '';
            empty($item['tag'])? $tag = '' : $tag = $item['tag'];
            $strselects .= "<option value='".$item['id']."' ".$selected.">".$tag.$item[$fieldname]."</option>\r\n";
        }
        $strselects .= "\r\n";
        $strselects .= "</select>\r\n";
        return $strselects;
    }


    /*
     * 构造复选框
     */
    public static function showChoiceCheckbox($tablename = '', $name = '', $value = '', $controname = '', $defaultvalues = '', $where = '', $order = ''){
        $CI = & get_instance();
        if(empty($where)) $where = "`id` > 0";
        if(empty($order)) $order = "`id` DESC";
        $value_arr      = array();
        if(!empty($defaultvalues)){
            $value_arr  = explode(',', $defaultvalues);
        }
        $str  = "";
        if(empty($tablename) || empty($name)){
            die('myerror:Parameter is empty');
        }
        list($i, $m, $n) = explode('_', $tablename);		
        $model = ucfirst($m) . "_" . ucfirst($n)."_Model";
        $function = "getAll";
        $CI->load->model(''.$model.'');
        $records = $CI->$model->$function($where, "*", $order);
        $str = "<input type='hidden' name='form[".$controname."]' id='".$controname."' value='".$defaultvalues."'>";
        foreach($records as $key => $v){
            $checked = "";
            if(in_array($v[''.$value.''],$value_arr)) $checked = "checked";
            $str .= "<input type='checkbox' name='".$controname."checks' id='".$controname."checks' value='".$v[''.$value.'']."' ". $checked ." onclick=\"ChangeCheckbox('".$controname."checks', '".$controname."')\">";
            $str .= $v[''.$name.'']. "\n"; 
        }
        echo $str;
    }
   
}