<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1">
    <div class="title flex-between">
        <h1>Mediji</h1>
        <div class="action-buttons">
            <a href="<?= $subDir ?>/formats/create" type="submit" class="btn btn-primary">Dodaj novi</a>
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
                <th>Tip</th>
                <th>Koeficijent</th>
                <th class="table-action-col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mediji as $medij): ?>
                <tr>
                    <td>
                        <a href="<?= $subDir ?>/formats/show?id=<?= $medij['id'] ?>"><?= $medij['id'] ?></a>
                    </td>
                    <td><?= $medij['tip'] ?></td>
                    <td><?= $medij['koeficijent'] ?></td>
                    <td>
                        <a href="<?= $subDir ?>/formats/edit?id=<?= $medij['id'] ?>" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit medij"><i class="bi bi-pencil"></i></a>
                        <form action="<?= $subDir ?>/formats/destroy" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="id" value="<?= $medij['id'] ?>">
                            <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete medij"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>