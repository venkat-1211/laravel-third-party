<!DOCTYPE html>
<html>
<head>
    <title>Typeform with Sidebar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet">

    <style>
        body {
            margin: 0;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        /* Top Navbar */
        .navbar-custom {
            height: 60px;
            background: #111827;
            color: white;
            position: fixed;
            width: 100%;
            z-index: 10;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 240px;
            height: calc(100vh - 60px);
            background: #1f2937;
            padding: 20px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #374151;
        }

        /* Main Content */
        .main {
            margin-left: 240px;
            margin-top: 60px;
            height: calc(100vh - 60px);
        }

        .typeform-container {
            width: 100%;
            height: 100%;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body>

<!-- Top Navbar -->
<nav class="navbar navbar-custom px-4 d-flex align-items-center">
    <h5 class="mb-0">My Laravel Dashboard</h5>
    <div class="ms-auto">Welcome, User</div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <h5 class="text-white mb-4">Menu</h5>
    <a href="#">🏠 Dashboard</a>
    <a href="#">📋 Forms</a>
    <a href="#">📊 Reports</a>
    <a href="#">⚙ Settings</a>
    <a href="#">🚪 Logout</a>
</div>

<!-- Main Content -->
<div class="main">
    <div class="typeform-container">
        <iframe 
            src="https://form.typeform.com/to/CCYUp2cx" 
            allow="camera; microphone; autoplay; encrypted-media;">
        </iframe>
    </div>
</div>

</body>
</html>
