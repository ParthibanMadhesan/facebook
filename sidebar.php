<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
</head>
<body>
    <!-- Left Sidebar Start -->
    <div class="w-80 px-2 min-h-screen flex flex-col py-2  bg-gray-200">
        <div class="group">
            <div class="text-white mt-1">
                <a href="/profile.php" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <?php session_start() ?>
                <?php if (isset($_SESSION['email'])): ?>
                    <div class="w-10 h-10 rounded-full overflow-hidden">
                        <img class="w-full" src="../views/<?php  echo htmlspecialchars($_SESSION['profile_picture']); ?>" alt="MD. Shibbir Ahmed">
                    </div>

                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black"><?php echo htmlspecialchars($_SESSION['email']); ?></h2>
                    </div>
                    <?php endif; ?>
                </a>
                <a href="friends.php" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black">friends</h2>
                    </div>
                </a>
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="ffont-normal text-black">Pages</h2>
                        <div class="text-xs text-blue-400 flex justify-start items-center space-x-1">
                            <span class="w-2 h-2 bg-blue-400 inline-block rounded-full"></span>
                            <span>9+ new</span>
                        </div>
                    </div>
                </a>
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black">Groups</h2>
                        <div class="text-xs text-blue-400 flex justify-start items-center space-x-1">
                            <span class="w-2 h-2 bg-blue-400 inline-block rounded-full"></span>
                            <span>1 new</span>
                        </div>
                    </div>
                </a>
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black">Marketplace</h2>
                        <div class="text-xs text-blue-400 flex justify-start items-center space-x-1">
                            <span class="w-2 h-2 bg-blue-400 inline-block rounded-full"></span>
                            <span>1 new</span>
                        </div>
                    </div>
                </a>
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black">See More</h2>
                    </div>
                </a>
            </div>
        </div>
        <div class="border-t border-gray-700 my-2"></div>
        <div class="group flex-1">
            <div class="flex justify-between items-center">
                <h3 class="text-gray-500 font-semibold text-lg">Your Shortcuts</h3>
                <a href="#" class="hover:bg-gray-300 text-blue-500 p-2 rounded-md opacity-0 group-hover:opacity-100">Edit</a>
            </div>
            <div class="text-white mt-1">
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden">
                        <img class="w-full" src="https://picsum.photos/200/300?random=1" alt="8 Ball Pool">
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black">8 Ball Pool</h2>                                         
                    </div>
                </a>
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden">
                        <img class="w-full" src="https://picsum.photos/200/300?random=2" alt="Standoff 2">
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black">Standoff 2</h2>
                    </div>
                </a>
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden">
                        <img class="w-full" src="https://picsum.photos/200/300?random=3" alt="Call of Duty">
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black">Call of Duty</h2>
                    </div>
                </a>
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden">
                        <img class="w-full" src="https://picsum.photos/200/300?random=4" alt="Candy Crush Saga">
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-normal text-black">Candy Crush Saga</h2>
                    </div>
                </a>
            </div>
        </div>
        <div class="border-t border-gray-700 my-2"></div>
        <div class="group flex-1">
            <div class="flex justify-between items-center">
                <h3 class="text-gray-500 font-semibold text-lg">Explore</h3>
                <a href="#" class="hover:bg-gray-300 text-blue-500 p-2 rounded-md opacity-0 group-hover:opacity-100">Edit</a>
            </div>
            <div class="text-white mt-1">
                <a href="#" class="hover:bg-gray-300 px-2.5 py-1.5 flex items-center space-x-3 rounded-md">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17l-1.5 1.5-4.5-4.5 1.5-1.5 4.5 4.5zm1.5 1.5l7.5-7.5m0 0l-7.5-7.5m7.5 7.5H3" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center content-start">
                        <h2 class="font-semibold">See More</h2>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Left Sidebar End -->
</body>
</html>
