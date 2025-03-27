<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step-by-Step Quiz</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .question-box { background: #f8f9fa; padding: 45px; border-radius: 30px; margin-bottom: 15px; }
        .pagination { margin-top: 20px; text-align: center; }
        .hidden { display: none; }
        #attempted-count { position: absolute; top: 10px; right: 20px; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-5 quiz-container position-relative">
        <h2 class="mb-4 text-center">Quiz</h2>
        <div id="attempted-count" class="badge bg-primary p-2">Attempted: 0 / 10</div>
        <form id="quiz-form">
            <div id="quiz-container"></div>
            <div class="pagination">
                <button type="button" id="prev-btn" class="btn btn-secondary mx-1" disabled>Previous</button>
                <button type="button" id="next-btn" class="btn btn-primary mx-1">Next</button>
            </div>
            <button type="submit" class="btn btn-success mt-3 d-none" id="submit-btn">Submit Quiz</button>
        </form>
        <div class="row">
            <div id="quiz-result" class="col-12 mt-3 mb-4 text-center"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let quizData = [];
            let currentPage = 0;
            let selectedAnswers = {}; // Stores selected answers
            let attemptedQuestions = new Set(); // Tracks attempted questions

            $.ajax({
                url: '{{ route("form.submit") }}', // Adjust route accordingly
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function (response) {
                    quizData = response.quizzes;
                    renderQuizPage();
                },
                error: function () {
                    alert('Something went wrong!');
                }
            });

            function renderQuizPage() {
                let quizContainer = $('#quiz-container');
                quizContainer.empty(); // Clear previous question
                let quiz = quizData[currentPage]; // Get current question
                
                let options = `
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question${currentPage}" value="${quiz.optionone}" ${selectedAnswers[currentPage] === quiz.optionone ? 'checked' : ''}>
                        <label class="form-check-label">${quiz.optionone}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question${currentPage}" value="${quiz.optiontwo}" ${selectedAnswers[currentPage] === quiz.optiontwo ? 'checked' : ''}>
                        <label class="form-check-label">${quiz.optiontwo}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question${currentPage}" value="${quiz.optionthree}" ${selectedAnswers[currentPage] === quiz.optionthree ? 'checked' : ''}>
                        <label class="form-check-label">${quiz.optionthree}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question${currentPage}" value="${quiz.optionfour}" ${selectedAnswers[currentPage] === quiz.optionfour ? 'checked' : ''}>
                        <label class="form-check-label">${quiz.optionfour}</label>
                    </div>
                `;

                quizContainer.append(`
                    <div class="question-box">
                        <h5>${quiz.serial_number}. ${quiz.question}</h5>
                        ${options}
                    </div>
                `);

                updatePaginationButtons();
            }

            function updatePaginationButtons() {
                $('#prev-btn').prop('disabled', currentPage === 0);
                $('#next-btn').toggleClass('d-none', currentPage === quizData.length - 1);
                $('#submit-btn').toggleClass('d-none', currentPage !== quizData.length - 1);
            }

            $('#next-btn').click(function () {
                if (currentPage < quizData.length - 1) {
                    currentPage++;
                    renderQuizPage();
                }
            });

            $('#prev-btn').click(function () {
                if (currentPage > 0) {
                    currentPage--;
                    renderQuizPage();
                }
            });

            $(document).on('change', '.form-check-input', function () {
                let questionIndex = currentPage;
                let selectedValue = $(`input[name="question${questionIndex}"]:checked`).val();
                selectedAnswers[questionIndex] = selectedValue; // Store selected answer
                
                if (selectedValue) {
                    attemptedQuestions.add(questionIndex);
                } else {
                    attemptedQuestions.delete(questionIndex);
                }

                $('#attempted-count').text(`Attempted: ${attemptedQuestions.size} / ${quizData.length}`);
            });

            $('#quiz-form').submit(function (e) {
                e.preventDefault();

                let totalQuestions = quizData.length;
                let attemptedCount = attemptedQuestions.size;
                
                if (attemptedCount < totalQuestions) {
                    let confirmSubmit = confirm(`You have attempted ${attemptedCount} out of ${totalQuestions} questions. Are you sure you want to submit?`);
                    if (!confirmSubmit) return;
                }

                let score = 0;
                quizData.forEach((quiz, index) => {
                    let selectedAnswer = selectedAnswers[index];
                    if (selectedAnswer && selectedAnswer === quiz.answer) {
                        score++;
                    }
                });

                $('#quiz-result').html(`<h4>Your Score: ${score} / ${quizData.length}</h4>`);
                $('#prev-btn, #next-btn, #submit-btn').prop('disabled', true);
            });
        });
    </script>
</body>
</html>
