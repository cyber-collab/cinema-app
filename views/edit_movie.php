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

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
    </form>
</div>
<?php include "footer.php"; ?>
<script>
    $(document).ready(function() {
        function generateUniqueId() {
            return 'new_' + Date.now();
        }

        $(document).on('click', '.add-question', function() {
            const questionsContainer = $('#questionsContainer');
            const newQuestionId = generateUniqueId();
            const newAnswerId = generateUniqueId();

            const newQuestionGroup = $(
                '<div class="form-group question-group">' +
                '<div class="mb-3"></div>' +
                '<label for="question_text">Question Text:</label>' +
                `<input type="text" name="question_text[${newQuestionId}]" class="form-control">` +
                '<div class="form-group answers-group ml-3">' +
                '<label for="answer_text">Answer Text:</label>' +
                `<input type="text" name="answer_text[${newQuestionId}][${newAnswerId}]" class="form-control">` +
                '<button type="button" class="btn btn-danger remove-answer">Remove Answer</button>' +
                '</div>' +
                '<div class="mt-2">' +
                '<button type="button" class="btn btn-primary add-answer">Add Answer</button>' +
                '<button type="button" class="btn btn-danger remove-question ml-2">Remove Question</button>' +
                '</div>' +
                '</div>'
            );

            questionsContainer.append(newQuestionGroup);
        });

        $(document).on('click', '.add-answer', function() {
            const newAnswerId = generateUniqueId();

            const newAnswerInput = $(
                '<div class="mb-3">' +
                `<input type="text" name="answer_text[<?php echo $question->getId()?>][${newAnswerId}]" class="form-control">` +
                '<button type="button" class="btn btn-danger remove-answer mt-2">Remove Answer</button>' +
                '</div>'
            );

            const addAnswerButton = $(this).closest('.question-group').find('.add-answer');
            addAnswerButton.before(newAnswerInput);
        });

        $(document).on('click', '.remove-question', function() {
            const questionGroup = $(this).closest('.question-group');
            const questionIdInput = questionGroup.find('input[name^="question_text["]');
            const questionId = questionIdInput.attr('name').match(/\[(\d+)\]/)[1];

            const deletedQuestionsInput = $('<input type="hidden" name="deleted_questions[]">');
            deletedQuestionsInput.val(questionId);

            questionGroup.replaceWith(deletedQuestionsInput);
        });

        $(document).on('click', '.remove-answer', function() {
            const answerText = $(this).siblings('.answer-text');
            const nameAttribute = answerText.attr('name');

            if (nameAttribute == null) {
                $(this).closest('.mb-3').remove();
            } else {
                const match = nameAttribute.match(/\[(\d+)\]\[(\d+)\]/);

                if (match) {
                    const answerId = match[2];
                    const deletedAnswersInput = $('<input type="hidden" name="deleted_answers[]">');
                    deletedAnswersInput.val(answerId);

                    $(this).closest('.mb-3').replaceWith(deletedAnswersInput);
                }
            }
        });
    });
</script>
