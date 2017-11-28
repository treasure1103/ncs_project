<?	if($rs['marketerID'] == "ksa" ){ ?>
	<ul id="snb">
		<li onclick="top.location.href='rule.php'">교육이용안내</li>
	</ul>
<? } else { ?>
	<ul id="snb">
		<li onclick="top.location.href='eduinfo.php'">위탁교육안내</li>
		<li onclick="top.location.href='logic.php'">교육진행절차</li>
		<li onclick="top.location.href='goyong.php'">환급절차</li>
		<li onclick="top.location.href='rule.php'">교육이용안내</li>
	</ul>
<? }  ?>