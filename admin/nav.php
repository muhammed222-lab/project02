<?php
// Start the session to get the admin email
$admin_email = isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : 'Admin';
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Navbar -->
<nav class="bg-green-600 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="./admin_dashboard.php" class="text-2xl font-bold text-green-800 flex align-item-center gap-2">
            <img src="./favicon.png" alt="P02" width="30px" height="30px">
            <span>Admin Dashboard</span>
        </a>

        <!-- Welcome Message with Admin Email -->
        <div class="text-white hidden lg:inline-block">
            Welcome, <?php echo htmlspecialchars($admin_email); ?>
        </div>

        <div class="block lg:hidden">
            <button id="navbar-toggle" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Desktop Navigation Links -->
        <div class="hidden lg:flex lg:items-center lg:space-x-4">
            <a href="admin_profile.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Admin Profile</a>
            <a href="admin_dashboard.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Dashboard</a>
            <a href="instructor.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Instructor</a>
            <a href="creator.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Creator</a>
            <a href="students.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Students</a>
            <a href="freelancer.php" class="text-white hover:bg-green-700 px-4 py-2 rounded-md">Freelancer</a>
            <a href="../logout.php" class="text-red-500 hover:bg-red-600 px-4 py-2 rounded-md">Logout</a>
        </div>
    </div>
</nav>

<!-- Mobile Navigation Menu -->
<div id="navbar-menu" class="hidden lg:hidden bg-green-600">
    <a href="admin_profile.php" class="block text-white hover:bg-green-700 px-4 py-2">Admin Profile</a>
    <a href="dashboard.php" class="block text-white hover:bg-green-700 px-4 py-2">Dashboard</a>
    <a href="instructor.php" class="block text-white hover:bg-green-700 px-4 py-2">Instructor</a>
    <a href="creator.php" class="block text-white hover:bg-green-700 px-4 py-2">Creator</a>
    <a href="students.php" class="block text-white hover:bg-green-700 px-4 py-2">Students</a>
    <a href="freelancer.php" class="block text-white hover:bg-green-700 px-4 py-2">Freelancer</a>
    <a href="../logout.php" class="block text-red-500 hover:bg-red-600 px-4 py-2">Logout</a>
</div>

<script>
// Toggle navbar for mobile view
document.getElementById('navbar-toggle').addEventListener('click', function() {
    var menu = document.getElementById('navbar-menu');
    menu.classList.toggle('hidden');
});
</script>