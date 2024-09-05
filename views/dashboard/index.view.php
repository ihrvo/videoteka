<?php include_once base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1">
    <div class="title flex-between">
        <h2>Dodaj novu posudbu</h2>
    </div>

    <hr>
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
            <?= $message['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <form class="row g-3 mt-3" action="<?= $subDir ?>/dashboard/store" method="POST">
        <div class="col-md-4">
            <label for="movies" class="form-label ps-1">Filmovi</label>
            <select class="form-select" aria-label="Default select example" name="film_id">
                <option>Odaberite film</option>
                <?php foreach ($movies as $movie): ?>
                    <option value="<?= $movie['id'] ?>"><?= $movie['naslov'] . ' (' . $movie['godina'] . ' g.)' ?>
                    <?php 
                    foreach ($movie['formats'] as $format) {
                        echo " | " . $format['dostupno'] ." kom " . $format['tip'] . " ";
                    }
                    ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="mediji" class="form-label ps-1">Medij</label>
            <select class="form-select" aria-label="Default select example" name="medij_id">
                <option>Odaberite medij</option>
                <?php foreach ($mediji as $medij): ?>
                    <option value="<?= $medij['id'] ?>"><?= $medij['tip'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="clanovi" class="form-label ps-1">Član</label>
            <select class="form-select" aria-label="Default select example" name="clan_id">
                <option>Odaberite člana</option>
                <?php foreach ($clanovi as $clan): ?>
                    <option value="<?= $clan['id'] ?>"><?= $clan['clanski_broj'] . ' - ' . $clan['ime'] . ' ' . $clan['prezime'] ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-success mb-3">Spremi</button>
        </div>
    </form>
    <h3>Aktivne posudbe</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Datum posudbe</th>
                <th>Član</th>
                <th>Film</th>
                <th>Cijena</th>
                <th class="table-action-col">Vrati</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rentals as $rental): ?>
                <tr>
                    <td><?= $rental['pid'] ?></td>
                    <td>
                        <a href="<?= $subDir ?>/rentals/show?pid=<?= $rental['pid'] ?>&kid=<?= $rental['kid'] ?>" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Prikaži posudbu"><i class="bi bi-credit-card"></i></a>
                        <?= $rental['datum_posudbe'] ?>
                    </td>
                    <td>
                        <a href="<?= $subDir ?>/members/show?id=<?= $rental['cid'] ?>" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Prikaži clana"><i class="bi bi-person-circle"></i></a>
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
                        <form action="<?= $subDir ?>/rentals/destroy" method="POST" class="d-inline">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="pid" value="<?= $rental['pid'] ?>">
                            <input type="hidden" name="kid" value="<?= $rental['kid'] ?>">
                            <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Oznaci vraceno"><i class="bi bi-arrow-counterclockwise"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</main>

<?php include_once base_path('views/partials/footer.php'); ?>