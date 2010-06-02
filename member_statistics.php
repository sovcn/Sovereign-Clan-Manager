
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">General Statistics</h3>
			</div>
			<div class="green_content">
				<table width="95%" class="general_info" cellpadding="15px" cellspacing="0">
					<tr>
						<td><h3>Total Members</h3></td>
						<td><h3>Average DSL</h3></td>
					</tr>
					<tr>
						<td><?php echo($statistic->getMembers()); ?></td>
						<td><?php echo(number_format($statistic->getAverageDsl(), 2)); ?></td>
					</tr>
				</table>
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Rank Statistics</h3>
			</div>
			<div class="green_content">
				<h4>The number of members for each rank.</h4>
				<table width="95%" class="general_info" cellpadding="15px" cellspacing="0">
					<?php
						$sql = 'select id from ranks order by id desc;';
						$result = @mysql_query($sql) or die("Mysql Error");
						for( $i = mysql_num_rows($result); $i > 0; $i-- )
						{
								if( !( ($i / 2) == ceil($i / 2) ) )
								{
								echo('<tr>
										<td>
										<img src="images/' . $i . '.jpg" /><span class="statistic">'. $statistic->getMembersByRank($i) .'</span>
										</td>
										');
									if( ($i - 1) > 0 )
									{
									echo('	<td>
											<img src="images/' . ($i - 1) . '.jpg" /><span class="statistic">'. $statistic->getMembersByRank(($i-1)) .'</span>
											</td>
											</tr>');
									}
								}
						}
					
					?>
				</table>
			</div>
			<div class="green_content_bottom">
			</div>
		</div>
		
		<div class="green_contentbox">
			<div class="green_content_top">
				<h3 class="content_box_header">Game Statistics</h3>
			</div>
			<div class="green_content">
				<h4>The number of members for each game.</h4>
				<table width="95%" class="general_info" cellpadding="15px" cellspacing="0">
					<?php
						$sql = 'select * from game;';
						$result = mysql_query($sql);
						
					
						echo('<tr>');
						while( $row = mysql_fetch_array($result) )
						{
							echo('<td>
									<img src="images/' . $row['image'] . '" alt="' . $row['name'] . '" />
								</td>
								');
						}
						echo('
							</tr>
							<tr>');
							
						$result = mysql_query($sql);
						while( $row = mysql_fetch_array($result) )
						{
							echo('
								<td>
									' . $statistic->getMembersByGame( $row['id'] ) . '
								</td>
							');
						}
						echo('
							</tr>
						</table>
					</div>
					<div class="green_content_bottom">
					</div>
				</div>
'); ?>