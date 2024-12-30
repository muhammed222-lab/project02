<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | PROJECT 02</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
</head>

<body class="bg-gray-100">
    <?php
    session_start();
    include 'nav.php';

    // Check if admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: admin_login.php");
        exit();
    }

    // Retrieve admin email for welcome message
    $adminEmail = $_SESSION['admin_email'];
    ?>

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-4">Admin Dashboard</h1>

        <!-- Welcome message with admin email -->
        <div class="mb-6">
            <p class="text-xl text-gray-700">Welcome, <strong><?php echo htmlspecialchars($adminEmail); ?></strong>!</p>
        </div>

        <!-- User Type Chart -->
        <div class="bg-white p-6 rounded-lg border mb-8">
            <canvas id="userTypeChart" width="400" height="200"></canvas>
        </div>

        <!-- User Management Table -->
        <div class="bg-white p-6 rounded-lg border">
            <h2 class="text-2xl font-semibold mb-4">Manage Users</h2>

            <!-- Search Box -->
            <div class="mb-4">
                <input type="text" id="searchBox" placeholder="Search users by name or email..."
                    class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:border-green-500">
            </div>

            <!-- User Table -->
            <table class="w-full table-auto border border-gray-300 mt-4">
                <thead class="bg-gray-200">
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Name</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Email</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Role</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Status</th>
                        <th class="px-4 py-2 text-center text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Dynamically load user data from database -->
                </tbody>
            </table>
        </div>

        <!-- User Details Popup -->
        <div id="userDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg w-96">
                <h2 class="text-xl font-semibold mb-4">User Details</h2>
                <div id="userDetailsContent">
                    <!-- User details go here -->
                </div>
                <button onclick="closeModal()" class="mt-4 bg-green-600 text-white py-2 px-4 rounded-md">Close</button>
            </div>
        </div>
    </div>

    <script>
    // Sample data for user types, replace with actual data fetched from the backend
    const userTypeData = {
        labels: ['Student', 'Freelancer', 'Creator', 'Instructor'],
        datasets: [{
            label: '# of Users',
            data: [12, 19, 3, 5], // Replace with actual counts
            backgroundColor: ['#4CAF50', '#FF6384', '#36A2EB', '#FFCE56'],
            hoverBackgroundColor: ['#66BB6A', '#FF6384', '#36A2EB', '#FFCE56']
        }]
    };

    const ctx = document.getElementById('userTypeChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: userTypeData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        loadUsers(); // Load users on page load

        // Search functionality
        document.getElementById("searchBox").addEventListener("input", function() {
            let query = this.value.toLowerCase();
            filterUsers(query);
        });
    });

    function loadUsers() {
        fetch("fetch_users.php")
            .then(response => response.json())
            .then(data => renderUserTable(data))
            .catch(error => console.error("Error fetching users:", error));
    }

    function filterUsers(query) {
        let rows = document.querySelectorAll("#userTableBody tr");
        rows.forEach(row => {
            let name = row.querySelector(".userName").innerText.toLowerCase();
            let email = row.querySelector(".userEmail").innerText.toLowerCase();
            row.style.display = (name.includes(query) || email.includes(query)) ? "" : "none";
        });
    }

    function renderUserTable(users) {
        const tableBody = document.getElementById("userTableBody");
        tableBody.innerHTML = ""; // Clear table

        users.forEach(user => {
            const row = document.createElement("tr");
            row.innerHTML = `
            <td class="px-4 py-2 whitespace-nowrap userName">${user.name}</td>
            <td class="px-4 py-2 whitespace-nowrap userEmail">${user.email}</td>
            <td class="px-4 py-2 whitespace-nowrap">${user.role}</td>
            <td class="px-4 py-2 whitespace-nowrap">${user.status}</td>
            <td class="px-4 py-2 whitespace-nowrap">
                <button onclick="viewUser(${user.id})" class="bg-blue-600 text-white px-3 py-1 rounded-md mr-2">View</button>
                <button onclick="disableUser(${user.id})" class="bg-red-600 text-white px-3 py-1 rounded-md">Disable</button>
            </td>
        `;
            tableBody.appendChild(row);
        });
    }

    function viewUser(userId) {
        fetch(`get_user_details.php?id=${userId}`)
            .then(response => response.json())
            .then(user => showUserDetailsModal(user))
            .catch(error => console.error("Error fetching user details:", error));
    }

    function showUserDetailsModal(user) {
        const modal = document.getElementById("userDetailsModal");
        const content = document.getElementById("userDetailsContent");

        content.innerHTML = `
        <p><strong>Name:</strong> ${user.name}</p>
        <p><strong>Email:</strong> ${user.email}</p>
        <p><strong>Role:</strong> ${user.role}</p>
        <p><strong>Status:</strong> ${user.status}</p>
        <p><strong>Created At:</strong> ${user.created_at}</p>
        <p><strong>Last Login:</strong> ${user.last_login || "N/A"}</p>
    `;

        modal.classList.remove("hidden"); // Show modal
    }

    function closeModal() {
        document.getElementById("userDetailsModal").classList.add("hidden");
    }

    function disableUser(userId) {
        if (confirm("Are you sure you want to disable this user?")) {
            fetch(`disable_user.php?id=${userId}`, {
                    method: "POST"
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Display response
                    loadUsers(); // Reload user list
                })
                .catch(error => console.error("Error disabling user:", error));
        }
    }
    </script>
</body>

</html>