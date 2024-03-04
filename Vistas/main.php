<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../Public/CSS/main.css">
    
    <script>
        const hamburger = document.querySelector(".hamburger")
        const nav_menu = document.querySelector(".nav-menu")
        hamburger.addEventListener("click",()=>{
          hamburger.classList.toggle("active");
          nav_menu.classList.toggle("active");
        })
        document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
          hamburger.classList.remove("active");
          nav_menu.classList.remove("active");
        }))
    </script>

</head>
<body>
    <header class="bg_animate">
        <div class="container">
            <div class="logo">

            </div>
            <nav role="navigation">
                <div id="menuToggle">
                    <!--
                    A fake / hidden checkbox is used as click reciever,
                    so you can use the :checked selector on it.
                    -->
                    <input type="checkbox" />
                    
                    <!--
                    Some spans to act as a hamburger.
                    
                    They are acting like a real hamburger,
                    not that McDonalds stuff.
                    -->
                    <span></span>
                    <span></span>
                    <span></span>
                    
                    <!--
                    Too bad the menu has to be inside of the button
                    but hey, it's pure CSS magic.
                    -->
                    <ul id="menu">
                    <a href="#"><li>Home</li></a>
                    <a href="#"><li>About</li></a>
                    <a href="#"><li>Info</li></a>
                    <a href="#"><li>Contact</li></a>
                    <a href="https://erikterwan.com/" target="_blank"><li>Show me more</li></a>
                    </ul>
                </div>
                </nav>
            <div class="btn_Link">
                <ul class="UCL">
                    <li>Usuario</li>
                    <li><a href="">Carrito</a></li>
                    <li><a href="">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div>

    </div>
    <footer>

    </footer>
    <script src="../JS/hamburMenu.js"></script>
</body>
</html>