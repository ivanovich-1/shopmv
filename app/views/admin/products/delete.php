<?php include_once (VIEWS . 'header.php') ?>
<div class="card p-4 bg-light">
	<div class="card-header">
		<h1 class="text-center">Eliminación de un producto</h1>
	</div>
	<div class="card-body">
		<form action="<?= ROOT ?>adminproduct/delete/<?= $data['data']->id ?>" method="POST">
			<div class="form-group text-left">
				<label for="type">Tipo de producto:</label>
				<select name="type" id="type" class="form-control">
					<option value="">Selecciona el tipo de producto</option>
					<?php foreach($data['type'] as $type): ?>
						<option value="<?= $type->value ?>" <?= (isset($data['data']->type) && $data['data']->type == $type->value) ? ' selected ' : '' ?> >
							<?= $type->description ?>
						</option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="form-group text-left">
				<label for="name">Nombre:</label>
				<input type="text" name="name" class="form-control" required placeholder="Escribe el nombre del producto" value="<?= $data['data']->name ?? '' ?>" disabled>
			</div>
			<div class="form-group text-left">
				<label for="price">Precio del producto</label>
				<input type="text" name="price" class="form-control" pattern="^(\d\-)?\d*\.?\d*$" placeholder="Escribe el precio del producto sin comas ni símbolos" required value="<?= $data['data']->price ?? '' ?>" disabled>
			</div>
			<div class="form-group text-left">
				<input type="submit" value="Eliminar" class="btn btn-success">
				<a href="<?= ROOT ?>adminproduct" class="btn btn-info">Cancelar</a>
				<p>Una vez borrado, la información no será recuperable</p>
			</div>
		</form>
	</div>
</div>
<?php include_once (VIEWS . 'footer.php') ?>