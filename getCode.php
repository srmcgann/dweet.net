<?
	require("db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	function get_data($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	if(isset($_GET['mode'])){
		switch($_GET['mode']){
			case "applet":
				$id=mysqli_real_escape_string($link,$_GET['id']);
				$sql="SELECT * FROM applets WHERE id=$id";
				$res=$link->query($sql);
				if(mysqli_num_rows($res)){
					$row=mysqli_fetch_assoc($res);
					$userID=$row['userID'];
					$id=$row['id'];
					$code=$row['code'];
					$sql="SELECT name FROM users WHERE id = $userID";
					$res=$link->query($sql);
					$row=mysqli_fetch_assoc($res);
					$author=$row['name'];
					echo "author: <a href='https://codegolf.tk/$author' target='_blank'>".$author.'</a><br>';
					echo "&nbsp;&nbsp;&nbsp;URL: <a href='https://codegolf.tk/a/".$id."' target='_blank'>https://codegolf.tk/a/".$id."</a><br>";
					echo "<div id='appletCodeDiv' class='codeDiv'>";
					echo str_replace("\n","<br>",$code);
					echo '</div>';
				}
				break;
			case "dweet":
				$url='https://dweetplayer.net/api/dweets/'.$_GET['id'];
				$ar=json_decode(get_data($url),true);
				echo "author: <a href='{$ar['authorUrl']}' target='_blank'>".$ar['author'].'</a><br>';
				echo "&nbsp;&nbsp;&nbsp;URL: <a href='".$ar['dweetUrl']."' target='_blank'>".$ar['dweetUrl']."</a><br>";
				echo "<div id='dweetCodeDiv' class='codeDiv'>";
				echo str_replace("\n","<br>",$ar['src']);
				echo '</div>';
				break;
			case "wholeApplet":
				$url="https://applet.codegolf.tk/?applet={$_GET['id']}&autoplay=1";
				echo get_data($url);
				break;
			case "wholeDweet":
				$url="https://dweet.dwitter.net/id/{$_GET['id']}?autoplay=1";
				$data = get_data($url);
				$data = str_replace("if(autoplay)","if(1)",$data);
				$data = str_replace("if (playing)","if(1)",$data);
				$data = explode("\n",$data);
				$res='';
				for($i=0;$i<count($data);++$i){
					if(strpos($data[$i],"text/javascript")===false &&
					   strpos($data[$i],"stats = createStats()")===false &&
					   strpos($data[$i],"setStatsVisibility(false)")===false &&
					   strpos($data[$i],"getElementById('stats')")===false &&
					   strpos($data[$i],"stats.begin()")===false &&
					   strpos($data[$i],"stats.end()")===false &&
					   strpos($data[$i],"stats || createStats()")===false)$res.=$data[$i]."\n";
				}
				echo $res;
				break;
		}
	}
?>