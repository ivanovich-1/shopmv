<?php include_once (VIEWS . 'header.php') ?>
<div class="card p-4 bg-light">
	<div class="card header">
		<h1 class="text-center"> Eliminacion de un usuario administrador</h1>
	</div>
	<div class="card-body">
		<form action="<?= ROOT ?>adminuser/delete/<?= $data['data']->id ?>" method="POST">
			<div class="form-group text-left">
				<label for="name">Usuario:</label>
				<input type="text" name="name" class="form-control" disabled value="<?= isset($data['data']->name) ? $data['data']->name : '' ?>">
			</div>
			<div class="form-group text-left">
				<label for="email">Correo Electrónico:</label>
				<input type="email" name="email" class="form-control" disabled value="<?= isset($data['data']->email) ? $data['data']->email : '' ?>">
			</div>
			<div class="form-group">
				<label for="status">Estado: </label>
				<select name="status" id="status" class="form-control" disabled>
					<?php foreach($data['status'] as $status): ?>
						<option value="<?= $status->value ?>" <?= $status->value == $data['data']->status ? ' selected ' : '' ?>><?= $status->description ?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="form-group text-left">
				<input type="submit" value="Borrar" class="btn btn-danger">
				<a href="<?= ROOT ?>adminuser" class="btn btn-info">Cancelar</a>
				<p>Una vez eliminado no se podrá recuperar</p>
			</div>
		</form>
	</div>
</div>
<?php include_once (VIEWS . 'footer.php') ?>