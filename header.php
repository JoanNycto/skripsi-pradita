<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKRIPSI PRADITA</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-item logo-profil">
            <a href="#profil">
                <img src="images/logo.png" alt="Profil" height="50">
            </a>
        </div>
        <ul class="navbar-nav">
            <li class="nav-item"><a href="homepage.php">Dashboard</a></li>
            <li class="nav-item"><a href="berkas_skripsi.php">Berkas Skripsi</a></li>
            <li class="nav-item"><a href="proposal.php">Proposal</a></li>
            <li class="nav-item"><a href="logbook.php">Logbook</a></li>
            <li class="nav-item"><a href="logout.php">Logout</a></li>
        </ul>
        <!-- <div class="nav-item logo-main">
            <div class = "dropdown">
                <button class="buttonLogout">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="grey" class="w-10 h-10">
                        <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                        clip-rule="evenodd" />
                    </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-hidden">
                <a href="profile.php" class="">Profile</a>
                <a href="logout.php" class="">Logout</a>
                </div>
            </div>
        </div> -->
    
        <!-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                const dropdownButton = document.querySelector('.buttonLogout');
                const dropdownMenu = document.querySelector('.dropdown-menu');

                dropdownButton.addEventListener('click', () => {
                    if (dropdownMenu.classList.contains('dropdown-menu-hidden')) {
                        dropdownMenu.classList.remove('dropdown-menu-hidden');
                        dropdownMenu.classList.add('dropdown-menu-visible');
                    } else {
                        dropdownMenu.classList.remove('dropdown-menu-visible');
                        dropdownMenu.classList.add('dropdown-menu-hidden');
                    }
                });
            });
        </script> -->
    </nav>