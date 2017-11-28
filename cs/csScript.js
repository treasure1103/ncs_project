var useApi = '../api/apiCSCenter.php';
var commentApi = '../api/apiCSComment.php';
var memberApi = '../api/apiMember.php';
var sortData = '';

$.get(useApi,'list='+listCount+'&page='+page+sortData,function(data){
	//alert(data.totalCount)
})

function inputDate(obj){
	var formData = $('.'+obj).serialize();
	$.post(useApi,formData,function(data){
		alert(data)
	})
}