<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <form id="ajax-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="button" id="submit-btn">Submit</button>
    </form>

    <script>
        $(document).ready(function () {
            $('#submit-btn').click(function () {
                var formData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                
                $.ajax({
                    url: '{{ route("form.submit") }}', // Adjust route accordingly
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        alert('Form submitted successfully!');
                    },
                    error: function (xhr) {
                        alert('Something went wrong!');
                    }
                });
            });
        });
    </script>
</body>
</html>
 