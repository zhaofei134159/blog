<?php
require_once 'pageclass_helper.php';

class mypage extends page{
    function mypage($array){
        parent::page($array);
        $this->first_page=1;
        $this->last_page=$this->totalpage;
        $this->set('format_left','');
        $this->set('format_right','');
    }

    function showAdmin_1(){
        $pagestr  = '<script>';
        $pagestr .= 'function pageselect(jumpurl,page){';
        $pagestr .= 'jumpurl = jumpurl + page;';
        $pagestr .= 'location.href = jumpurl; ';
        $pagestr .= '}';
        $pagestr .= '</script>';
        $pagestr .='<a class="">总计'.$this->totalpage.'页, 共'.$this->totalresult.'条记录</a>';
        $pagestr .= $this->show(1);
        return $pagestr;
    }


    function showWeb_1(){
        //$this->starttag = "<li>";
        //$this->endtag = "</li>";
        $pagestr = "";
        $pagestr .= "<div class='pagination'>";
        $pagestr .= $this->show(7);
        $pagestr .= "</div>";
        return $pagestr;        
    }


    function recipe_cat_list(){
        $this->starttag = "<li>";
        $this->endtag = "</li>";
        $pagestr = "";
        $pagestr .= "<ul>";
        $pagestr .= $this->show(7);
        $pagestr .= "</ul>";
        return $pagestr;
    }    

    function works_list(){
        $this->starttag = "<li>";
        $this->endtag = "</li>";
        $pagestr = "";
        $pagestr .= "<ul>";
        $pagestr .= $this->show(7);
        $pagestr .= "</ul>";
        return $pagestr;
    }    


}
?>