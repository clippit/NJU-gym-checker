function showTab(n) {
	var text = new Array("#single", "#batch", "#secrets");
	$("#menu li a").removeClass("active");
	$("#menu li:eq("+n+") a").addClass("active");
	$("#single,#batch,#secrets").hide();
	$(text[n]).fadeIn("slow");
}

function getStatics() {
	$(".loadingspinner:eq(2)").show();
	$(".number").empty();
	$.post("api.php", "secrets=1", function(data){
		for(var i in data) {
			$('#'+i).append(data[i]);
		}
		$(".loadingspinner:eq(2)").hide();
		$("#statics,#ad").show();
	},'json');
}

$(document).ready(function() {
	if (!Modernizr.input.placeholder) {
		var placeholderText = new Array();
		$('#num,#num1,#num2').each(function(i) {
			placeholderText[i] = $(this).attr('placeholder');
			$(this).attr('value',placeholderText[i]);
			$(this).addClass('placeholder');
		});

		$('#num,#num1,#num2').each(function(i) {
			$(this).focus(function() {
				if( ($(this).val() == placeholderText[i]) )	{
					$(this).attr('value','');
					$(this).removeClass('placeholder');
				}
			});
		});

		$('#num,#num1,#num2').each(function(i) {
				$(this).blur(function() {
				if ( ($(this).val() == placeholderText[i]) || (($(this).val() == '')) ) {
					$(this).addClass('placeholder');
					$(this).attr('value',placeholderText[i]);
				}
			});
		});
	}
	
	$("h1#title").mouseover(function() {
		$("#ver").animate(
			{opacity:1,left:'47%'},
			400
		);
	}); 
	
	$("h1#title").mouseout(function() {
		$("#ver").animate(
			{opacity:0,left:'49%'},
			400
		);
	});


	if ($("#menu li:eq(0) a").hasClass("active")) {
		showTab(0);
	}
	if ($("#menu li:eq(1) a").hasClass("active")) {
		showTab(1);
	}
	if ($("#menu li:eq(2) a").hasClass("active")) {
		showTab(2);
		getStatics();
	}
	$("#menu li:eq(0) a").click(function(){
		showTab(0);
		return false;
	});
	$("#menu li:eq(1) a").click(function(){
		showTab(1);
		return false;
	});
	$("#menu li:eq(2) a").click(function(){
		showTab(2);
		getStatics();
		return false;
	});

	$("#singlebox").submit(function() {
		$(".submit").attr("disabled","disabled");
		var studentID = $("input#num").val();
		var re = /^(09|10|11)\d{7}$/
		if (!re.exec(studentID)) {
			alert("学号格式错误");
			return false;
		}
		$(".loadingspinner:eq(0)").show();
		$(".summary,#gbox_singledetails").hide();
		$.post("api.php", $(this).serialize(), function(data){
			if (data.length == 0) {
				alert("不给力啊，你确定这是有效的学号吗？要么就是你一次也没有刷过。如果你确定没有问题，那就是体育部那边抽风了，请过段时间再来查询，谢谢。");
				$(".loadingspinner").hide();
				return false;
			}
			$("#singledetails").jqGrid("clearGridData");
			$(".count,.date").empty();
			$(".count").append(data.count);
			$(".date").append(data.list[data.list.length-1].datetime.match(/(\d{4})-(\d{2})-(\d{2})/)[0]);
			for(var i=0; i<data.list.length; i++)
				$("#singledetails").jqGrid('addRowData', i+1, data.list[i]);
			$(".ui-pg-selbox").change();
			$(".loadingspinner:eq(0)").hide();
			$(".summary").fadeIn("slow");
			$("#gbox_singledetails").fadeIn("slow");
		}, "json");
		$(".submit").removeAttr("disabled");
		return false;
	});

	$("#singledetails").jqGrid({
		datatype: "local",
		height:"auto",
		colNames:['日期时间','类型'],
		colModel:[
			{name:'datetime', index:'datetime', width:240, sorttype:'date', datefmt:'Y-m-d H:i'},
			{name:'type', index:'type', width:160}
		],
		caption: "刷卡详细记录",
		pager: '#pager1',
		viewrecords:true,
		rowNum:15,
		rowList:[10,15,20]
	});

	$("#batchbox").submit(function() {
		var studentID1 = $("input#num1").val();
		var studentID2 = $("input#num2").val();
		var re = /^(09|10|11)\d{7}$/
		if ((!re.exec(studentID1)) || (!re.exec(studentID2))) {
			alert("学号格式错误");
			return false;
		}
		var delta = studentID2 - studentID1;
		if ( delta < 0 || delta > 30 ) {
			alert("您悠着点，学号范围有误，请修改至1-30以内");
			return false;
		}
		$(".submit").attr("disabled","disabled");
		$(".loadingspinner:eq(1)").show();
		$("#gbox_batchdetails").hide();
		$("#batchdetails").jqGrid("clearGridData");
		var total = 0;
		for (var id = studentID1; id <= studentID2; id++) {
			if (id.toString().length == 8) {
				num = "0" + id;
			} else {
				num = id.toString();
			}
			$.post("api.php", "num="+num, function(data, textStatus, jqXHR){
				if (data.length == 0) return true;
				for(var i=0; i<data.list.length; i++,total++) {
					data.list[i]['sid'] = data.student_id;
					$("#batchdetails").jqGrid('addRowData', total+1, data.list[i]);
				}
				if ($("#gbox_batchdetails").is(":hidden")) $("#gbox_batchdetails").fadeIn("slow");
				//$(".ui-pg-selbox").change();
				$("#batchdetails").trigger('reloadGrid');
			}, "json");
		}
		$(".submit").removeAttr("disabled");
		return false;
	});
	$(".loadingspinner:eq(1)").ajaxStop(function() {
		$(this).hide();
	});
	$("#batchdetails").jqGrid({
		datatype: "local",
		height:"auto",
		colNames:['日期时间','类型','学号'],
		colModel:[
			{name:'datetime', index:'datetime', width:240, sorttype:'date', datefmt:'Y-m-d H:i'},
			{name:'type', index:'type', width:160},
			{name:'sid', index:'sid', width:80}
		],
		caption: "批量查询结果（点击加号查看明细）",
		rowNum:-1,
		grouping:true,
		groupingView : {
			groupField : ['sid'],
			groupColumnShow : [false],
			groupText : ['<strong>{0} - 截至当前刷卡 {1} 次</strong>'],
			groupCollapse : true
		}
	});

});