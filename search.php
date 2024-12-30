<?php

require_once 'db.php';

$query = $_GET['query'] ?? '';

// Display the search form at the top of this page for additional searches
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-green-800 mb-6">Search Results for "<?php echo htmlspecialchars($query); ?>"
        </h1>

        <!-- Search and Sorting Options -->
        <div class="flex justify-between mb-4">
            <form action="search_results.php" method="GET" class="flex">
                <form action="search.php" method="GET" class="flex">
                    <input type="text" name="query" value="<?php echo htmlspecialchars($query); ?>"
                        placeholder="Search again..." class="px-4 py-2 rounded-l-md border border-gray-300 w-80">
                    <button type="submit"
                        class="bg-green-700 text-white px-6 py-2 rounded-r-md hover:bg-green-800">Search</button>
                </form>
                <button onclick="window.location.href='./all_project.php'"
                    class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-700 mr-4">Search All
                    Projects</button>
                <select id="sortOptions" class="px-4 py-2 rounded-md border border-gray-300">
                    <option value="date">Sort by Date</option>
                    <option value="price">Sort by Price</option>
                </select>
        </div>

        <!-- Search Results Container -->
        <div id="resultsContainer" class="flex gap-3">
            <!-- AJAX-loaded results will appear here -->
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Load initial results based on the query
        loadResults("<?php echo $query; ?>", null, 1);

        // Sort change event
        $('#sortOptions').on('change', function() {
            const sortOption = $(this).val();
            loadResults("<?php echo $query; ?>", sortOption, 1);
        });

        // Function to load results
        function loadResults(query, sort, page) {
            $.ajax({
                url: 'fetch_results.php',
                method: 'POST',
                data: {
                    query: query,
                    sort: sort,
                    page: page
                },
                success: function(response) {
                    $('#resultsContainer').html(response);
                }
            });
        }
    });
    </script>


</body>

</html>