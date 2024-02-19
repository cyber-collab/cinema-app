<?php include "header.php"; ?>

<div class="container mt-5">
    <h1>List of Movies</h1>
    <?php if (empty($movies)): ?>
        <p>No movies found.</p>
    <?php else: ?>
        <form action="/filter-surveys" method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <?php foreach ($movies as $movie): ?>
            <div class="mb-4">
        <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Title:</strong> <?php echo $movie->getTitle(); ?><br>
                        <strong>Fromat:</strong> <?php echo $movie->getFormat(); ?><br>
                        <strong>Realise year:</strong> <?php echo $movie->getReleaseYear(); ?><br>
                    </li>
                </ul>
            </div>
            <?php foreach ($movie->actors as $actor): ?>
                <li class="list-group-item">
                    <?php if (!empty($actor->name)): ?>
                        <strong>Actors:</strong> <?php echo $actor->name; ?><br>
                    <?php else: ?>
                        <p>No answers found for this question.</p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php include "footer.php"; ?>
