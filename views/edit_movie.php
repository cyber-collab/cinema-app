<?php include "header.php"; ?>

<div class="container mt-5">
    <h1>Edit Movie</h1>
    <form action="/movie/update/<?php echo $movie->getId(); ?>" method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo $movie->getTitle(); ?>" required>
        </div>

        <div class="form-group">
            <label for="format">Format:</label>
            <select id="format" name="format" class="form-control">
                <option value="VHS" <?php if ($movie->getFormat() == 'VHS') echo 'selected'; ?>>VHS</option>
                <option value="DVD" <?php if ($movie->getFormat() == 'DVD') echo 'selected'; ?>>DVD</option>
                <option value="Blu-ray" <?php if ($movie->getFormat() == 'Blu-ray') echo 'selected'; ?>>Blu-ray</option>
            </select>
        </div>

        <div class="form-group">
            <label for="realise_year">Realise date</label>
            <input type="date" id="realise_year" name="realise_year" class="form-control"  value="<?php echo $movie->getReleaseYear(); ?>" required>
        </div>

        <?php foreach ($movie->getActors() as $actor): ?>
            <div class="form-group actor-group">
                <div class="mb-3"></div>
                <label for="actor_<?php echo $actor->getId(); ?>">Actor Name:</label>
                <input type="text" name="actor_text[<?php echo $actor->getId(); ?>]" class="form-control" value="<?php echo $actor->name; ?>">

                <button type="button" class="btn btn-danger remove-actor ml-2">Remove Actor</button>
            </div>
        <?php endforeach; ?>
        <div id="actorsContainer">
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
    </form>
</div>
<?php include "footer.php"; ?>
<script>
    $(document).ready(function() {
        let actorCounter = 0;

        $(document).on('click', '.add-actor', function() {
            const actorsContainer = $('#actorsContainer');
            const newActorGroup = $(
                `<div class="form-group actor-group" data-actor="${actorCounter}">` +
                `<label for="actor_text_${actorCounter}">Actor Text:</label>` +
                `<input type="text" name="actor_text[${actorCounter}]" class="form-control" id="actor_text_${actorCounter}" required>` +
                `<button type="button" class="btn btn-danger remove-actor ml-2">Remove Actor</button>` +
                `</div>`
            );
            actorsContainer.append(newActorGroup);
            actorCounter++;
        });

        $(document).on('click', '.remove-actor', function() {
            const actorGroup = $(this).closest('.actor-group');
            const actorIdInput = actorGroup.find('input[name^="actor_text["]');
            const actorId = actorIdInput.attr('name').match(/\[(\d+)\]/)[1];

            const deletedActorsInput = $('<input type="hidden" name="deleted_actors[]">');
            deletedActorsInput.val(actorId);

            actorGroup.replaceWith(deletedActorsInput);
        });
    });
</script>

