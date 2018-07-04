<?php 
    $this->load->view('home/public/blog_header');
?>
  
        <div class="widewrapper subheader">
            <div class="container">
                <div class="clean-breadcrumb" style="font-size:20px;">
                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>" style="margin:0 5px">首页</a>
                    <span class="separator">&#x2F;</span>
                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>/art_list">文章列表</a>
                </div>

                <div class="clean-searchbox">
                    <!-- <form action="#" method="get" accept-charset="utf-8">
                        <input class="searchfield" id="searchbox" type="text" placeholder="Search">
                        <button class="searchbutton" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </form> -->
                </div>
            </div>
        </div>
    </header>

    <div class="widewrapper main" style="min-height:500px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 blog-main">
                    <div class="row">
                        <div class="col-md-1">分类：</div>
                        <div class="col-md-11">
                            <ul class="tags">
                                <?php 
                                    if(!empty($cates)){
                                        foreach($cates as $ck=>$cate){
                                ?>
                                <li>
                                    <a href="javascript:;" <?=($cate['id']==$c)?'style="border:solid 1px black;"':''?> onclick="click_search('cate',<?=$cate['id']?>)"><?=$cate['title']?></a>
                                </li>
                                <?php            
                                        }
                                    }
                                ?> 
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">标签：</div>
                        <div class="col-md-11">
                            <ul class="tags">
                                <?php 
                                    $tagarr = explode(',',$t);
                                    if(!empty($tags)){
                                        foreach($tags as $ck=>$tag){
                                ?>
                                <!-- <?=HOME_URL?>blog/<?=$blog['id']?>/art_list?tag=<?=$tag['']?> -->
                                <li>
                                    <a href="javascript:;" <?=(in_array($tag['id'],$tagarr))?'style="border:solid 1px black;"':''?> onclick="click_search('tag',<?=$tag['id']?>)"><?=$tag['name']?></a>
                                </li>
                                <?php            
                                        }
                                    }
                                ?> 
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <?php 
                            if(!empty($works)){
                                foreach($works as $wk=>$work){
                        ?>
                        <div class="col-md-3 col-sm-3">
                            <article class="blog-teaser" style="min-height:275;">
                                <header>
                                    <?php if(!empty($work['img'])){?>
                                    <img src="/<?=$work['img']?>"  style="width:100%;height:150px;" alt="">
                                    <?php }else{?>
                                    <img src="/public/public/workimg/2014312311231233123.jpg"  style="width:100%;height:150px;" alt="">
                                    <?php }?>
                                    <br>
                                    <h3 style="overflow: hidden;"><a href="<?=HOME_URL?>blog/<?=$blog['id']?>/detail/<?=$work['id']?>"><?=mb_substr($work['title'],0,6,'utf-8');?></a></h3>
                                    <hr>
                                </header>
                                <div class="clearfix">
                                    <span style="font-size:14px;padding-left:20px;">点击量：<?=empty($work['browse_num'])?0:$work['browse_num']?></span>
                                    <a href="<?=HOME_URL?>blog/<?=$blog['id']?>/detail/<?=$work['id']?>" class="btn btn-clean-one">详情</a>
                                </div>
                            </article>
                        </div>
                        <?php 
                                }
                            }
                        ?>
                    </div>
                    <?=$work_htm?>
                </div>
                
            </div>
        </div>
    </div>
    <input type="hidden" name="cate" value="<?=$c?>">
    <input type="hidden" name="tag" value="<?=$t?>">
    <script type="text/javascript"> 
        
        function click_search(type,id){
            var cate = $('input[name="cate"]').val();
            var tag = $('input[name="tag"]').val();
            var get = '';

            if(type=='cate'){
                if(cate.indexOf(id) != -1){
                    get += 'cate='+cate.replace(id,"")+'&tag='+tag;
                }else{
                    get += 'cate='+id+'&tag='+tag;
                }
            }

            if(type=='tag'){
                if(cate!=''){
                    if(tag.indexOf(id) != -1){
                        get += 'cate='+cate+'&tag='+tag.replace(id+',',"");
                    }else{
                        console.log(2);
                        get += 'cate='+cate+'&tag='+id+','+tag;
                    }
                }else{
                    if(tag.indexOf(id) != -1 ){
                        get += 'cate=&tag='+tag.replace(id+',',"");
                    }else{
                        get += 'cate=&tag='+id+','+tag;
                    }
                }
            }   

            
            window.location.href = '/home/blog/<?=$blog["id"]?>/art_list'+'?'+get;
        }
        // function getSearch(key){
        //     var res = new Array();
        //     var search = window.location.search.slice(1);
        //     var arr = search.split("&"); 
        //     for (var i = 0; i < arr.length; i++) { 
        //         var ar = arr[i].split("="); 
        //         res.push(ar);
        //     }
        //     return res;
        // }
    </script>

<?php 
    $this->load->view('home/public/blog_footer');
?>