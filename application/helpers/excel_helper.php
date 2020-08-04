<?php
class Excel{
    
    function __construct(){
        $CI =& get_instance();
        $CI->load->library('phpexcel/PHPExcel');
        $CI->load->library('phpexcel/PHPExcel/IOFactory');
    }

   /*
     * 读取EXCEL文件，返回数组
    */
    public static function getValues($excelFile, $exceType = 'Excel5', $sheet = 0, $startRow = 1){     
        $CI =& get_instance();
        $CI->load->library('phpexcel/PHPExcel');
        $CI->load->library('phpexcel/PHPExcel/IOFactory');
        $objReader = IOFactory::createReader($exceType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($excelFile); //档案名称
        $objWorksheet = $objPHPExcel->getActiveSheet($sheet); //读取第一个工作表(编号从0开始)
        $highestRow = $objWorksheet->getHighestRow();  //总行数
        $highestColumn = $objWorksheet->getHighestColumn();  //最高列数
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  //总列数
        $excelVals = array();
        for ($row = $startRow; $row <= $highestRow; $row++) {
            $rowVals = array();
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                $rowVals[] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            $excelVals[] = $rowVals;
        }
        return $excelVals;
    }
    
    /*
    * 生成EXCEL
    */
    public static function create($titles = array(), $vals = array(), $filename = false){
        $CI =& get_instance();
        $CI->load->library('phpexcel/PHPExcel');
        $CI->load->library('phpexcel/PHPExcel/IOFactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        //表头
        foreach($titles as $k => $v){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($k, 1, $v);
        }
        //数据
        foreach($vals as $k => $v){
            $i = -1;
            foreach($v as $item){
                $i++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $k+2, $item);  
            }
        }
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
        if(!$filename) $filename = 'file_'.date('YmdHis');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }
    
}