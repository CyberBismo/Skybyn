<?php
session_start();

function domainCheck() {
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url = parse_url($url, PHP_URL_HOST);
    return $url;
}

$devDomain = 'dev.skybyn.no';
$currentUrl = domainCheck();
if ($currentUrl == $devDomain) {
    $dev_access = true;
}

if ($dev_access) {
    include("../assets/functions.php");
} else {
    include("https://skybyn.no/assets/functions.php");
}

if (!isset($_SESSION['user'])) {
    if ($dev_access) {
        header('Location: ../');
    } else {
        header('Location: https://skybyn.no/');
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Skybyn Administration</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
        }

        .sidebar {
            background-color: #222;
            width: 200px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
        }

        .sidebar-item {
            margin-bottom: 10px;
            cursor: pointer;
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
        }

        h1, h2 {
            color: #ddd;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #555;
        }

        tr:nth-child(even) {
            background-color: #474747;
        }

        button {
            background-color: #565656;
            color: white;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            margin-right: 5px;
        }

        button:hover {
            background-color: #666;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-item">Dashboard</div>
        <div class="sidebar-item">Users</div>
        <div class="sidebar-item">Pages</div>
        <div class="sidebar-item">Groups</div>
        <div class="sidebar-item">Settings</div>
    </div>
    <div class="main-content">
        <h1>Dashboard</h1>
        <div class="section users">
            <h2>Users</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php $getUsers = $conn->query("SELECT * FROM USERS");?>
                <tr>
                    <td>John Doe</td>
                    <td>Admin</td>
                    <td>Active</td>
                    <td><button>Edit</button> <button>Delete</button></td>
                </tr>
            </table>
        </div>
        <div class="section pages">
            <h2>Pages</h2>
            <!-- Pages List -->
        </div>
        <div class="section groups">
            <h2>Groups</h2>
            <!-- Groups List -->
        </div>
    </div>
</body>
</html>