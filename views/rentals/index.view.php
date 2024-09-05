<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1">
    <div class="title flex-between">
        <h1>Posudbe</h1>
        <div class="action-buttons">
            <a href="<?= $subDir ?>/rentals/create" type="submit" class="btn btn-primary">Dodaj novu</a>
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
                <th>Posudba</th>
                <th>Clan</th>
                <th>Film</th>
                <th>Cijena</th>
                <th class="table-action-col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rentals as $rental): ?>
                <tr> 
                    <td><?= $rental['id'] ?></td>
                    <td>
                        <a href="<?= $subDir ?>/rentals/show?pid=<?= $rental['id'] ?>&kid=<?= $rental['kopija_id'] ?>" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Prikazi posudbu"><i class="bi bi-credit-card"></i></a>
                        <?= $rental['datum_posudbe'] ?> - <?= $rental['datum_povrata'] ?? 'Posudjen' ?>
                    </td>
                    <td>
                        <a href="<?= $subDir ?>/members/show?id=<?= $rental['clan_id'] ?>" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Prikazi clana"><i class="bi bi-person-circle"></i></a>
                        <?= $rental['clanski_broj'] ?> - <?= $rental['ime'] ?> <?= $rental['prezime'] ?>
                    </td>
                    <td>
                        <a href="<?= $subDir ?>/movies/show?id=<?= $rental['film_id'] ?>" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Prikazi film"><i class="bi bi-camera-reels"></i></a>
                        <?= $rental['medij'] ?> - <?= mb_strimwidth($rental['naslov'], 0, 40, '...') ?>
                    </td>
                    <td>
                        <a href="<?= $subDir ?>/prices/show?id=<?= $rental['cjenik_id'] ?>" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Prikazi cjenik"><i class="bi bi-code-square"></i></a>
                        <?= formatPrice($rental['cijena'] * $rental['koeficijent']) ?> - <?= $rental['tip_filma'] ?>
                    </td>
                    <td>
                        <a href="<?= $subDir ?>/rentals/edit?pid=<?= $rental['id'] ?>&kid=<?= $rental['kopija_id'] ?>" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Uredi posudbu"><i class="bi bi-pencil"></i></a>
                        <form action="<?= $subDir ?>/rentals/destroy" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="pid" value="<?= $rental['id'] ?>">
                            <input type="hidden" name="kid" value="<?= $rental['kopija_id'] ?>">
                            <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Obrisi posudbu"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>