<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body class="font-sans bg-gray-100 text-gray-800">
    <div class="container mx-auto p-6 bg-white border">
        <!-- Responsive Navigation -->
        <nav class="bg-white border mb-6">
            <div class="container mx-auto px-4 py-2 flex justify-between items-center">
                <a href="#" class="text-2xl font-bold text-green-800">Project 02 - Find Project, gigs or creator</a>
                <button id="clearSearch" class="text-sm bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">Clear
                    Search</button>
            </div>
        </nav>

        <!-- Search Form -->
        <form id="searchForm" class="flex mb-4" style="width: 300px;">
            <input type="text" id="queryInput" placeholder="Search..." class="px-4 py-2 border border-gray-300 w-full">
            <button type="submit" class="bg-green-700 text-white px-6 py-2 hover:bg-green-800">Search</button>
        </form>

        <!-- Category Buttons -->
        <div class=" justify-center space-x-4 mb-6">
            <button class="category-btn border-l px-3 border-black-400" data-category="projects">⇉ Projects</button>
            <button class="category-btn border-l px-3 border-black-400" data-category="gigs">⇉ Gigs</button>
            <button class="category-btn border-l px-3 border-black-400" data-category="creators">⇉ Creators</button>
            <button class="" data-category="freelancers">⇉ Freelancers</button>
        </div>

        <!-- Results Container -->
        <div id="resultsContainer" class="space-y-4 flex gap-3 flex-wrap align-items-center mt-3">
            <!-- AJAX results will appear here -->
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Load all projects by default
        loadResults('projects', '');

        // Handle category button clicks
        $('.category-btn').on('click', function() {
            const category = $(this).data('category');
            const query = $('#queryInput').val();
            loadResults(category, query);
        });

        // Handle search form submission
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            const category = $('.category-btn.active').data('category') || 'projects';
            const query = $('#queryInput').val();
            loadResults(category, query);
        });

        // Clear search button functionality
        $('#clearSearch').on('click', function() {
            $('#queryInput').val('');
            loadResults('projects', ''); // Reset to default view
        });

        // Load results function
        function loadResults(category, query) {
            $.ajax({
                url: 'fetch_all.php',
                method: 'POST',
                data: {
                    category: category,
                    query: query
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