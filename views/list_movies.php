<?php include 'header.php'; ?>
<main class="container mt-5">
    <h1>List of Movies</h1>
    <?php if (empty($movies)): ?>
        <p>No surveys found.</p>
    <?php else: ?>
        <?php foreach ($movies as $movie): ?>
            <div class="mb-4">
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Title:</strong> <?php echo $movie->getTitle(); ?><br>
                        <strong>Foramt:</strong> <?php echo $movie->getFormat(); ?><br>
                    </li>
                    <!-- <?php foreach ($survey->questions as $question): ?>
                        <li class="list-group-item">
                            <strong>Question:</strong> <?php echo $question->question_text; ?><br>
                            <?php if (!empty($question->options)): ?>
                                <input type="hidden" name="question_id" value="<?php echo $question->getId(); ?>">
                                <ul class="list-group">
                                    <?php foreach ($question->options as $answer): ?>
                                        <li class="list-group-item">
                                            <input type="radio" name="answer_id" value="<?php echo $answer->getId(); ?>">
                                            <label class="form-check-label">
                                                <?php echo $answer->answer_text ?>
                                                - Numbers votes <?php echo $answer->getVotes()?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No answers found for this question.</p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?> -->
                    <a href="/survey/edit/<?php echo $survey->getId(); ?>" class="btn btn-primary mb-2 mt-2">Edit</a>
                    <a href="/survey/delete/<?php echo $survey->getId(); ?>" class="btn btn-primary mt-2">Delete</a>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>
<?php include 'footer.php' ?>
