<?
@set_time_limit(0);
@error_reporting(E_ERROR | E_WARNING | E_PARSE);
@header("Content-Type: text/html; charset=utf-8");

/*
Tool Name: MT
Tool URI: http://korsen.f2bLog.com
Description: MT格式汇入f2bLog
Author: korsen
Version: 1.0
Author URI: http://korsen.f2bLog.com
*/

$plugins_path="../";
include("../include/function.php");

echo "<h2>MT汇入F2blog程序</h2>";

$mt_txt="mt.txt";

if (!file_exists($mt_txt)){
	echo "注意事项：汇入的xml名称必须是mt.txt，并且通过ftp上传到tools目录下面。";
}else{
	#读取日志
	$blog_content = file_get_contents($mt_txt);
	//echo $blog_content;
	$blog_content = preg_replace("/(\r\n|\n|\r)/", "\n", $blog_content);
	$blog_content = preg_replace("/\n--------\n/", "--MT-ENTRY--\n", $blog_content);
	$blog_content = str_replace("'", "&#39;", $blog_content);
	$arrPost = explode("--MT-ENTRY--", $blog_content);	

	$error=0;
	$success=0;
	$i=0;
	//每条日志
	foreach ($arrPost as $post) {
			if ('' != trim($post)) {
				$i++;
				unset ($post_categories);

				// Take the pings out first
				preg_match("|(-----\n\nPING:.*)|s", $post, $pings);
				$post = preg_replace("|(-----\n\nPING:.*)|s", '', $post);

				// Then take the comments out
				preg_match("|(-----\nCOMMENT:.*)|s", $post, $comments);
				$post = preg_replace("|(-----\nCOMMENT:.*)|s", '', $post);

				// We ignore the keywords
				$post = preg_replace("|(-----\nKEYWORDS:.*)|s", '', $post);

				// We want the excerpt
				preg_match("|-----\nEXCERPT:(.*)|s", $post, $excerpt);
				$excerpt = trim($excerpt[1]);
				$post = preg_replace("|(-----\nEXCERPT:.*)|s", '', $post);

				// We're going to put extended body into main body with a more tag
				preg_match("|-----\nEXTENDED BODY:(.*)|s", $post, $extended);
				$extended = trim($extended[1]);
				if ('' != $extended)
					$extended = "\n<!--more-->\n$extended";
				$post = preg_replace("|(-----\nEXTENDED BODY:.*)|s", '', $post);

				// Now for the main body
				preg_match("|-----\nBODY:(.*)|s", $post, $body);
				$body = trim($body[1]);
				$post_content = $body.$extended;
				$post = preg_replace("|(-----\nBODY:.*)|s", '', $post);
				
				// Grab the metadata from what's left
				$metadata = explode("\n", $post);
				foreach ($metadata as $line) {
					preg_match("/^(.*?):(.*)/", $line, $token);
					$key = trim($token[1]);
					$value = trim($token[2]);
					// Now we decide what it is and what to do with it
					switch ($key) {
						case '' :
							break;
						case 'AUTHOR' :
							$post_author = $value;
							break;
						case 'TITLE' :
							$post_title = $value;
							break;
						case 'STATUS' :
							// "publish" and "draft" enumeration items match up; no change required
							$post_status = ($value=="draft")?0:1;
							break;
						case 'ALLOW COMMENTS' :
							$post_allow_comments = $value;
							break;
						case 'CONVERT BREAKS' :
							$post_convert_breaks = $value;
							break;
						case 'ALLOW PINGS' :
							$post_allow_pings = $value;
							break;
						case 'PRIMARY CATEGORY' :
							if (! empty ($value) )
								$post_categories[] = $value;
							break;
						case 'CATEGORY' :
							if (! empty ($value) )
								$post_categories[] = $value;
							break;
						case 'DATE' :
							$post_date = strtotime($value);
							break;
						default :
							// echo "\n$key: $value";
							break;
					} // end switch
				} // End foreach

				$postdata = compact('post_author', 'post_date', 'post_content', 'post_title', 'post_excerpt', 'post_status', 'post_allow_comments', 'post_allow_pings');
				//print_r($postdata);
				//print_r($post_categories);

				// Insert to data
				//类别处理，单层
				if (count($post_categories)==1){
					$sql="select id from ".$DBPrefix."categories where name='$post_categories[0]' and parent='0'";
					if ($arr_result=$DMC->fetchArray($DMC->query($sql))){
						$category_id=$arr_result['id'];
					}else{
						$sql="INSERT INTO ".$DBPrefix."categories(parent,name,orderNo,cateTitle,outLinkUrl,cateCount,isHidden) VALUES ('0','$post_categories[0]','1','$post_categories[0]','','0','0')";
						$DMC->query($sql);
						$category_id=$DMC->insertId();
					}
				}else if (count($post_categories)==2){//类别处理，双层
					$sql="select id from ".$DBPrefix."categories where name='$post_categories[0]' and parent='0'";
					if ($arr_result=$DMC->fetchArray($DMC->query($sql))){
						$parent_id=$arr_result['id'];
						//插入第二层类别
						$sql="select id from ".$DBPrefix."categories where name='$post_categories[1]' and parent='$parent_id'";
						if ($arr_result=$DMC->fetchArray($DMC->query($sql))){
							$category_id=$arr_result['id'];
						}else{
							$sql="INSERT INTO ".$DBPrefix."categories(parent,name,orderNo,cateTitle,outLinkUrl,cateCount,isHidden) VALUES ('$parent_id','$post_categories[1]','1','$post_categories[1]','','0','0')";
							$DMC->query($sql);
							$category_id=$DMC->insertId();
						}
					}else{
						$sql="INSERT INTO ".$DBPrefix."categories(parent,name,orderNo,cateTitle,outLinkUrl,cateCount,isHidden) VALUES ('0','$value','1','$value','','0','0')";
						$DMC->query($sql);
						$parent_id=$DMC->insertId();
						//插入第二层类别
						$sql="select id from ".$DBPrefix."categories where name='$post_categories[1]' and parent='$parent_id'";
						if ($arr_result=$DMC->fetchArray($DMC->query($sql))){
							$category_id=$arr_result['id'];
						}else{
							$sql="INSERT INTO ".$DBPrefix."categories(parent,name,orderNo,cateTitle,outLinkUrl,cateCount,isHidden) VALUES ('$parent_id','$post_categories[1]','1','$post_categories[1]','','0','0')";
							$DMC->query($sql);
							$category_id=$DMC->insertId();
						}
					}
				}else{//无类别
					$sql="select id from ".$DBPrefix."categories";
					if ($arr_result=$DMC->fetchArray($DMC->query($sql))){
						$category_id=$arr_result['id'];
					}else{
						$sql="INSERT INTO ".$DBPrefix."categories(parent,name,orderNo,cateTitle,outLinkUrl,cateCount,isHidden) VALUES ('0','MT','1','MT','','0','0')";
						$DMC->query($sql);
						$category_id=$DMC->insertId();
					}
				}

				echo "正在汇入第$i 条日志 ... ";
				$sql="select id from ".$DBPrefix."logs where logTitle='$postdata[post_title]'";
				if ($arr_result=$DMC->fetchArray($DMC->query($sql))){
					$comment_post_ID=$arr_result['id'];
					echo "<font color=red>资料已存在</font><br>";
				}else{
					$sql="INSERT INTO ".$DBPrefix."logs(cateId,logTitle,logContent,author,quoteUrl,postTime,isComment,isTrackback,isTop,weather,saveType,tags,password,logsediter) VALUES ('$category_id','$postdata[post_title]','$postdata[post_content]','$postdata[post_author]','','$postdata[post_date]','$postdata[post_allow_comments]','$postdata[post_allow_pings]','0','sunny','$postdata[post_status]','','','tiny')";
					$DMC->query($sql);
					if ($DMC->error()){
						$error++;
						echo $DMC->error()."<br>";
					}else{
						$success++;
						echo "<font color=red>OK</font><br>";
					}

					$comment_post_ID = $DMC->insertId();
				}

				// Now for comments
				$comments = explode("-----\nCOMMENT:", $comments[0]);
				$num_comments = 0;
				foreach ($comments as $comment) {
					if ('' != trim($comment)) {
						// Author
						preg_match("|AUTHOR:(.*)|", $comment, $comment_author);
						$comment_author = trim($comment_author[1]);
						$comment = preg_replace('|(\n?AUTHOR:.*)|', '', $comment);
						preg_match("|EMAIL:(.*)|", $comment, $comment_author_email);
						$comment_author_email = trim($comment_author_email[1]);
						$comment = preg_replace('|(\n?EMAIL:.*)|', '', $comment);

						preg_match("|IP:(.*)|", $comment, $comment_author_IP);
						$comment_author_IP = trim($comment_author_IP[1]);
						$comment = preg_replace('|(\n?IP:.*)|', '', $comment);

						preg_match("|URL:(.*)|", $comment, $comment_author_url);
						$comment_author_url = trim($comment_author_url[1]);
						$comment = preg_replace('|(\n?URL:.*)|', '', $comment);

						preg_match("|DATE:(.*)|", $comment, $comment_date);
						$comment_date = strtotime(trim($comment_date[1]));
						//$comment_date = date('Y-m-d H:i:s', strtotime($comment_date));
						$comment = preg_replace('|(\n?DATE:.*)|', '', $comment);

						$comment_content = trim($comment);
						$comment_content = str_replace('-----', '', $comment_content);

						$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_url', 'comment_author_email', 'comment_author_IP', 'comment_date', 'comment_content');
						//print_r($commentdata);

						$sql="select id from ".$DBPrefix."comments where logId='$commentdata[comment_post_ID]' and author='$commentdata[comment_author]' and content='$commentdata[comment_content]'";
						if (!$arr_result=$DMC->fetchArray($DMC->query($sql))){
							$sql="insert into ".$DBPrefix."comments(author,password,logId,ip,content,postTime,isSecret,parent) values('$commentdata[comment_author]','','".$commentdata['comment_post_ID']."','".$commentdata['comment_author_IP']."','".$commentdata['comment_content']."','".$commentdata['comment_date']."','0','0')";
							$DMC->query($sql);
						}
					}
				}

				// Finally the pings
				// fix the double newline on the first one
				$pings[0] = str_replace("-----\n\n", "-----\n", $pings[0]);
				$pings = explode("-----\nPING:", $pings[0]);
				$num_pings = 0;
				foreach ($pings as $ping) {
					if ('' != trim($ping)) {
						preg_match("|BLOG NAME:(.*)|", $ping, $ping_blogsite);
						$ping_blogsite = trim($ping_blogsite[1]);
						$ping = preg_replace('|(\n?BLOG NAME:.*)|', '', $ping);

						preg_match("|IP:(.*)|", $ping, $ping_blogsite_IP);
						$ping_blogsite_IP = trim($ping_blogsite_IP[1]);
						$ping = preg_replace('|(\n?IP:.*)|', '', $ping);

						preg_match("|URL:(.*)|", $ping, $ping_blogsite_url);
						$ping_blogsite_url = trim($ping_blogsite_url[1]);
						$ping = preg_replace('|(\n?URL:.*)|', '', $ping);

						preg_match("|DATE:(.*)|", $ping, $ping_date);
						$ping_date = strtotime(trim($ping_date[1]));
						$ping = preg_replace('|(\n?DATE:.*)|', '', $ping);

						preg_match("|TITLE:(.*)|", $ping, $ping_title);
						$ping_title = trim($ping_title[1]);
						$ping = preg_replace('|(\n?TITLE:.*)|', '', $ping);

						$ping_content = trim($ping);
						$ping_content = str_replace('-----', '', $ping_content);

						$ping_content = "<strong>$ping_title</strong>\n\n$ping_content";

						$pingdata = compact('comment_post_ID', 'ping_blogsite', 'ping_blogsite_url', 'ping_blogsite_IP', 'ping_date', 'ping_content', 'ping_title');

						//print_r($pingdata);
						$sql="select id from ".$DBPrefix."trackbacks where logId='$pingdata[comment_post_ID]' and content='$pingdata[ping_content]'";
						if (!$arr_result=$DMC->fetchArray($DMC->query($sql))){
							$sql="insert into ".$DBPrefix."trackbacks(logId,tbTitle,blogSite,blogUrl,content,postTime,isApp,ip) values('$pingdata[comment_post_ID]','".$pingdata['ping_title']."','".$pingdata['ping_blogsite']."','".$pingdata['ping_blogsite_url']."','".$pingdata['ping_content']."','".$pingdata['ping_date']."','1','".$pingdata['ping_blogsite_IP']."')";
							$DMC->query($sql);
						}
				}
			}				
		}
	}
}
?>