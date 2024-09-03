<?php
session_start();
include '../config.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

// Check if a friend_id is provided
if (!isset($_GET['friend_id'])) {
    die('No friend selected.');
}

$friendId = filter_input(INPUT_GET, 'friend_id', FILTER_SANITIZE_NUMBER_INT);

// Fetch friend's data from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$friendId]);
$friend = $stmt->fetch();

if (!$friend) {
    die('Friend not found.');
}

// Fetch friend's posts
$stmt = $pdo->prepare("
    SELECT p.*, 
           u.profile_picture, 
           u.firstname, 
           u.lastname,
           (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$friendId]);

$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($friend['name']); ?>'s Profile</title>
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
                <div class="profile-cover" style="background-image: url('<?php echo htmlspecialchars($friend['cover_photo']); ?>');">
                    <div class="absolute bottom-0 left-0 right-0 flex justify-center mb-4">
                        <div class="absolute">
                            <img src="<?php echo htmlspecialchars($friend['profile_picture']); ?>" alt="<?php echo htmlspecialchars($friend['name']); ?>'s Profile Picture" class="profile-picture border-4 border-white shadow-lg">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Friend's Name -->
            <div class="text-center mt-20">
                <h1 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($friend['name']); ?></h1>
            </div>

            <!-- Bio Section -->
            <div class="bio-container mt-6 mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">About</h2>
                <p class="text-gray-600"><?php echo htmlspecialchars($friend['bio']); ?></p>
            </div>

            <!-- Posts Section -->
            <div class="container mt-6 mx-auto bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4"><?php echo htmlspecialchars($friend['name']); ?>Posts</h2>
                
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
                        
                        <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($post['content']); ?></p>
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

            <script>
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