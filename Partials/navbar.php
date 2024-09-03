<nav class="bg-blue-600 p-4">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <!-- Left Section: Logo and Search Bar -->
        <div class="flex items-center space-x-4 mb-4 md:mb-0">
            <!-- Logo -->
            <div class="text-white font-bold text-2xl hover:text-white " title="dashboard">
                <a href="../views/dashboard.php">Facebook</a>
            </div>
            <!-- Search Bar -->
            <div class="relative">
                <input id="searchInput" type="text" class="p-2 rounded-md" placeholder="Search..." autocomplete="off">
                <div id="searchResults" class="absolute top-full mt-2 bg-white border border-gray-300 rounded-md w-full hidden"></div>
            </div>
        </div>
        <!-- Center Section: Navigation Links -->
        <div class="flex justify-center space-x-8 mb-4 md:mb-0">
            <a href="../views/dashboard.php" class="text-white relative group" title="home">
                <i class="fas fa-home text-3xl px-5 py-3 hover:bg-gray-300 rounded-xl"></i>
                <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-gray-700 text-white text-xs rounded opacity-0 ">Home</span>
            </a>
            <a href="/friends.php" class="text-white relative group" title="Friends">
                <i class="fas fa-user-friends text-3xl px-5 py-3 hover:bg-gray-300 rounded-xl"></i>
                <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-gray-700 text-white text-xs rounded opacity-0 ">Friends</span>
            </a>
            <a href="/createpost.php" class="text-white relative group" title="Create Post">
                <i class="fas fa-tv text-3xl px-5 py-3 hover:bg-gray-300 rounded-xl"></i>
                <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-gray-700 text-white text-xs rounded opacity-0 ">Create Post</span>
            </a>
            <a href="/notification.php" class="text-white relative group" title="Notifications">
                <i class="fas fa-store text-3xl px-5 py-3 hover:bg-gray-300 rounded-xl"></i>
                <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-gray-700 text-white text-xs rounded opacity-0 ">Notifications</span>
            </a>
            <a href="/friendslist.php" class="text-white relative group" title="Friends List">
                <i class="fas fa-users text-3xl px-5 py-3 hover:bg-gray-300 rounded-xl"></i>
                <span class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 px-2 py-1 bg-gray-700 text-white text-xs rounded opacity-0 ">Friends List</span>
            </a>
        </div>
        <!-- Right Section: Profile Icon -->
        <div class="flex items-center space-x-4">
            <?php if (isset($_SESSION['email'])): ?>
             <a href="/profile.php">  <img src="../views/<?php echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="Profile" class="w-10 h-10 rounded-full"></a>
                <div class="text-white"><?php echo htmlspecialchars($_SESSION['firstname']); ?></div>
            <?php endif; ?>
            <a href="/logout.php" class="text-white">Logout</a>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();

        if (query.length > 0) {
            fetch(`/search.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = ''; // Clear previous results
                    if (data.length > 0) {
                        searchResults.classList.remove('hidden');
                        data.forEach(user => {
                            const div = document.createElement('div');
                            div.className = 'px-4 py-2 border-b border-gray-200 hover:bg-gray-100 cursor-pointer';
                            div.innerHTML = `<a href="/profiles.view.php?id=${user.id}">${user.name}</a>`;
                            searchResults.appendChild(div);
                        });
                    } else {
                        searchResults.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        } else {
            searchResults.classList.add('hidden');
        }
    });

    // Hide results when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.classList.add('hidden');
        }
    });
});
</script>

