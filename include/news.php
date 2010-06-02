<?php

class News
{
	var $id;
	var $title;
	var $description;
	var $news;
	var $poster;
	var $date;
	
function News($id)
{
	global $db;
	$array = $db->getNewsInfo($id);
	$this->id = $id;
	$this->title = $array['title'];
	$this->description = $array['description'];
	$this->news = nl2br($array['text']);
	$this->poster = $array['poster'];
	$this->date = $array['date'];
}


};

class News_Header
{
	var $news_list;
	
	function News_Header()
	{
		$sql = 'select * from news order by id desc limit 4;';
		$result = mysql_query($sql);
		$counter = 1;
		while( $row = mysql_fetch_array($result) )
		{
			$this->news_list[$counter] = new News($row['id']);
			$counter++;
		}
	
	}
	
	function display( $select )
	{
	global $db;
		switch( $select )
		{
			case 1:
				echo('
					<div class="news_header_left">
						<div class="news_header_title_4" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=4">' . $this->news_list[4]->title . '</a>
						</div>
						<div class="news_header_title_3" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=3">' . $this->news_list[3]->title . '</a>
						</div>
						<div class="news_header_title_2" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=2">' . $this->news_list[2]->title . '</a>
						</div>
						<div class="news_header_text_1" style="background: url(templates/orange_rush/images/java/java_07.jpg); height: 118px;">
							<h3>' . $this->news_list[1]->title . ' - <a style="font-size: 10px;" href="index.php?view=news">[ View More ]</a></h3>
							<p class="newstext">' . trunc( strip_tags($this->news_list[1]->news), 25) . '</p>
							<h5>Posted by <strong>' . $db->titleFromUsername( $this->news_list[1]->poster ) . '</strong> at 
								<strong>' . dateFromTimestamp_Long( $this->news_list[1]->date ) . '</strong></h5>
						</div>
					</div>
				');
			break;
			
			case 2:
				echo('
					<div class="news_header_left">
						<div class="news_header_title_4" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=4">' . $this->news_list[4]->title . '</a></div>
						<div class="news_header_title_3" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=3">' . $this->news_list[3]->title . '</a></div>
						<div class="news_header_text_2" style="background: url(templates/orange_rush/images/java/java_07.jpg); height: 118px;">
							<h3>' . $this->news_list[2]->title . ' - <a style="font-size: 10px;" href="index.php?view=news">[ View More ]</a></h3>
							<p class="newstext">' . trunc( strip_tags($this->news_list[2]->news), 25) . '</p>
							<h5>Posted by <strong>' . $db->titleFromUsername( $this->news_list[2]->poster ) . '</strong> at 
								<strong>' . dateFromTimestamp_Long( $this->news_list[2]->date ) . '</strong></h5>
						</div>
						<div class="news_header_title_1" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=1">' . $this->news_list[1]->title . '</a></div>
					</div>
				');
			break;
			
			case 3:
				echo('
					<div class="news_header_left">
						<div class="news_header_title_4" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=4">' . $this->news_list[4]->title . '</a></div>
						<div class="news_header_text_3" style="background: url(templates/orange_rush/images/java/java_07.jpg); height: 118px;">
							<h3>' . $this->news_list[3]->title . ' - <a style="font-size: 10px;" href="index.php?view=news">[ View More ]</a></h3>
							<p class="newstext">' . trunc( strip_tags($this->news_list[3]->news), 25) . '</p>
							<h5>Posted by <strong>' . $db->titleFromUsername( $this->news_list[3]->poster ) . '</strong> at 
								<strong>' . dateFromTimestamp_Long( $this->news_list[3]->date ) . '</strong></h5>
						</div>
						<div class="news_header_title_2" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=2">' . $this->news_list[2]->title . '</a></div>
						<div class="news_header_title_1" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=1">' . $this->news_list[1]->title . '</a></div>
					</div>
				');
			break;
			
			case 4:
				echo('
					<div class="news_header_left">
						<div class="news_header_text_4" style="background: url(templates/orange_rush/images/java/java_07.jpg); height: 118px;">
							<h3>' . $this->news_list[4]->title . ' - <a style="font-size: 10px;" href="index.php?view=news">[ View More ]</a></h3>
							<p class="newstext">' . trunc( strip_tags($this->news_list[4]->news), 25) . '</p>
							<h5>Posted by <strong>' . $db->titleFromUsername( $this->news_list[4]->poster ) . '</strong> at 
								<strong>' . dateFromTimestamp_Long( $this->news_list[4]->date ) . '</strong></h5>
						</div>
						<div class="news_header_title_3" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=3">' . $this->news_list[3]->title . '</a></div>
						<div class="news_header_title_2" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=2">' . $this->news_list[2]->title . '</a></div>
						<div class="news_header_title_1" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=1">' . $this->news_list[1]->title . '</a></div>
					</div>
				');
			break;
			
			default:
				echo('
					<div class="news_header_left">
						<div class="news_header_title_4" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=4">' . $this->news_list[4]->title . '</a>
						</div>
						<div class="news_header_title_3" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=3">' . $this->news_list[3]->title . '</a>
						</div>
						<div class="news_header_title_2" style="background: url(templates/orange_rush/images/java/java_03.jpg); height: 25px;">
							<a href="index.php?newsid=2">' . $this->news_list[2]->title . '</a>
						</div>
						<div class="news_header_text_1" style="background: url(templates/orange_rush/images/java/java_07.jpg); height: 118px;">
							<h3>' . $this->news_list[1]->title . ' - <a style="font-size: 10px;" href="index.php?view=news">[ View More ]</a></h3>
							<p class="newstext">' . trunc( strip_tags($this->news_list[1]->news), 25) . '</p>
							<h5>Posted by <strong>' . $db->titleFromUsername( $this->news_list[1]->poster ) . '</strong> at 
								<strong>' . dateFromTimestamp_Long( $this->news_list[1]->date ) . '</strong></h5>
						</div>
					</div>
				');
			break;
		}
	}
};

?>
