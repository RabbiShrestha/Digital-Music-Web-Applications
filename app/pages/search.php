<?php require page('includes/header')?>

<div class="section-title">Search for: <?=$_GET['find']?></div>

<section class="content">
	
	<?php 
		$title = $_GET['find'] ?? null;
		$rows = [];

		if(!empty($title)){
			
			$query = "SELECT * FROM songs ORDER BY views DESC LIMIT 24";
			$allSongs = db_query($query);

			
			foreach($allSongs as $song) {
				if (stripos($song['title'], $title) !== false) { 
					$rows[] = $song;
				}
			}
		}
	?>

	<?php if(!empty($rows)):?>
		<?php foreach($rows as $row):?>
			<?php include page('includes/song')?>
		<?php endforeach;?>
	<?php else:?>
		<div class="m-2">No songs found</div>
	<?php endif;?>

</section>

<?php require page('includes/footer')?>

