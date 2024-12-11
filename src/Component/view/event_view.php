<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></title> 
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
  <header>
    <?php
          echo $navbar->render();
    ?>
  </header> 

  <main class="container mx-auto px-4 py-8">
    <div class="bg-gray-800 rounded-lg p-6 mb-2">
        <h1 class="text-3xl font-semibold mb-4"><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><strong class="text-orange-400">Description:</strong> <?php echo htmlspecialchars($event['event_description'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong class="text-orange-400">Date:</strong> <?php echo date("F j, Y, g:i a", strtotime($event['event_date'])); ?></p>
        <p><strong class="text-orange-400">Location:</strong> <?php echo htmlspecialchars($event['location'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong class="text-orange-400">Registrations:</strong> <?php echo $registration_count; ?> people registered</p> 
    </div>

    <!-- Zielona informacja, gdy użytkownik jest już zapisany -->
    <?php if ($isRegistered): ?>
        <div class="bg-green-800 p-4 rounded-lg mb-8">
            <p class="text-center">You are already registered for this event.</p>
        </div>
    <?php endif; ?>

    <br>

    <?php if (!$isRegistered): ?>
        <?php if ($isLoggedIn): ?>
            <div class="flex justify-center">
                <form method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                    <button type="submit" name="register" class="bg-orange-500 text-white px-6 py-3 rounded-lg hover:bg-orange-600">Register for Event</button>
                </form>
            </div>
        <?php else: ?>
            <div class="bg-gray-800 p-4 rounded-lg">
                <p>You need to <a href="login.php" class="text-orange-500">log in</a> to register for this event.</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="flex justify-center mt-4">
            <form method="POST">
                <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                <button type="submit" name="unregister" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600">Unregister from Event</button>
            </form>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
