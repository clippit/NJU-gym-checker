$(document).ready(function() {
	// placeholder fallback
	if (!Modernizr.input.placeholder) {
		var placeholderText = [];
		$('#num,#num1,#num2').each(function(i) {
			var $this = $(this);
			placeholderText[i] = $this.attr('placeholder');
			$this.attr('value', placeholderText[i]);
			$this.addClass('placeholder')
				.focus(function() {
					if(($this.val() == placeholderText[i])) {
						$this.attr('value','');
						$this.removeClass('placeholder');
					}
				})
				.blur(function() {
					if (($this.val() == placeholderText[i]) || (($(this).val() === ''))) {
						$this.addClass('placeholder');
						$this.attr('value',placeholderText[i]);
					}
				});
		});
	}

	// version title
	$("h1#title").mouseover(function() {
		$("#ver").animate(
			{opacity:1,left:'47%'},
			400
		);
	}).mouseout(function() {
		$("#ver").animate(
			{opacity:0,left:'49%'},
			400
		);
	});

	// show which tab
	var showTab = function(n) {
		var text = ["#single", "#batch", "#secrets"];
		$("#menu li a").removeClass("active");
		$("#menu li:eq(" + n + ") a").addClass("active");
		$(text.join()).hide();
		$(text[n]).fadeIn("slow");
	};

	function getStatics() {
		var $loadingSpinner = $(".loadingspinner:eq(2)");
		$loadingSpinner.show();
		$(".number").empty();
		$.post("api.php", "secrets=1", function(data){
			for(var i in data) {
				$('#'+i).append(data[i]);
			}
			$loadingSpinner.hide();
			$("#statics,#ad").show();
		},'json');
	}

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
	$("#menu li:eq(0) a").click(function(e) {
		e.preventDefault();
		showTab(0);
	});
	$("#menu li:eq(1) a").click(function(e) {
		e.preventDefault();
		showTab(1);
	});
	$("#menu li:eq(2) a").click(function(e) {
		e.preventDefault();
		showTab(2);
		getStatics();
	});

	$("#singlebox").submit(function() {
		var studentID = $("input#num").val(),
			re = /^(09|10|11)\d{7}$/,
			$submit = $(".submit"),
			$loadingSpinner = $(".loadingspinner:eq(0)");
		$submit.attr("disabled", "disabled");

		if (!re.exec(studentID)) {
			alert("学号格式错误");
			return false;
		}
		$loadingSpinner.show();
		$(".summary,#gbox_singledetails").hide();
		$.post("api.php", $(this).serialize(), function(data){
			if (data.length === 0) {
				alert("不给力啊，你确定这是有效的学号吗？要么就是你一次也没有刷过。如果你确定没有问题，那就是体育部那边抽风了，请过段时间再来查询，谢谢。");
				$(".loadingspinner").hide();
				return false;
			}
			$("#singledetails").jqGrid("clearGridData");
			$(".count,.date").empty();
			$(".count").append(data.count);
			$(".date").append(data.list[data.list.length-1].datetime.match(/(\d{4})-(\d{2})-(\d{2})/)[0]);
			for(var i = 0; i < data.list.length; i++)
				$("#singledetails").jqGrid('addRowData', i + 1, data.list[i]);
			$(".ui-pg-selbox").change();
			$loadingSpinner.hide();
			$(".summary").fadeIn("slow");
			$("#gbox_singledetails").fadeIn("slow");
		}, "json");
		$submit.removeAttr("disabled");
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
		viewrecords: true,
		rowNum: 15,
		rowList: [10,15,20]
	});

	$("#batchbox").submit(function() {
		var studentID1 = $("input#num1").val(),
			studentID2 = $("input#num2").val(),
			re = /^(09|10|11)\d{7}$/,
			$submit = $(".submit"),
			$loadingSpinner = $(".loadingspinner:eq(1)"),
			$gboxBatchDetails = $("#gbox_batchdetails"),
			$batchDetails = $("#batchdetails");
		$loadingSpinner.ajaxStop(function() {
			$(this).hide();
		});
		if ((!re.exec(studentID1)) || (!re.exec(studentID2))) {
			alert("学号格式错误");
			return false;
		}
		var delta = studentID2 - studentID1;
		if ( delta < 0 || delta > 20 ) {
			alert("您悠着点，学号范围有误，请修改至1-20以内");
			return false;
		}
		$submit.attr("disabled","disabled");
		$loadingSpinner.show();
		$gboxBatchDetails.hide();
		$batchDetails.jqGrid("clearGridData");
		var total = 0,
			appendResult = function(data, textStatus, jqXHR) {
				if (data.length === 0)
					return true;
				for(var i = 0; i < data.list.length; i++, total++) {
					data.list[i]['sid'] = data.student_id;
					$batchDetails.jqGrid('addRowData', total + 1, data.list[i]);
				}
				if ($gboxBatchDetails.is(":hidden"))
					$gboxBatchDetails.fadeIn("slow");
				//$(".ui-pg-selbox").change();
				$batchDetails.trigger('reloadGrid');
			};
		for (var id = studentID1; id <= studentID2; id++) {
			if (id.toString().length == 8) {
				num = "0" + id;
			} else {
				num = id.toString();
			}
			$.post("api.php", "num=" + num, appendResult, "json");
		}
		$submit.removeAttr("disabled");
		return false;
	});

	$("#batchdetails").jqGrid({
		datatype: "local",
		height: "auto",
		colNames: ['日期时间','类型','学号'],
		colModel: [
			{name: 'datetime', index: 'datetime', width: 240, sorttype: 'date', datefmt: 'Y-m-d H:i'},
			{name: 'type', index: 'type', width: 160},
			{name: 'sid', index: 'sid', width: 80}
		],
		caption: "批量查询结果（点击加号查看明细）",
		rowNum: -1,
		grouping: true,
		groupingView: {
			groupField: ['sid'],
			groupColumnShow: [false],
			groupText: ['<strong>{0} - 截至当前刷卡 {1} 次</strong>'],
			groupCollapse: true
		}
	});

});
