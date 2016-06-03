<?php if ($this->debug):?>
	<p>
	<?php
	$contents = null;
	$backtrace = $this->error->getTrace();

	if (is_array($backtrace))
	{
		$j = 1;
		?>
		<table cellpadding="0" cellspacing="0" class="Table table table-striped">
			<thead>
			<tr>
				<th colspan="3" class="th"><strong>Call stack</strong></th>
			</tr>
			<tr>
				<th class="th"><strong>#</strong></th>
				<th class="th"><strong>Function</strong></th>
				<th class="th"><strong>Location</strong></th>
			</tr>
			</thead>
			<tbody>
			<?php for ($i = count($backtrace) - 1; $i >= 0; $i--):?>
				<tr>
					<td class="TD"><?php echo $j;?></td>
					<td class="TD">
						<?php
						if (isset($backtrace[$i]['class']))
						{
							echo $backtrace[$i]['class'] . $backtrace[$i]['type'] . $backtrace[$i]['function'] . '()';
						}
						else
						{
							echo $backtrace[$i]['function'] . '()';
						}
						?>
					</td>
					<td class="TD">
						<?php
						if (isset($backtrace[$i]['file']))
						{
							echo $backtrace[$i]['file'] . ':' . $backtrace[$i]['line'];
						}
						else
						{
							echo '&#160';
						}
						?>
					</td>
				</tr>
				<?php $j++;?>
			<?php endfor;?>
			</tbody>
		</table>
	<?php } ?>
	</p>
<?php endif; ?>
