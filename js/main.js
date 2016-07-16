$(function(){
     
	// $(".birthday").calendar({
	//     value: ['2015-12-05']
	// });
	var root='http://oflow.applinzi.com';
	/******主页操作******/
	function template(tmpid,data,target){
		var source=$(tmpid).html();
		if(source!=null){
			var tmp=Handlebars.compile(source);
			var html=tmp(data);
			$(target).html(html);	
		}
	} 
	
	/**
	 * [askAjax 所有问题ajax] 
	 * @param  {[type]} or 排序规则
	 * @return {[type]}    [description]
	 */
	function askAjax(da,url,tmpid,target){
		$.ajax({
			type:'post',
			url:root+'/knowhow/index.php/Home/Ask/'+url,
			data:da,
			success:function(data){
				console.log(data);
				template(tmpid,data,target);
			}
		})
	}

	$('.new').css("color","red");	
	$(".ask").load(root+'/tmp/asktmp.html',function(){//初始化问题
		askAjax({'order':'newest'},'queryAllAsk',"#asktmp",".ask");
	});
	$(document).on("pageAnimationStart",'#router',function() {
    	$('.new').css("color","red");	
		$(".ask").load(root+'/tmp/asktmp.html',function(){//初始化问题
			askAjax({'order':'newest'},'queryAllAsk',"#asktmp",".ask");
		});
    });

	var aid;
	$(document).on("click",".askitem",function(){//获取问题id
		aid=$(this).attr("data-aid");
		var viewbox=$(this).find('.viewcount');
		// console.log($(this).find('.viewcount'));
		console.log(aid);
		if(viewbox.length!=0){
			var view=viewbox.text().trim();
			$.ajax({//浏览量+1
				type:'post',
				url:root+'/knowhow/index.php/Home/Ask/upAskView',
				data:{
					askid:aid
				},
				success:function(data){
					// console.log(data);
					if(view.indexOf('k')>-1){//大于1000的情况
						var num=((data[0].viewcount/1000).toFixed(1)+'k');
						viewbox.text(num);
					}else if(view.indexOf('m')>-1){//大于1000000的情况
						var num=((data[0].viewcount/1000000).toFixed(1)+'m');
						viewbox.text(num)
					}else{
						viewbox.text((view)*1+1);
					}
				}
			})
		}
	})
    $(document).on("pageAnimationStart",'#askinfo',function() {//加载问题详情模版
    	$(".askinfo_list").load(root+'/tmp/askinfo_tmp.html',function(){
    		askAjax({'askid':aid,order:'zan-cai'},'queryAskinfo',"#askinfotmp",".askinfo_list");
    	});
    });

	$(document).on("touchstart",".hot",function(){//最热
		$(".asklist a").css("color","#0894EC");
		$(this).css("color","red");
		$(".ask").load(root+'/tmp/asktmp.html',function(){
			// askAjax('frequent');
			askAjax({'order':'frequent'},'queryAllAsk',"#asktmp",".ask");
		});
		
	})
    $(document).on("touchstart",".new",function(){//最新
    	$(".asklist a").css("color","#0894EC");
		$(this).css("color","red");
		$(".ask").load(root+'/tmp/asktmp.html',function(){
			// askAjax('newest');
			askAjax({'order':'newest'},'queryAllAsk',"#asktmp",".ask");
		});
		
	})


	$(document).on("touchstart", ".dark", function() {//夜间模式
		if($("body").attr("class").indexOf("theme-dark")>-1){
			$("body").removeClass("theme-dark");
		}else{
			$("body").addClass("theme-dark");
		}
	});

    var uid;
    $(document).on('pageInit','#router',function(){
    	$(".tagbox").load(root+'/tmp/tag_tmp.html',function(){//初始化标签
			$.ajax({
				type:'post',
				url:root+'/knowhow/index.php/Home/Tag/queryTag',
				success:function(data){
					// console.log(data);
					template("#allTag",data,".tagbox");
				}
			})
		});
    	if(localStorage.getItem('userinfo')!=null){//登录情况
		    var rage=JSON.parse(localStorage.getItem('userinfo'));//取出用户id
			uid=rage.userid;
			$('.unick').text(rage.nickname);
	    	$('.login').css('display','none');
	        $('.logout').css('display','block');
	        // $('.home_page_btn').removeClass('external');
	    }else{//未登录情况
	    	$('.login').css('display','block');
	    	$('.logout').css('display','none');
	    	// $('.home_page_btn').addClass('external');
	    };
    });
	$(document).on("touchstart",".home_page_btn",function(e){//个人主页
		e.preventDefault();
		if($("body").attr("class").indexOf("theme-dark")>-1){
			$("body").addClass("theme-dark");
		}
		setTimeout(function(){
			$("body").removeClass("with-panel-right-reveal");
			$("#panel-right-demo").removeClass("active");
		},200);
		if(localStorage.getItem('userinfo')!=null){
			var rage=JSON.parse(localStorage.getItem('userinfo'));
			uid=rage.userid;
  			$('.edit_userinfo_btn').css('display','block');

		}
	})
	$(document).on("touchstart",".login",function(){
		$("body").removeClass("with-panel-right-reveal");
			if($("body").attr("class").indexOf("theme-dark")>-1){
				$("body").addClass("theme-dark");
			}
		$("#panel-right-demo").removeClass("active");
		
	})
	$(document).on("touchstart",".reload",function(){//刷新页面
		window.location.reload();
	})

	$(document).on('touchstart','.alert-text',function(){//退出登录
        $.modal({
        	title:'确定要退出登录吗?',
        	buttons:[
        		{
        			text:'取消',
        			onClick: function() {
			        	$.closeModal();
			        }
        		},
        		{
        			text:'确定',
        			onClick: function() {
        				localStorage.removeItem('userinfo');
			        	window.location.reload();
			        }
        		}
        	]
        })
        
    });


    $(document).on('refresh', '.pull-to-refresh-content',function(e){//下拉刷新
    	setTimeout(function(){
    		$(".ask").load(root+'/tmp/asktmp.html',function(){//初始化问题
				askAjax({'order':'newest'},'queryAllAsk',"#asktmp",".ask");
			});
	    	$(".asklist a").css("color","#0894EC");
			$(".new").css("color","red");
	    	$.pullToRefreshDone('.pull-to-refresh-content');
    	},2000)
	});
	
 	$(document).on('touchstart','.addaskbtn',function(){
  		if(localStorage.getItem('userinfo')==null){
  			hint();
  		}else{
  			$(this).addClass('open-popup open-about');
  		}
  	})
    $(document).on('touchstart','.addbtn',function(){//发布问题
    	var tit=$('.ask_title').val();
    	var taid=$('.tagbox').val();
    	var con=$(".asktex").val();
    	if(tit!=""&&con!=""){
    		$('.addbtn').addClass('close-popup');
	    	$.ajax({
	    		type:'post',
	    		url:root+'/knowhow/index.php/Home/Ask/addAsk',
	    		data:{
	    			title:tit,
	    			tagid:taid,
	    			content:con,
	    			uid:1
	    		},
	    		success:function(data){
	    			// console.log(data);
	    			$(".box").load(root+'/tmp/asktmp.html',function(){
		    			var source=$('#asktmp').html();
						if(source!=null){
							var tmp=Handlebars.compile(source);
							var html=tmp(data);
							$('.ask').prepend(html);
							// $('.addbtn').addClass('close-popup');
							$.alert('提交成功');
						}
					});
	    		}
	    	})
    	}else{
    		$('.addbtn').removeClass('close-popup');
    		$.alert("标题和内容必须填哦");
    	}
    });

    /****askinfo操作****/
    function askopt(askid,ot,zan,cai,url,answerid=1){
    	$.ajax({
    		type:'post',
    		url:root+'/knowhow/index.php/Home/'+url,
    		data:{
    			aid:askid,
    			opt:ot,
    			uid:1,
    			ansid:answerid
    		},
    		success:function(data){
    			// console.log(data);
    			if(data){
	    			if(ot=='zan'){
	    				console.log(data[0].zan);
	    				if(zan.text().indexOf('k')>-1){//大于1000的情况
	    					var num=((data[0].zan/1000).toFixed(1)+'k');
	    					zan.text(num)
	    				}else{
	    					zan.text((zan.text())*1+1);
	    				}
	    			}else if(ot=='cai'){
	    				console.log(data[0].cai);
	    				if(cai.text().indexOf('k')>-1){//大于1000的情况
	    					var num=((data[0].cai/1000).toFixed(1)+'k');
	    					cai.text(num)
	    				}else{
	    					cai.text((cai.text())*1+1);
	    				}
	    			}
    				$.alert('操作成功');
    			}else{
    				$.alert('操作失败');
    			}
    		},
    		error:function(XMLHttpRequest, textStatus, errorThrown) {
				if(XMLHttpRequest.status==404){
					$.alert('只能对同一个问题或回答操作一次');
				}
			}

    	})
    }

  	$(document).on('touchstart','.askopt',function(){//问题的赞,踩
  		if(localStorage.getItem('userinfo')==null){
  			hint();
  		}else{
    		modal(aid,$('.zannum'),$('.cainum'),'Ask/askOpt');
  		}

  	})

  	$(document).on('touchstart','.ansopt',function(){//回答的赞踩
  		var ansid=$(this).attr('data-ansid');
  		var zan=$(this).children().eq(1);
  		var cai=$(this).children().eq(3)
 		if(localStorage.getItem('userinfo')==null){
  			hint();
  		}else{
    		modal(aid,zan,cai,'Answer/answerOpt',ansid);
  		}
  		
  	})
  	function modal(askid,zan,cai,url,answerid){
  		console.log(answerid);
  		if(answerid==undefined){
  			answerid=1;
  		}
  		$.modal({
  			title:"请选择操作",
  			buttons:[
  				{
  					text:'赞',
  					bold:true,
  					onClick:function(){
  						askopt(askid,'zan',zan,cai,url,answerid); 						
  					}
  				},
  				{
  					text:'踩',
  					bold:true,
  					onClick:function(){
  						askopt(askid,'cai',zan,cai,url,answerid);
  					}
  				},
  				{
  					text:'取消',
  					bold:true,
  					onClick:function(){
  						$.closeModal();
  					}
  				}
  			]
  		})
  	}

  	$(document).on('touchstart','.addAnswerbtn',function(){
  		if(localStorage.getItem('userinfo')==null){
  			hint();
  		}else{
  			$(this).addClass('open-popup open-about');
  		}
  	})
 
  	$(document).on('touchstart','.addAnswer',function(){//发布回答
  		var txt=$('.anstxt').val();
  		if(txt!=""){
  			$('.addAnswer').addClass('close-popup');
	  		$.ajax({
	  			type:'post',
	  			url:root+'/knowhow/index.php/Home/Answer/addAnswer',
	  			data:{
	  				askid:aid,
	  				content:txt,
	  				uid:uid
	  			},
	  			success:function(data){
	  				console.log(data);
	  				$(".box").load(root+'/tmp/ans_tmp.html',function(){
		    			var source=$('#ans_tmp').html();
		    			console.log(source);
						if(source!=null){
							var tmp=Handlebars.compile(source);
							var html=tmp(data);
							$('.anslist').prepend(html);
							$('.ansnum').text(($('.ansnum').text())*1+1);
							$.alert('提交成功');
						}
					});
	  			}
	  		})
  			
  		}else{
  			$('.addAnswer').removeClass('close-popup');
  			$.alert("内容不能为空");
  		}
  	})
  	/****all_tag.html操作****/
  	$(document).on('touchstart','.tagdown',function(){//展开标签详情
  		var taginfo=$(this).parent().parent().parent().next()
  		if($(this).attr('class').indexOf('icon-down')>-1){
	  		taginfo.show('slow');
	  		$(this).attr('class','icon icon-up tagdown');
  		}else{
  			taginfo.hide('slow');
	  		$(this).attr('class','icon icon-down tagdown');
  		}
  	})

  	function tagAjax(url){
  		$('.all_tag_list').load(root+'/tmp/all_tag_tmp.html');
  		$.ajax({
  			type:'post',
  			url:root+'/knowhow/index.php/Home/Tag/'+url,
  			success:function(data){
  				// console.log(data);
  				template("#all_tag_tmp",data,".all_tag_list");
  			}
  		})
  	}
  	$(document).on('pageAnimationStart','#all_tag',function(){//加载所有标签
  		$('.anum').css('color','red');
  		$('.word').css('color','#0894EC');
  		tagAjax('showTagByAskNum');
  	})
  	$(document).on('touchstart','.word',function(){//字母排序
  		$('.tagbox a').css('color','#0894EC'),
  		$(this).css('color','red');
        tagAjax('showTagByName');
  	})
  	$(document).on('touchstart','.anum',function(){//问题数排序
  		$('.tagbox a').css('color','#0894EC'),
  		$(this).css('color','red');
        tagAjax('showTagByAskNum');
  	})
  	var taid;
  	var tagname;
  	$(document).on('touchstart','.taglist',function(){
  		taid=$(this).attr('data-tagid');
  		tagname=$(this).attr('title');
  		// console.log(taid,tagname);
  	})
	$(document).on('pageAnimationStart','#tag_ask',function(){//加载标签对应的问题
		$(".ask").load(root+'/tmp/asktmp.html',function(){//初始化问题
			$('.tag_title').text(tagname);
			$.ajax({
	  			type:'post',
	  			url:root+'/knowhow/index.php/Home/Ask/showAskByTag',
	  			data:{
	  				tagid:taid
	  			},
	  			success:function(data){
	  				// console.log(data);
	  				if(data){
	  					template("#asktmp",data,".ask");
	  				}else{
	  					$.alert('这个标签还没有问题');
	  				}
	  				
	  			}
	  		})		
		});
		
  	})

  	function hint(){
  		$.modal({
	    	title:'你还没登录,是否登录?',
	    	buttons:[
	    		{
	    			text:'取消',
	    			onClick: function() {
			        	$.closeModal();
			        }
	    		},
	    		{
	    			text:'确定',
	    			onClick: function() {
	    				location.href=root+'/page/login.html';
			        }
	    		}
	    	]
		})
  	}
	/****home_page.html操作****/
	$(document).on('touchstart','.user',function(){
		uid=$(this).attr('data-uid');
	})

	$(document).on('pageAnimationStart','#home_page',function(e, pageId, $page){//加载个人信息
		console.log(uid);
		if(uid!=undefined){
			if(localStorage['userinfo']!=undefined){
				if(uid!=JSON.parse(localStorage['userinfo']).userid){//其他用户编辑按钮隐藏
					$('.edit_userinfo_btn').css('display','none');
				}else{
					$('.edit_userinfo_btn').css('display','block');//登录用户编辑按钮显示
				}
			}
			perinfo(uid);
		}else{
			$.confirm("你还没登录,是否登录",function(){
				location.href="login.html";
			},function(){
				location.href="../index.html";
			})
			// hint();
		}
			
  	})
  	function perinfo(userid){
  		$(".infolist").load(root+'/tmp/home_page_tmp.html',function(){
			$.ajax({
	  			type:'post',
	  			url:root+'/knowhow/index.php/Home/User/showOneUser',
	  			data:{
	  				uid:userid
	  			},
	  			success:function(data){
	  				console.log(data);
	  				var des=data[0].Userinfo.des;
	  				$('.des').text(des);//个人简介
	  				template('#home_page_tmp',data,".infolist");
	  			}
	  		})		
		});
  	}

  	var nick;
  	$(document).on('touchstart','.myasnum',function(){
  		nick=$('.nickname').text();
  	})
    $(document).on('pageAnimationStart','#my_ask_ans',function(){//加载个人问题和回答
    	console.log(uid);
    	$(".ask_ans_tab").load(root+'/tmp/my_ask_ans_tmp.html',function(){
			$.ajax({
	  			type:'post',
	  			url:root+'/knowhow/index.php/Home/Ask/showAskByUser',
	  			data:{
	  				uid:uid
	  			},
	  			success:function(data){
	  				console.log(data);
	  				$('.ask_ans_btn a').removeClass('active');
	  				$('.ask_tab').addClass('active');
	  				$('.nicktitle').text(nick+'的提问和回答');
	  				template('#my_ask_ans_tmp',data,".ask_ans_tab");
	  			}
	  		})		
		});
    })
    /***login.html操作****/
    $(document).on('touchstart','.regbtn',function(e){//注册
    	var user=$('.regusername').val();
    	var pass=$('.regpass').val();
    	var repass=$('.regrepass').val();
    	var nick=$('.regnickname').val();
    	if(pass==repass){
	    	$.ajax({
	    		type:'post',
	    		url:root+'/knowhow/index.php/Home/User/reg',
	    		data:{
	    			username:user,
	    			password:pass,
	    			nickname:nick
	    		},
	    		success:function(data){
	    			console.log(data,typeof (data*1));
	    			if(!isNaN(parseInt(data))){
		    			var obj={'userid':data,'nickname':nick};
		    			var ragedata=JSON.stringify(obj);
		    			localStorage.setItem('userinfo',ragedata);
		    			$('.unick').text(nick);
		    			$('.login').css('display','none');
		    			$('.logout').css('display','block');
		    			$.showPreloader("注册中");
					    setTimeout(function () {
					        $.hidePreloader();
			    			$.confirm('注册成功,是否完善个人资料',function(){
			    				location.href="userinfo.html";
			    			},function(){
			    				location.href="../index.html";
			    			});
					    }, 2000);
	    			}else if(typeof data =='string'){
	    				$.toast(data);
	    			}
	    		}
	    	});
    	}else{
    		$.toast('两次输入密码不一致');
    	}
    })

    $(document).on('touchstart','.loginbtn',function(){//登录
    	var user=$('.logusername').val();
    	var pass=$('.logpass').val();
    	$.ajax({
    		type:'post',
    		url:root+'/knowhow/index.php/Home/User/login',
    		data:{
    			username:user,
    			password:pass
    		},
    		success:function(data){
    			console.log(data);
    			if(data){
	    			var obj={'userid':data[0].id,'nickname':data[0].Userinfo.name};
	    			var ragedata=JSON.stringify(obj);
	    			console.log(ragedata);
	    			localStorage.setItem('userinfo',ragedata);
	    			console.log($('.unick'));
	    			$('.unick').text(data[0].Userinfo.name);
	    			$('.login').css('display','none');
		    		$('.logout').css('display','block');
		    		$.showPreloader('登陆中');
				    setTimeout(function () {
				        $.hidePreloader();
				        location.href="../index.html";
				    },2000);
    			}else{
    				$.toast('帐号或密码错误');
    			}
    			
    		}
    	});
    })

    $(document).on('touchstart','.eidtinfobtn',function(){//修改个人信息
    	var nick=$('.editnick').val();
    	var loca=$('.editlocation').val();
    	var web=$('.editweb').val();
    	var des=$('.editdes').val();
    	var birth=$('.birthday').val().split('-')[0];
    	var now=(new Date()).toLocaleDateString().split('/')[0];
    	var ag=now-birth;
    	var uid=JSON.parse(localStorage['userinfo']).userid;
    	$.ajax({
    		type:'post',
    		url:root+'/knowhow/index.php/Home/User/editUserInfo',
    		data:{
    			name:nick,
    			location:loca,
    			website:web,
    			age:ag,
    			des:des,
    			uid:uid
    		},
    		success:function(data){
    			console.log(data);
    			if(data){
    				$.toast('操作成功');
    				setTimeout(function(){
    					$.closeModal('#edit_myinfo');
    					$('.nickname').text(nick);
    					$('.after_web').text(web);
    					$('.after_location').text(loca);
    					$('.des').text(des);
    				},2000);
    			}else{
    				$.toast('操作失败');
    			}
    		}
    	})
    })
    $(document).on('touchstart','.com_infobtn',function(){//完善个人资料
    	var loca=$('.com_location').val();
    	var web=$('.com_web').val();
    	var des=$('.com_des').val();
    	var birth=$('.com_birth').val().split('-')[0];
    	var now=(new Date()).toLocaleDateString().split('/')[0];
    	var ag=now-birth;
    	var uid=JSON.parse(localStorage['userinfo']).userid;
    	$.ajax({
    		type:'post',
    		url:root+'/knowhow/index.php/Home/User/editUserInfo',
    		data:{
    			location:loca,
    			website:web,
    			age:ag,
    			des:des,
    			uid:uid
    		},
    		success:function(data){
    			console.log(data);
    			if(data){
    				$.toast('操作成功');
    				setTimeout(function(){
    					location.href="../index.html";
    				},2000);
    			}else{
    				$.toast('操作失败');
    			}
    		}
    	})
    })
	$.init();
})