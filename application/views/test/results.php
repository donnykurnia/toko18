
<?php $i=0; foreach ($results as $result): ?>
	<li>
	<?php if ($result['Result'] == 'Passed'): ?>
		<div class="pas">

			[PASSED] <?=$result['Test Name']?>

			<?php if ( ! empty($messages[$i])): ?>
			<div class="detail">
				<?=$messages[$i]?>&nbsp;
			</div>
			<?php endif; ?>

		</div>
	<?php else: ?>
		<div class="err">

			[FAILED] <?=$result['Test Name']?>

			<?php if ( ! empty($messages[$i])): ?>
			<div class="detail">
				<?=$messages[$i]?>&nbsp;
			</div>
			<?php endif; ?>

		</div>
	<?php endif; ?>
	</li>
<?php $i++; endforeach; ?>

