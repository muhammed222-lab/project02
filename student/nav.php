<!-- Student Navigation Bar -->
<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">

        <a href="dashboard.php" class="text-2xl font-bold text-green-800 flex align-item-center gap-2">
            <img src="../favicon.png" alt="P02" width="30px" height="30px">
            <span>Student Dashboard</span>
        </a>
        <div class="hidden md:flex space-x-4">
            <a href="dashboard.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded">Dashboard</a>
            <a href="find_project.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded">Find
                Projects</a>
            <a href="my_projects.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded">My
                Projects</a>
            <a href="messages.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded">Messages</a>
            <a href="settings.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded">Settings</a>
            <a href="logout.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded">Logout</a>
        </div>
        <div class="md:hidden flex items-center">
            <button id="mobile-menu-button" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    <div id="mobile-menu" class="hidden md:hidden">
        <div class="flex flex-col mt-2 space-y-1">
            <a href="dashboard.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block px-4 py-2 rounded">Dashboard</a>
            <a href="find_project.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block px-4 py-2 rounded">Find Projects</a>
            <a href="my_projects.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block px-4 py-2 rounded">My Projects</a>
            <a href="messages.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block px-4 py-2 rounded">Messages</a>
            <a href="settings.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block px-4 py-2 rounded">Settings</a>
            <a href="../logout.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block px-4 py-2 rounded">Logout</a>
        </div>
    </div>
</nav>

<!-- JavaScript for Mobile Menu -->
<script>
document.getElementById('mobile-menu-button').addEventListener('click', function() {
    var menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
});
</script>