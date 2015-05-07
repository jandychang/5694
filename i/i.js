//首页
	$(function(){
		$("#d dd b u a,#e b u a").hover(function(){
			$(this).addClass('c').siblings().removeClass('c');
			$(this).parents('dd,#e').find('p,div').eq($(this).parent("u").find("a").index(this)).show().siblings("p,div").hide();
		})
	});
	//幻灯
	$(function(){
		//背景
		$("#b div a").each(function(){
			var bg = $(this).attr("bg");
			$(this).css("background-image",'url('+bg+')');
		});
		//手动切换
		$("#b p a").hover(function(){
			$(this).addClass('c').siblings().removeClass('c');
			$(this).parent("p").siblings("div").find("a").eq($(this).parent("p").find("a").index(this)).css("display","block").siblings("a").hide();
		});
		//自动切换
		var picTimer;
		var index = 0;
		$("#b div,#b p").hover(function(){
			clearInterval(picTimer);
		},function(){
			picTimer = self.setInterval(function(){
				if (index == 8) {
					index = 0;
				}else{
					index++;
				};
				$("#b div a").eq(index).css("display","block").siblings("a").hide();
				$("#b p a").eq(index).addClass('c').siblings().removeClass('c');
			},5000);
		});
	});
//展示
	$(function(){
		$("#m #bf s.m").click(function(){
			$(this).hide().parent("li").css("height","auto").find(".i").show();	
		});
		$("#m #bf b a,#l div .b2 a").click(function(){
			$(this).addClass("c").siblings().removeClass("c");
			$(this).parents("div").find("li,p.lp").hide().eq($(this).parent("b").find("a").index(this)).show();
		})
	});
//人物
	$(function(){
		$("#l .lh a").click(function(){
			$(this).addClass("c").siblings().removeClass("c");
			$(this).parents("#l dd").children("div").hide().eq($(this).parent("b").find("a").index(this)).show();
		});
	});
	$(function(){
		$("#l .l2m").toggle(function(){
			$(this).html("收起 ↗");
			$("#l .l2").css("height","auto");
		},function(){
			$("#l .l2").css("height","140px");
			$(this).html("展开全部 ↘");
		});
	});
//排行榜
	$(function(){
		$("#d dt b s a").click(function(){
			$(this).addClass("c").siblings().removeClass("c");
			$(this).parents("dt").children("p.p1").hide().eq($(this).parent("s").find("a").index(this)).show();
		});
	});
//评论
	$(function(){
		$("#pl p u a").click(function(){
			$(this).hide().parent("u").css("height","auto");
		});
	})