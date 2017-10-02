<?
	$domain="https://dweet.net";
	if(isset($_GET['params'])){
		$params=explode("/",$_GET['params']);
		$appletID=$params[0];
		$dweetID=$params[1];
	}
	if(isset($_GET['applet'])) $appletID=$_GET['applet'];
	if(isset($_GET['dweet'])) $dweetID=$_GET['dweet'];
	if($appletID<1 || $appletID>1000000) $appletID=167;
	if($dweetID<1 || $dweetID>1000000) $dweetID=42;
?>
<!doctype HTML>
<html>
	<head>
		<style>
			html,body{
				margin:0;
				background:#000;
				color:#fff;
				font-family:arial,tahoma;
				text-align:center;
				width:100%;
			}
			.main{
				margin-top:5vh;
				min-height:90vh;
				width:95%;
				border-radius:40px;
				border:1px solid #666;
				margin-left:auto;
				margin-right:auto;
				background:#111;
			}
			.innerLeft{
				left:0;
				float:left;
				width:50%;
				height:100%;
			}
			.innerRight{
				position:absolute;
				box-sizing: border-box;
				-moz-box-sizing: border-box;
				-webkit-box-sizing: border-box;
				border-left:1px solid #666;
				right:0;
				min-height:90vh;
				width:50%;
			}
			.frameDiv{
				width:35vw;
				height:19.6875vw;
				margin-left:auto;
				margin-right:auto;
				background:#000;
			}
			iframe{
				width:100%;
				height:100%;
				display:block;
				border:1px solid #888;
				background:#fff;
			}
			.siteTitle{
				font-size:30px;
				color:#afa;
				display:block;
			}
			input[type=text] {
				font-size:20px;
				background:#001;
				border:1px solid #8af;
				color:#fff;
				width:100px;
				text-align:center;
				margin-top:10px;
				margin-bottom:10px;
			}
			.code{
				width:34vw;
				margin-left:auto;
				margin-right:auto;
				text-align:left;
				word-wrap:break-word;
				display:none;
				border-radius:6px;
				padding:.5vw;
				padding-top:.25vw;
				background:#333;
				color:#abc;
				font-size:18px;
			}
			.embed{
				background:#232;
				color:#fff;
				padding:5px;
				font-family:courier;
				font-size:14px;
				border:1px solid #888;
			}
			.codeDiv{
				background:#111;
				color:#fff;
				padding:5px;
				font-family:courier;
				font-size:14px;
				border:1px solid #888;
			}
			.inputLabel{
				font-size:20px;
				color:#888;
			}
			.notFound{
				position:relative;
				top:46%;
				transform:translateY(-50%);
				font-size:32px;
			}
			button{
				font-size:20px;
				border-radius:5px;
				background:#444;
				color:white;
				margin:6px;
				margin-top:20px;
				margin-bottom:20px;
			}
			#copyAppletEmbedCodeButton{
				font-size:12px;
				margin-top:0px;
				margin-bottom:5px;
			}
			#copyDweetEmbedCodeButton{
				font-size:12px;
				margin-top:0px;
				margin-bottom:5px;
			}
			#appletButtons{
				display:none;
			}
			#dweetButtons{
				display:none;
			}
			a{
				color:#ac2;
				text-decoration:none;
			}
			.clear{
				clear:both;
			}
		</style>
	</head>
	<body onload="loadApplet();loadDweet();">
		<div class="main">
			<div class="innerLeft">
				<br>
				<span class="siteTitle">codegolf.tk</span>
				<span class="inputLabel">applet # </span>
				<input type="text" value="<?=$appletID?>" id="appletID" />
				<button onclick="loadApplet()">load</button>
				<br>
				<div class="frameDiv" id="appletFrameDiv"></div>
				<br>
				<div class="code" id="appletCode"></div>
				<div id="appletButtons">
					<button onclick="downloadAppletHTML()">download stand-alone HTML</button>
				</div>
			</div>
			<div class="innerRight">
				<br>
				<span class="siteTitle">dwitter.net</span>
				<span class="inputLabel">dweet # </span>
				<input type="text" value="<?=$dweetID?>" id="dweetID" />
				<button onclick="loadDweet()">load</button>
				<br>
				<div class="frameDiv" id="dweetFrameDiv"></div>
				<br>
				<div class="code" id="dweetCode"></div>
				<div id="dweetButtons">
					<button onclick="downloadDweetHTML()">download stand-alone HTML</button>
				</div>
			</div>
			<div class="clear"></div>
			<script>
				copyApplet=()=>{
					try {
						var range = document.createRange();
						range.selectNode(embedAppletCode);
						window.getSelection().removeAllRanges();
						window.getSelection().addRange(range);
						var successful = document.execCommand('copy');
						alert("copied!");
					} catch (err) {
						alert(err+'Oops, unable to copy');
					}
				}
				copyDweet=()=>{
					try {
						var range = document.createRange();
						range.selectNode(embedDweetCode);
						window.getSelection().removeAllRanges();
						window.getSelection().addRange(range);
						var successful = document.execCommand('copy');
						alert("copied!");
					} catch (err) {
						alert(err+'Oops, unable to copy');
					}
				}
				download=(filename, text)=>{
					var file = document.createElement('a');
					file.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
					file.setAttribute('download', filename);
					file.style.display = 'none';
					document.body.appendChild(file);
					file.click();
					document.body.removeChild(file);
				}
				downloadAppletHTML=()=>{
					download("applet.html",appletHTML);
				}
				downloadDweetHTML=()=>{
					download("dweet.html",dweetHTML);
				}
				loadApplet=()=>{
					appletFrameDiv.innerHTML="";
					appletCode.style.display="none";
					appletButtons.style.display="none";
					var url='https://applet.codegolf.tk/?applet='+appletID.value+'&autoplay=1';
					var xhr = new XMLHttpRequest();
					xhr.onreadystatechange = function() {
						if (xhr.readyState == XMLHttpRequest.DONE) {
							switch(xhr.responseText){
								case "404":
									appletFrameDiv.innerHTML="<span class='notFound'>APPLET NOT FOUND!</span>";
									appletCode.innerHTML='';
								break;
								default:
									if(appletID.value==''){
										appletFrameDiv.innerHTML="<span class='notFound'>APPLET NOT FOUND!</span>";
										appletCode.innerHTML='';
									}else{
										var frame=document.createElement("iframe");
										frame.src=url;
										frame.width=320;
										frame.height=180;
										appletFrameDiv.appendChild(frame);
										var codeURL='<?=$domain?>/getCode.php?mode=applet&id='+appletID.value;
										var xhr2 = new XMLHttpRequest();
										xhr2.onreadystatechange = function() {
											if (xhr2.readyState == XMLHttpRequest.DONE) {
												appletCode.innerHTML=xhr2.responseText;
												appletCode.innerHTML+="<br>embed code: <button id='copyAppletEmbedCodeButton' onclick='copyApplet()'>copy to clipboard</button>";
												appletCode.style.display="block";
												embedAppletCode = document.createElement("div");
												embedAppletCode.className = "embed";
												embedAppletCode.innerHTML=appletFrameDiv.innerHTML.replace("<","&lt;");
												appletCode.appendChild(embedAppletCode);
												var codeURL='<?=$domain?>/getCode.php?mode=wholeApplet&id='+appletID.value;
												var xhr3 = new XMLHttpRequest();
												xhr3.onreadystatechange = function() {
													if (xhr3.readyState == XMLHttpRequest.DONE) {
														appletHTML=xhr3.responseText;
														appletButtons.style.display="block";
													}
												}
												xhr3.open('GET', codeURL, true);
												xhr3.send(null);
											}
										}
										xhr2.open('GET', codeURL, true);
										xhr2.send(null);
									}
								break;
							}
						}
					}
					xhr.open('GET', '<?=$domain?>/getStatus.php?url='+url, true);
					xhr.send(null);
				}
				loadDweet=()=>{
					dweetFrameDiv.innerHTML="";
					dweetCode.style.display="none";
					dweetButtons.style.display="none";
					var url='https://dweet.dwitter.net/id/'+dweetID.value+'?autoplay=1';;
					var xhr = new XMLHttpRequest();
					xhr.onreadystatechange = function() {
						if (xhr.readyState == XMLHttpRequest.DONE) {
							switch(xhr.responseText){
								case "404":
									dweetFrameDiv.innerHTML="<span class='notFound'>DWEET NOT FOUND!</span>";
									dweetCode.innerHTML='';
								break;
								default:
									if(dweetID.value==''){
										dweetFrameDiv.innerHTML="<span class='notFound'>DWEET NOT FOUND!</span>";
										dweetCode.innerHTML='';
									}else{
										var frame=document.createElement("iframe");
										frame.src=url;
										frame.width=320;
										frame.height=180;
										dweetFrameDiv.appendChild(frame);
										var codeURL='<?=$domain?>/getCode.php?mode=dweet&id='+dweetID.value;
										var xhr2 = new XMLHttpRequest();
										xhr2.onreadystatechange = function() {
											if (xhr2.readyState == XMLHttpRequest.DONE) {
												dweetCode.innerHTML=xhr2.responseText;
												dweetCode.innerHTML+="<br>embed code: <button id='copyDweetEmbedCodeButton' onclick='copyDweet()'>copy to clipboard</button>";
												dweetCode.style.display="block";
												embedDweetCode = document.createElement("div");
												embedDweetCode.className = "embed";
												embedDweetCode.innerHTML=dweetFrameDiv.innerHTML.replace("<","&lt;");
												dweetCode.appendChild(embedDweetCode);
												var codeURL='<?=$domain?>/getCode.php?mode=wholeDweet&id='+dweetID.value;
												var xhr3 = new XMLHttpRequest();
												xhr3.onreadystatechange = function() {
													if (xhr3.readyState == XMLHttpRequest.DONE) {
														dweetHTML=xhr3.responseText;
														dweetButtons.style.display="block";
													}
												}
												xhr3.open('GET', codeURL, true);
												xhr3.send(null);
											}
										}
										xhr2.open('GET', codeURL, true);
										xhr2.send(null);
									}
								break;
							}
						}
					}
					xhr.open('GET', '<?=$domain?>/getStatus.php?url='+url, true);
					xhr.send(null);
				}
				appletID.addEventListener("keyup", function(event) {
					event.preventDefault();
					if (event.keyCode == 13) {
						loadApplet();
					}
				});
				dweetID.addEventListener("keyup", function(event) {
					event.preventDefault();
					if (event.keyCode == 13) {
						loadDweet();
					}
				});
			</script>
		</div>
	</body>
</html>