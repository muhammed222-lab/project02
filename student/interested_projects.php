<?php
session_start();
require_once '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch Saved Projects
$query = "SELECT p.*, pi.interest_date, u.name as creator_name, u.email as creator_email
          FROM project_interests pi
          JOIN projects p ON pi.project_id = p.id
          JOIN users u ON p.creator_id = u.id
          WHERE pi.user_id = :user_id AND pi.is_bought = 0
          ORDER BY pi.interest_date DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$saved_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Paid Projects
$query = "SELECT p.*, pa.amount, pa.payment_date, pa.currency, pa.transaction_id
          FROM payments pa
          JOIN projects p ON pa.project_id = p.id
          WHERE pa.user_id = :user_id
          ORDER BY pa.payment_date DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$paid_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Pending Payments
$query = "SELECT p.*, pa.amount, pa.currency, pa.tx_ref
          FROM payments pa
          JOIN projects p ON pa.project_id = p.id
          WHERE pa.user_id = :user_id AND pa.status = 'pending'
          ORDER BY pa.payment_date DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$pending_projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Dashboard | Project Hub</title>
    <link rel="icon" href="../favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
    :root {
        --bg-primary: #222831;
        --bg-secondary: #393E46;
        --accent-color: #00ADB5;
        --text-primary: #EEEEEE;
    }

    body {
        background: linear-gradient(135deg, var(--bg-primary), var(--bg-secondary));
        min-height: 100vh;
        color: var(--text-primary);
        font-family: 'Inter', sans-serif;
    }

    .project-card {
        background-color: var(--bg-secondary);
        border: 1px solid var(--accent-color);
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .project-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px rgba(0, 173, 181, 0.2);
    }

    .btn-primary {
        background-color: var(--accent-color);
        color: var(--text-primary);
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: var(--accent-color);
    }

    .hidden {
        display: none;
    }

    .receipt {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        padding: 16px;
        max-width: 500px;
        margin: 0 auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    table th,
    table td {
        padding: 12px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .btn-primary {
        background-color: #00adb5;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #008f98;
    }

    .text-center {
        text-align: center;
    }

    @media print {
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: black;
            margin: 0;
            padding: 0;
        }

        h3,
        h4,
        h5 {
            color: black;
            margin-top: 0;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            color: black;
        }

        td {
            color: black;
        }

        button,
        .no-print {
            display: none !important;
            /* Hide buttons or any unnecessary elements */
        }

        .receipt {
            border: none;
            box-shadow: none;
            padding: 0;
            margin: 0;
        }
    }
    </style>
</head>

<body class="antialiased">
    <?php include 'nav.php'; ?>

    <main class="container mx-auto px-4 py-16 max-w-7xl">
        <header class="mb-12 text-center">
            <h1 class="text-5xl font-bold text-[var(--accent-color)] mb-4">Your Projects</h1>
            <p class="text-xl text-[var(--text-primary)] max-w-2xl mx-auto opacity-80">
                View and manage your saved, paid, and pending projects here.
            </p>
        </header>

        <!-- Saved Projects Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Saved Projects</h2>
            <?php if (empty($saved_projects)): ?>
            <p class="text-center text-[var(--text-primary)] opacity-80">No saved projects yet.</p>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($saved_projects as $project): ?>
                <div class="project-card rounded-2xl overflow-hidden p-6">
                    <h3 class="text-xl font-bold mb-4">
                        <?php echo htmlspecialchars($project['title']); ?>
                    </h3>
                    <p class="opacity-80 mb-4">
                        <?php echo htmlspecialchars($project['description']); ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>

        <!-- Paid Projects Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Paid Projects</h2>
            <?php if (empty($paid_projects)): ?>
            <p class="text-center text-[var(--text-primary)] opacity-80">No paid projects yet.</p>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($paid_projects as $project): ?>
                <div class="project-card rounded-2xl overflow-hidden p-6">
                    <h3 class="text-xl font-bold mb-4">
                        <?php echo htmlspecialchars($project['title']); ?>
                    </h3>
                    <p class="opacity-80 mb-4">
                        Amount Paid: <?php echo htmlspecialchars($project['amount']); ?>
                        <?php echo htmlspecialchars($project['currency']); ?>
                    </p>
                    <button class="btn-primary"
                        onclick="openReceiptModal('<?php echo htmlspecialchars($project['transaction_id']); ?>')">View
                        Receipt</button>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>

        <!-- Pending Projects Section -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Pending Projects</h2>
            <?php if (empty($pending_projects)): ?>
            <p class="text-center text-[var(--text-primary)] opacity-80">No pending projects yet.</p>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($pending_projects as $project): ?>
                <div class="project-card rounded-2xl overflow-hidden p-6">
                    <h3 class="text-xl font-bold mb-4">
                        <?php echo htmlspecialchars($project['title']); ?>
                    </h3>
                    <p class="opacity-80 mb-4">
                        Amount: <?php echo htmlspecialchars($project['amount']); ?>
                        <?php echo htmlspecialchars($project['currency']); ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Receipt Modal -->
    <!-- Receipt Modal -->
    <div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
            <button onclick="closeReceiptModal()"
                class="absolute top-4 right-4 text-gray-600 hover:text-black text-2xl">
                &times;
            </button>
            <div id="receiptContent" class="text-black">
                <!-- Receipt details will be loaded here dynamically -->
            </div>
        </div>
    </div>



    <script>
    function openReceiptModal(transactionId) {
        const receiptContent = document.getElementById('receiptContent');
        receiptContent.innerHTML = 'Loading receipt for transaction ID: ' + transactionId + '...';

        // Fetch receipt data
        fetch('fetch_receipt.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    transaction_id: transactionId
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    const receipt = data.data;

                    // Dynamically populate receipt content
                    receiptContent.innerHTML = `
                <div class="receipt">
                    <h3 class="text-2xl font-bold text-center mb-4">Payment Receipt</h3>
                    <table class="w-full border-collapse border border-gray-300">
                        <tbody>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Transaction ID</th>
                                <td class="border border-gray-300 px-4 py-2">${receipt.transaction_id}</td>
                            </tr>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Reference</th>
                                <td class="border border-gray-300 px-4 py-2">${receipt.tx_ref}</td>
                            </tr>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">User Name</th>
                                <td class="border border-gray-300 px-4 py-2">${receipt.user_name}</td>
                            </tr>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">User Email</th>
                                <td class="border border-gray-300 px-4 py-2">${receipt.user_email}</td>
                            </tr>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Project ID</th>
                                <td class="border border-gray-300 px-4 py-2">${receipt.project_id}</td>
                            </tr>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Amount Paid</th>
                                <td class="border border-gray-300 px-4 py-2">${receipt.amount} ${receipt.currency}</td>
                            </tr>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                                <td class="border border-gray-300 px-4 py-2">${receipt.status}</td>
                            </tr>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Payment Date</th>
                                <td class="border border-gray-300 px-4 py-2">${new Date(receipt.payment_date).toLocaleString()}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center mt-4">
                        <button class="btn-primary px-6 py-2" onclick="printReceipt()">Print Receipt</button>
                    </div>
                </div>
                `;
                } else {
                    receiptContent.innerHTML = `<p class="text-red-500">${data.message}</p>`;
                }
            })
            .catch((error) => {
                receiptContent.innerHTML = `<p class="text-red-500">Failed to load receipt: ${error.message}</p>`;
            });

        // Show the modal
        document.getElementById('receiptModal').classList.remove('hidden');
    }


    // Function to print the receipt
    function printReceipt() {
        const receiptContent = document.getElementById('receiptContent').innerHTML;
        const printWindow = window.open('', '', 'width=800, height=600');
        printWindow.document.write(`
        <html>
        <head>
            <title>Print Receipt</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                h3 { color: #00ADB5; }
            </style>
        </head>
        <body>
            ${receiptContent}
        </body>
        </html>
    `);
        printWindow.document.close();
        printWindow.print();
    }



    function closeReceiptModal() {
        document.getElementById('receiptModal').classList.add('hidden');
    }
    </script>


</body>

</html>