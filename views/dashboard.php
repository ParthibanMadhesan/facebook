<?php
session_start();
include '../config.php'; // Include your database connection file


if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];

//Fetch user data from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    die('User not found.');
}

$stmt = $pdo->prepare("
    SELECT DISTINCT p.*, 
           u.firstname,
           u.lastname,u.profile_picture,
           (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
           (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count
    FROM posts p
    JOIN users u ON p.user_id = u.id 
    LEFT JOIN friendships f ON (
            (f.user1_id = p.user_id AND f.user2_id = ?)
            OR
            (f.user2_id = p.user_id AND f.user1_id = ?)
        )
    WHERE p.user_id = ?
    OR EXISTS (
        SELECT 1 FROM friend_requests fr 
        WHERE fr.status = 'accepted' 
        AND (
            (fr.sender_id = f.user1_id AND fr.receiver_id = f.user2_id)
            OR
            (fr.sender_id = f.user2_id AND fr.receiver_id = f.user1_id)
        )
    )
    ORDER BY p.created_at DESC
");

$stmt->execute([$userId, $userId, $userId]);$userId = $_SESSION['user_id'];
$posts = $stmt->fetchAll();





//Fetch comments for each post
foreach ($posts as $post) {
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at ASC");
    $stmt->execute([$post['id']]);
    $post['comments'] = $stmt->fetchAll();
}

//Uncomment to fetch notifications if needed
// $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
// $stmt->execute([$userId]);
// $notifications = $stmt->fetchAll();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col h-screen">
    <!-- Navbar -->
    <?php include "../Partials/navbar.php"; ?>

    <!-- Main Content -->
    <div class="flex flex-1">
        <!-- Sidebar -->
        <?php include  "../Partials/sidebarc.php"; ?>

        <!-- Dashboard Container -->
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="flex flex-col gap-6 max-w-8xl mx-auto">
               <!-- Notifications Section -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Notifications</h2>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="mb-2 p-2 bg-gray-100 rounded">
                            <p class="text-gray-700"><?php echo htmlspecialchars($notification['message']); ?></p>
                            <p class="text-gray-500 text-sm"><?php echo date('F j, Y, g:i a', strtotime($notification['created_at'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                    <a href="notifications.php" class="text-blue-500 hover:underline">View all notifications</a>
                </div>
                
              <!-- Posts Section -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-gray-800 mb-4">Recent Posts</h2>
    <?php foreach ($posts as $post): ?>
        <div class="mb-4 p-4 bg-gray-50 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-800 inline-flex ">
            <?php
                    $photoP = htmlspecialchars($post['profile_picture']);
                    ?>
               <img src="<?php echo $photoP;?> " class=" w-10 h-10 rounded-full"><span class="ml-2 mt-1" >    <?php echo htmlspecialchars($post['firstname'] . ' ' . $post['lastname']); ?>
    </span> </h3>
            <p class="text-semibold mt-2 "><?php echo htmlspecialchars($post['content']); ?></p>
            <?php if (!empty($post['photo'])): ?>   
                <div class="mt-2">
                    <?php
                    $photoPath = htmlspecialchars($post['photo']);
                    ?>
                    <img src="<?php echo $photoPath; ?>" alt="Post Photo" class="w-full rounded-md shadow-md">
                </div>
            <?php endif; ?>
            <p class="text-gray-400 text-sm mt-2"><?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></p>
            <div class="mt-4 flex space-x-6">
                <button class="flex items-center text-blue-500 hover:text-blue-600" onclick="likePost(<?php echo $post['id']; ?>)">
                    <i class="fas fa-thumbs-up mr-1"></i>
                    Like (<span id="likeCount-<?php echo $post['id']; ?>"><?php echo $post['like_count']; ?></span>)
                </button>
                <button class="flex items-center text-green-500 hover:text-green-600" onclick="toggleComments(<?php echo $post['id']; ?>)">
                    <i class="fas fa-comment mr-1"></i>
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
                </div>
            </div>
        </main>
    </div>
    <script>
        function likePost(postId) {
    console.log('Liking post:', postId);
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
        } else {
            console.error('Like error:', data.error);
        }
    })
    .catch(error => console.error('Fetch error:', error));
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
</body>
</html>
