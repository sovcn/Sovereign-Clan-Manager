<?php

if( $security->runSecurity(1, $user->username, 1, 1, 1) )
{
		$page = $_GET['page'];
		$sql = 'select *
				 from logs
				 order by id desc;';
		$result = mysql_query($sql);
		$rows = mysql_num_rows($result);
		$number_of_links = $rows / $config->getSetting('logs_perpage');
		
		echo('
		<div class="contentbox">
					<div align="center">
						<div class="content_back">
							<div align="center">
								<div class="content_header">
									<h3 class="content_box_header">Logs</h3>
								</div>
							</div>
			<table class="logs">
				<tr class="logs">
					<td class="logs_event">
						<h3 class="logs">Event</h3>
					</td>
					<td class="logs_time">
						<h3 class="logs">Time/Date</h3>
					</td>
				</tr>
		');
		
		$counter = 1;
		while( $row = mysql_fetch_array($result) )
		{
		if($counter >= (($config->getSetting('logs_perpage') * $page) - $config->getSetting('logs_perpage'))  && $counter <= ($config->getSetting('logs_perpage') * $page) )
			{
			echo('
			<tr class="logs">
			<td class="logs_event">' . $row['Event'] . '</td>
					<td class="logs_time">' . $row['datetime'] . '</td>
			</tr>');
			}
		$counter++;
		}
		$counter = 1;
		echo('
			</table>
			
			<div class="page_numbers">Page Numer: ');
			
		while( $counter <= $number_of_links + 1 )
		{
			echo('<a class="return" href="index.php?view=logs&page=' . $counter . '">' . $counter . ', </a>');
			$counter++;
		}
		echo('</div>
		
											</div>
					</div>
					<div align="center"><div class="content_box_footer"></div></div>
					
					
					</div>'); // ADDED 2 - 2 - 08 to fix a display problem
					
					
					
		/*while( $counter <= $upper_limit )
		{
			if( $counter >= $lower_limit )
			{
				$row = mysql_fetch_array($result);
				echo('<div class="log_row">' . $row['Event'] . ' ' . $row['datetime'] . '</div>');
			}
		$counter++;
		}
		*/
}
?>
