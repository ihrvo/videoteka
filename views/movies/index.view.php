<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1">
    <div class="title flex-between">
        <h1>Filmovi</h1>
        <div class="action-buttons">
            <a href="<?= $subDir ?>/movies/create" type="submit" class="btn btn-primary">Dodaj novi</a>
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
                <th>Naslov</th>
                <th>Dostupnost</th>
                <th>Godina</th>
                <th>Žanr</th>
                <th>Tip Filma</th>
                <th class="table-action-col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movies as $movie): ?>
                <tr>
                    <td><?= $movie['id'] ?></td>
                    <td><a href="<?= $subDir ?>/movies/show?id=<?= $movie['id'] ?>"><?= $movie['naslov'] ?></td>
                    <td><small><?php 
                    foreach ($movie['formats'] as $format) {
                        ?><i class="bi bi-<?= $format['icon'] ?> me-1"></i><?php
                        echo $format['tip'] . "(" . $format['ukupno'] .") | <small> Na stanju: " . $format['dostupno']
                        . " | U posudbi: " . $format['nedostupno'] . "</small><br>";
                    }
                    ?></small></td>
                    <td><?= $movie['godina'] ?></td>
                    <td><?= $movie['zanr'] ?></td>
                    <td><?= $movie['tip_filma'] ?></td>
                    <td>
                        <a href="<?= $subDir ?>/movies/edit?id=<?= $movie['id'] ?>" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit price"><i class="bi bi-pencil"></i></a>
                        <form action="<?= $subDir ?>/movies/destroy" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="id" value="<?= $movie['id'] ?>">
                            <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Obriši film"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>