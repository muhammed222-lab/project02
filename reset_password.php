<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionicons@5.5.2/dist/css/ionicons.min.css">
    <link rel="shortcut icon" href="./favicon.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-4">Reset Password</h2>
        <form id="resetPasswordForm" method="POST">
            <div id="emailSection" class="mb-4">
                <label for="email" class="block text-sm font-semibold">Email:</label>
                <input type="email" id="email" name="email"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div id="nameSection" class="mb-4">
                <label for="name" class="block text-sm font-semibold">Enter Your Name (first or last):</label>
                <input type="text" id="name" name="name"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:border-green-500"
                    required>
            </div>
            <div class="mb-4 flex justify-end">
                <button type="submit" id="submitButton"
                    class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">Submit</button>
            </div>
            <div id="errorMessage" class="text-red-500 text-sm"></div>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $('#resetPasswordForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const email = $('#email').val();
            const name = $('#name').val();

            // AJAX request to validate email and name
            $.ajax({
                url: 'check_email_name.php',
                type: 'POST',
                data: {
                    email: email,
                    name: name
                },
                dataType: 'json',
                success: function(response) {
                    if (response.emailFound && response.nameMatches) {
                        // Redirect to new password page with email in URL
                        window.location.href =
                            `new_password.php?email=${encodeURIComponent(email)}`;
                    } else {
                        $('#errorMessage').removeClass('hidden');
                        if (!response.emailFound) {
                            $('#errorMessage').text('Email not found.');
                        } else if (!response.nameMatches) {
                            $('#errorMessage').text('Name does not match.');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $('#errorMessage').text('An error occurred. Please try again.')
                        .removeClass('hidden');
                }
            });
        });
    });
    </script>
</body>

</html>