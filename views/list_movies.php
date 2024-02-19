<?php include 'header.php'; ?>
<main class="container mt-5">
    <h1>List of Movies</h1>
    <?php if (empty($movies)): ?>
        <p>No movies found.</p>
    <?php else: ?>
        <?php foreach ($movies as $movie): ?>
            <div class="mb-4">
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Title:</strong> <?php echo $movie->getTitle(); ?><br>
                        <strong>Format:</strong> <?php echo $movie->getFormat(); ?><br>
                        <strong>Date:</strong> <?php echo $movie->getReleaseYear(); ?><br>
                    </li>
                    <?php foreach ($movie->actors as $actor): ?>
                        <li class="list-group-item">
                            <?php if (!empty($actor->name)): ?>
                                <strong>Actor:</strong> <?php echo $actor->name; ?><br>
                            <?php else: ?>
                                <p>No actor found for this movie.</p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    <a href="/movie/edit/<?php echo $movie->getId(); ?>" class="btn btn-primary mb-2 mt-2">Edit</a>
                    <a href="/movie/delete/<?php echo $movie->getId(); ?>" class="btn btn-primary mt-2">Delete</a>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
<?php include 'footer.php' ?>
