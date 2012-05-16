index

<hr>

<?foreach($this->cars as $n => $car):?>

	<a href="/car/<?=$n?>">
		<?=$car['licence_plate']?> :
		cost <?=$car['price']?>
	</a>

<?endforeach;?>