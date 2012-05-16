edit

<hr>

<form action="?edit" method="POST">
	<input type="text" name="licence_plate" value="<?=$this->car['licence_plate']?>">
	<input type="text" name="price" value="<?=$this->car['price']?>">
	<input type="submit">
</form>

<hr>

<a href="/car/1?destroy">delete this car</a>

<hr>

<a href="/car/all">all cars</a>
