<?php
session_start();
include '../config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

// Fetch user data from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    die('User not found.');
}
  $stmt = $pdo->prepare("
            SELECT p.*, 
                   u.profile_picture, 
                   u.firstname, 
                   u.lastname,
                   (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
                   (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.user_id =?
            ORDER BY p.created_at DESC");
$stmt->execute([$userId]);

$posts = $stmt->fetchAll();

// // Fetch notifications
// $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
// $stmt->execute([$userId]);
// $notifications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['name']); ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .profile-cover {
            background-size: cover;
            background-position: center;
            height: 300px;
        }
        .profile-picture {
            border: 5px solid white;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        .profile-edit-icon {
            position: absolute;
            bottom: -10px;
            right: -10px;
            background: white;
            border-radius: 50%;
            padding: 8px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .bio-container {
            max-width: 800px;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <?php include "../Partials/navbar.php"; ?>

    <div class="flex">
        <!-- Sidebar -->
        <?php include "../Partials/sidebar.php"; ?>

        <!-- Profile Container -->
        <div class="flex-1 p-6">
            <!-- Cover Photo and Profile Picture -->
            <div class="relative">
                <div class="profile-cover" style="background-image: url('<?php echo htmlspecialchars($user['cover_photo']); ?>');">
                    <div class="absolute bottom-0 left-0 right-0 flex justify-center mb-4">
                        <div class="absolute">
                            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="<?php echo htmlspecialchars($user['name']); ?>'s Profile Picture" class="profile-picture border-4 border-white shadow-lg">
                            <div class="profile-edit-icon">
                                <a href="edit_profile_picture.php" class="flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232a2.828 2.828 0 11-4-4 2.828 2.828 0 014 4zM12 14a4 4 0 01-4-4 4 4 0 014-4 4 4 0 014 4 4 4 0 01-4 4z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-edit-icon">
                    <a href="edit_cover_photo.php" class="flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l3-3m0 0l3 3m-3-3v14m12-8l-3 3m0 0l-3-3m3 3V4m6 8v6a2 2 0 01-2 2H7a2 2 0 01-2-2V12a2 2 0 012-2h6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2h-2m-6 0a2 2 0 002-2V8a2 2 0 00-2-2h-6a2 2 0 00-2 2v12a2 2 0 002 2m4 0h4"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- User Name -->
            <div class="text-center mt-20">
                <h1 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($user['name']); ?></h1>
            </div>

            <!-- Bio Section -->
            <div class="bio-container mt-6 mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">About</h2>
                <p class="text-gray-600"><?php echo htmlspecialchars($user['bio']); ?></p>
                <a href="edit_bio.php" class="text-blue-500 hover:underline">Edit Bio</a>
            </div>

             <!-- Notifications Section -->
    <div class="container mt-6 mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Notifications</h2>
        <?php foreach ($notifications as $notification): ?>
            <div class="mb-2 p-2 bg-gray-100 rounded">
                <p class="text-gray-700"><?php echo htmlspecialchars($notification['message']); ?></p>
                <p class="text-gray-500 text-sm"><?php echo date('F j, Y, g:i a', strtotime($notification['created_at'])); ?></p>
            </div>
        <?php endforeach; ?>
        <a href="notifications.php" class="text-blue-500 hover:underline">View all notifications</a>
    </div>

          <!-- Posts Section -->
<div class="container mt-6 mx-auto bg-white p-6 rounded-lg shadow-lg">
    <div class="text-right mb-3">
        <a href="create_post.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Create Post</a>
    </div>
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">My Posts</h2>
    <?php foreach ($posts as $post): ?>
        <div class="mb-4 p-4 bg-gray-50 rounded-lg shadow-md">
            <div>
            <h3 class="text-xl font-semibold text-gray-800 inline-flex ">
            <?php
                    $phot = htmlspecialchars($post['profile_picture']);
                    ?>
               <img src="<?php echo $phot;?> " class=" w-10 h-10 rounded-full"><span class="ml-2 mt-1" >    <?php echo htmlspecialchars($post['firstname'] . ' ' . $post['lastname']); ?>
    </span> </h3>
    </div>
    
            <div class="flex justify-between items-start">
                <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($post['content']); ?></p>
                <button class="text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded ml-4" onclick="openModal(<?php echo htmlspecialchars($post['id']); ?>)">
                    Delete
                </button>

            </div>
            <?php if ($post['photo']): ?>
                <div class="mt-2">
                    <img src="<?php echo htmlspecialchars($post['photo']); ?>" alt="Post Photo" class="w-full rounded-md shadow-md">
                </div>
            <?php endif; ?>
            <p class="text-gray-400 text-sm mt-2"><?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></p>

            <div class="mt-4 flex space-x-4">
                    <button class="flex items-center text-blue-500 hover:text-blue-600" onclick="likePost(<?php echo $post['id']; ?>)">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                        </svg>
                        Like (<span id="likeCount-<?php echo $post['id']; ?>"><?php echo $post['like_count']; ?></span>)
                    </button>
                    <button class="flex items-center text-green-500 hover:text-green-600" onclick="toggleComments(<?php echo $post['id']; ?>)">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Comment (<?php echo $post['comment_count']; ?>)
                    </button>
                </div>
                
                <!-- Comments Section (hidden by default) -->
                <div id="comments-<?php echo $post['id']; ?>" class="mt-4 hidden">
                    <div id="commentList-<?php echo $post['id']; ?>">
                        <!-- Comments will be loaded here -->
                    </div>
                    <form onsubmit="event.preventDefault(); addComment(<?php echo $post['id']; ?>);" class="mt-2">
                        <input type="text" id="commentInput-<?php echo $post['id']; ?>" class="w-full border rounded p-2" placeholder="Add a comment...">
                        <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<!-- Modal Structure -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Deletion</h2>
        <p class="text-gray-600 mb-4">Are you sure you want to delete this post? This action cannot be undone.</p>
        <div class="flex justify-end">
            <button id="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">Cancel</button>
            <a id="confirmDelete" href="#" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">Delete</a>
        </div>
    </div>
</div>

<script>
    let postIdToDelete = null;

    function openModal(postId) {
        postIdToDelete = postId;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('confirmDelete').href = 'delete_post.php?id=' + postId;
    }

    document.getElementById('cancelDelete').addEventListener('click', () => {
        document.getElementById('deleteModal').classList.add('hidden');
    });

    function likePost(postId) {
            fetch('like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'post_id=' + postId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('likeCount-' + postId).textContent = data.likeCount;
                }
            });
        }

        function toggleComments(postId) {
            const commentsSection = document.getElementById('comments-' + postId);
            if (commentsSection.classList.contains('hidden')) {
                commentsSection.classList.remove('hidden');
                loadComments(postId);
            } else {
                commentsSection.classList.add('hidden');
            }
        }

        function loadComments(postId) {
            fetch('get_comments.php?post_id=' + postId)
            .then(response => response.text())
            .then(html => {
                document.getElementById('commentList-' + postId).innerHTML = html;
            });
        }

        function addComment(postId) {
            const input = document.getElementById('commentInput-' + postId);
            const content = input.value.trim();
            if (content) {
                fetch('add_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'post_id=' + postId + '&content=' + encodeURIComponent(content)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        loadComments(postId);
                    }
                });
            }
        }
   
</script>

        </div>
    </div>

</body>
</html>
