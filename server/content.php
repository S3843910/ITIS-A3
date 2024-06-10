<?php
	session_start();
	if(!isset($_SESSION['login'])){
		header('Location: ../client/login.html');
	}

    // Fetch session variables
    $username = $_SESSION['username'];
    $role_id = $_SESSION['role_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Library</title>
    <link rel="stylesheet" href="../client/css/book_library.css">
</head>
<body>
    <h1>Book Library</h1>
    
    <input type="text" id="searchInput" placeholder="Search for books...">
    <ul id="bookList">
        <!-- This will be populated with books dynamically -->
    </ul>

    <script>
        // Function to display books based on search input
        let debounceTimer;
        function displayBooks() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const searchInput = document.getElementById("searchInput").value.toLowerCase();
                const bookList = document.getElementById("bookList");
                bookList.innerHTML = "";

                fetch(`get_books.php?search=${searchInput}`)
                    .then(response => response.json())
                    .then(books => {
                        books.forEach(book => {
                            const li = document.createElement("li");
                            li.innerHTML = `
                                <div class="book-info">
                                    <div class="book-name">${book.name}</div>
                                    <div class="book-genre">${book.genre}</div>
                                </div>
                                <a href="books/${book.filename}" download><button class="download-button">Download</button></a>`;
                            bookList.appendChild(li);
                        });
                    });
            }, 300); // Adjust the debounce delay (in milliseconds) as needed
        }

        // Initial display of books
        displayBooks();

        // Event listener for search input
        document.getElementById("searchInput").addEventListener("input", displayBooks);
    </script>

<div class="button-container">
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Logout</button>
        </form>
        <?php if ($role_id == 1): // role_id 1 is for admin ?>
            <form action="admin.php" method="get">
                <button type="submit" class="admin-button">Go to Admin Page</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
