    <!-- Navbar -->
    <nav class="bg-green-600 p-4">
        <div class="container mx-auto flex justify-between items-center">

            <a href="dashboard.php" class="text-2xl font-bold text-green-800 flex align-item-center gap-2">
                <img src="../favicon.png" alt="P02" width="30px" height="30px">
                <span>Instructor Dashboard</span>
            </a>
            <div class="block lg:hidden">
                <button id="navbar-toggle" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:items-center lg:space-x-4">
                <a href="dashboard.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Dashboard</a>
                <a href="create_project.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Create
                    GIG</a>
                <a href="clients.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Jobs</a>
                <a href="profile.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Profile Settings</a>
                <a href="../logout.php" class="text-red-500 hover:bg-red-600 px-4 py-2 rounded-md">Logout</a>
            </div>
        </div>
    </nav>

    <div id="navbar-menu" class="hidden lg:hidden bg-green-600">
        <a href="dashboard.php" class="block text-white hover:bg-green-700 px-4 py-2">Dashboard</a>
        <a href="create_project.php" class="block text-white hover:bg-green-700 px-4 py-2">Create Project</a>
        <a href="clients.php" class="block text-white hover:bg-green-700 px-4 py-2">Clients</a>
        <a href="profile.php" class="block text-white hover:bg-green-700 px-4 py-2">Profile Settings</a>
        <a href="../logout.php" class="block text-red-500 hover:bg-red-600 px-4 py-2">Logout</a>
    </div>

    <script>
        // Toggle navbar for mobile view
        document.getElementById('navbar-toggle').addEventListener('click', function() {
            var menu = document.getElementById('navbar-menu');
            menu.classList.toggle('hidden');
        });
    </script>