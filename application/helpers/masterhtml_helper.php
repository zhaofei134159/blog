<?php
class MasterHtml{
    
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
        $item  =  (empty($params['item'])?'all':$params['item']);
        $str     = '';
        $str .= "    <textarea id='".$id."' name='".$name."' style='width:".$width."px;height:".$height."px;'>    \r\n";
        $str .=  $value;
        $str .= "    </textarea>\r\n";
        $str .= "    <script>    \r\n";
        $str .= "      var editor_".$id.";    \r\n";
        $str .= "      KindEditor.ready(function(K) {    \r\n";
        $str .= "          editor_".$id." = K.create('#".$id."', {    \r\n";
        $str .= "          uploadJson : '/resource/master/js/kindeditor/php/upload_json.php',    \r\n";
        $str .= "          width: '$width',    \r\n";
        $str .= "          height: '$height',    \r\n";
        if($item == 'all'){
          	$str .= "items : [
                    		'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                    		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    		'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
                    		'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    		'anchor', 'link', 'unlink', '|', 'about'
                    	],\r\n";
        }else if($item = 'simple'){
            $str .= " items : [
              						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
              						'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
              						'insertunorderedlist', '|', 'emoticons', 'image', 'link'],\r\n";
        }
        $str .= "          fileManagerJson : '/resource/master/js/kindeditor/php/file_manager_json.php',    \r\n";
        $str .= "              afterBlur: function(){this.sync();}    \r\n";
        $str .= "          });    \r\n";
        $str .= "      });    \r\n";
        $str .= "    </script>    \r\n";
        echo $str;
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
        $width     = (empty($params['width'])?'160px;':$params['width']);
        $format     = (empty($params['format'])?'yyyy-MM-dd HH:mm:ss':$params['format']);
        $startDate     = (empty($params['startDate'])?'%y-%M-01 00:00:00':$params['startDate']);
        $str = '<input class="Wdate" style="width:'.$width.';" readonly="readonly" type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" onFocus="WdatePicker({startDate:\''.$startDate.'\',dateFmt:\''.$format.'\',alwaysUseStartDate:true})"/>';
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
    
}