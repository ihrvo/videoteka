<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1">
    <div class="title flex-between">
        <h1>Cjenik</h1>
        <div class="action-buttons">
            <a href="<?= $subDir ?>/prices/create" type="submit" class="btn btn-primary">Dodaj novu cijenu</a>
        </div>
    </div>

    <hr>
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
            <?= $message['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Tip filma</th>
                <th>Cijena</th>
                <th>Zakasnina po danu</th>
                <th class="table-action-col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prices as $price): ?>
                <tr>
                    <td>
                        <a href="<?= $subDir ?>/prices/show?id=<?= $price['id'] ?>"><?= $price['id'] ?></a>
                    </td>
                    <td><?= $price['tip_filma'] ?></td>
                    <td><?= $price['cijena'] ?></td>
                    <td><?= $price['zakasnina_po_danu'] ?></td>
                    <td>
                        <a href="<?= $subDir ?>/prices/edit?id=<?= $price['id'] ?>" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit price"><i class="bi bi-pencil"></i></a>
                        <form action="<?= $subDir ?>/prices/destroy" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="id" value="<?= $price['id'] ?>">
                            <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete price"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>