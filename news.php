<?php

if( !$_GET['id'] )
{
$sql = 'select id
		from news
		order by id desc;';
$result = mysql_query($sql);
while( $row = mysql_fetch_array($result) )
	{
		$news = new News($row['id']);
		
		echo('<div class="green_contentbox">
			<div class="green_content_top">
				<table width="95%" class="news_header" cellpadding="0" cellspacing="0"><tr><td class="title" width="50%"><h3 class="content_box_header">' . $news->title . '</h3></td><td class="time">' . dateFromTimestamp_Long($news->date) . '</td></tr></table>
			</div>
			<div class="green_content">
				<table width="95%" class="general_info" align="left" cellpadding="3" cellspacing="0">
				<tr>
				<td class="news_left" valign="top">
				<div class="news_thumbnail">
					<a href="index.php?view=profile&user=' . $news->poster . '">
					'); 
					$file_management->profileImage( $news->poster, 110, 95 );
					echo('
					</a>
				</div>
				<div class="news_left">
				<span>' . $db->titleFromUsername( $news->poster ) . '</span>
				</div>
				</td>
				<td valign="top">
			  <p class="news" width="100%" style="overflow: auto;">' . $news->news . '</p>
			  </td>
			  </tr>
			  </table>
			  </div>
			<div class="green_content_bottom">
			</div>
		</div>

				');
	
	
	}
}
else
{
	$news = new News($_GET['id']);
		
		echo('<div class="green_contentbox">
			<div class="green_content_top">
				<table width="95%" class="news_header" cellpadding="0" cellspacing="0"><tr><td class="title" width="50%"><h3 class="content_box_header">' . $news->title . '</h3></td><td class="time">' . dateFromTimestamp_Long($news->date) . '</td></tr></table>
			</div>
			<div class="green_content">
				<table width="95%" class="general_info" align="left" cellpadding="3" cellspacing="0">
				<tr>
				<td class="news_left" valign="top">
				<div class="news_thumbnail">
					<a href="index.php?view=profile&user=' . $news->poster . '">
					'); 
					$file_management->profileImage( $news->poster, 110, 95 );
					echo('
					</a>
				</div>
				<div class="news_left">
				<span>' . $db->titleFromUsername( $news->poster ) . '</span>
				</div>
				</td>
				<td valign="top">
			  <p class="news" width="100%" style="overflow: auto;">' . $news->news . '</p>
			  </td>
			  </tr>
			  </table>
			  </div>
			<div class="green_content_bottom">
			</div>
		</div>

				');
}

?>
